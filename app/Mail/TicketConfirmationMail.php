<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $attendee;

    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;
    }

    public function build()
    {
        $attendee = $this->attendee->loadMissing([
            'ticketCategory.event',
        ]);

        $category = $attendee->ticketCategory;
        $event = $category->event;

        if (empty($attendee->verification_token)) {
            $attendee->verification_token = Str::random(64);
            $attendee->save();
        }

        $formattedTicketRef = $attendee->ticket_code;

        /*
         * Load the saved Ticket Design Studio configuration.
         */
        $design = $category->ticket_design ?? [];
        $design = is_array($design) ? $design : [];

        $elements = is_array($design['elements'] ?? null)
            ? $design['elements']
            : [];

        /*
         * Load the saved background from Laravel's public storage disk.
         * Supports normal public-disk paths and existing HTTP(S) URLs.
         */
        $ticketBgBase64 = null;
        $background = $design['background'] ?? null;

        if (is_string($background) && $background !== '') {
            try {
                if (preg_match('~^https?://~i', $background)) {
                    $imageData = @file_get_contents(
                        $background,
                        false,
                        stream_context_create([
                            'http' => [
                                'timeout' => 5,
                            ],
                        ])
                    );

                    $mime = null;
                } else {
                    $backgroundPath = ltrim($background, '/');

                    // Handle paths stored as "storage/..."
                    if (str_starts_with($backgroundPath, 'storage/')) {
                        $backgroundPath = substr($backgroundPath, strlen('storage/'));
                    }

                    if (Storage::disk('public')->exists($backgroundPath)) {
                        $imageData = Storage::disk('public')->get($backgroundPath);
                    } else {
                        $imageData = null;
                    }

                    $mime = null;
                }

                if (!empty($imageData)) {
                    if (!empty($mime)) {
                        $imageMime = $mime;
                    } else {
                        $imageMime = null;

                        if (function_exists('finfo_open')) {
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);

                            if ($finfo) {
                                $imageMime = finfo_buffer($finfo, $imageData);
                                finfo_close($finfo);
                            }
                        }

                        $imageMime = $imageMime ?: 'image/jpeg';

                        // DomPDF supports common raster formats.
                        if (!in_array($imageMime, [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ], true)) {
                            $imageMime = 'image/jpeg';
                        }
                    }

                    $ticketBgBase64 = 'data:' . $imageMime
                        . ';base64,' . base64_encode($imageData);
                }
            } catch (\Throwable $e) {
                logger()->warning('Could not load ticket design background.', [
                    'category_id' => $category->id,
                    'background' => $background,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        /*
         * Generate the verification QR code.
         */
        $verificationUrl = route('ticket.verify', [
            'token' => $attendee->verification_token,
        ]);

        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=310x310&data='
            . urlencode($verificationUrl);

        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
            ],
        ]);

        $qrImageData = @file_get_contents(
            $qrApiUrl,
            false,
            $context
        );

        $qrBase64 = $qrImageData
            ? 'data:image/png;base64,' . base64_encode($qrImageData)
            : null;

        /*
         * Pass the same saved design and background into the PDF Blade.
         */
        $data = [
            'attendee' => $attendee,
            'event' => $event,
            'formattedTicketRef' => $formattedTicketRef,
            'ticketDesign' => $design,
            'ticketBgBase64' => $ticketBgBase64,
            'qrBase64' => $qrBase64,
        ];

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isFontSubsettingEnabled' => true,
        ])->loadView('emails.ticket_pdf', $data);

        $mail = $this
            ->subject('Congratulations! Your Event Ticket - ' . $event->title)
            ->view('emails.ticket_notification', $data)
            ->attachData(
                $pdf->output(),
                'Ticket_' . $formattedTicketRef . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );

        /*
         * Preserve event item attachments.
         */
        if ($event->items && $event->items->count() > 0) {
            foreach ($event->items as $item) {
                if (
                    $item->image
                    && Storage::disk('public')->exists($item->image)
                ) {
                    $mail->attach(
                        Storage::disk('public')->path($item->image),
                        [
                            'as' => Str::slug($item->title)
                                . '.'
                                . pathinfo(
                                    $item->image,
                                    PATHINFO_EXTENSION
                                ),
                        ]
                    );
                }
            }
        }

        /*
         * Preserve waiver, consent, and race guide attachments.
         */
        $attachments = [
            'english_waiver' => 'English_Waiver.pdf',
            'burmese_waiver' => 'Burmese_Waiver.pdf',
            'english_consent' => 'English_Consent.pdf',
            'burmese_consent' => 'Burmese_Consent.pdf',
            'english_race_guide' => 'English_Race_Guide.pdf',
            'burmese_race_guide' => 'Burmese_Race_Guide.pdf',
        ];

        foreach ($attachments as $field => $filename) {
            $path = $event->{$field};

            if (
                $path
                && Storage::disk('public')->exists($path)
            ) {
                $mail->attach(
                    Storage::disk('public')->path($path),
                    ['as' => $filename]
                );
            }
        }

        return $mail;
    }

    private function resizeImageToBase64(
        $filePath,
        $maxWidth,
        $maxHeight
    ) {
        if (!file_exists($filePath)) {
            return null;
        }

        if (!function_exists('imagecreatefromjpeg')) {
            $imageBinary = @file_get_contents($filePath);
            $mime = mime_content_type($filePath) ?: 'image/jpeg';

            return $imageBinary
                ? 'data:' . $mime . ';base64,' . base64_encode($imageBinary)
                : null;
        }

        $imageInfo = @getimagesize($filePath);

        if (!$imageInfo) {
            return null;
        }

        list($origWidth, $origHeight, $imageType) = $imageInfo;

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = @imagecreatefromjpeg($filePath);
                break;

            case IMAGETYPE_PNG:
                $sourceImage = @imagecreatefrompng($filePath);
                break;

            case IMAGETYPE_GIF:
                $sourceImage = @imagecreatefromgif($filePath);
                break;

            default:
                return null;
        }

        if (!$sourceImage) {
            return null;
        }

        $ratio = min(
            $maxWidth / $origWidth,
            $maxHeight / $origHeight
        );

        if ($ratio > 1) {
            $ratio = 1;
        }

        $newWidth = max(1, round($origWidth * $ratio));
        $newHeight = max(1, round($origHeight * $ratio));

        $virtualImage = imagecreatetruecolor($newWidth, $newHeight);

        if (
            $imageType === IMAGETYPE_PNG
            || $imageType === IMAGETYPE_GIF
        ) {
            imagecolortransparent(
                $virtualImage,
                imagecolorallocatealpha($virtualImage, 0, 0, 0, 127)
            );

            imagealphablending($virtualImage, false);
            imagesavealpha($virtualImage, true);
        }

        imagecopyresampled(
            $virtualImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $origWidth,
            $origHeight
        );

        ob_start();

        if ($imageType === IMAGETYPE_JPEG) {
            imagejpeg($virtualImage, null, 75);
            $mime = 'image/jpeg';
        } else {
            imagepng($virtualImage, null, 6);
            $mime = 'image/png';
        }

        $imageData = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($virtualImage);

        return 'data:' . $mime . ';base64,' . base64_encode($imageData);
    }
}
