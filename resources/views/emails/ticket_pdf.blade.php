
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Event Ticket</title>

    <style>
        @page {
            margin: 0;
            size: 1200pt 387.75pt;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 1600px;
            height: 517px;
            font-family: Helvetica, Arial, sans-serif;
        }

        .ticket-wrapper {
            position: relative;
            width: 1600px;
            height: 517px;
            overflow: hidden;
            background: #ffffff;
        }

        .ticket-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 1600px;
            height: 517px;
        }

        .ticket-element {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 4px;
            text-align: center;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .ticket-element-text {
            display: block;
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: clip;
            line-height: 1.2;
        }

        .ticket-element-qr {
            padding: 0;
            background: #ffffff;
        }

        .ticket-element-qr img {
            display: block;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    @php
        $design = $ticketDesign ?? ($attendee->ticketCategory->ticket_design ?? []);
        $design = is_array($design) ? $design : [];

        $elements = is_array($design['elements'] ?? null)
            ? $design['elements']
            : [];

        $event = $attendee->ticketCategory->event ?? null;

        $elementValues = [
            'attendee_name' => $attendee->full_name ?? '',
            'phone' => $attendee->phone ?? '',
            'ticket_number' => $formattedTicketRef ?? $attendee->ticket_code ?? '',
            'bib_number' => $attendee->bib_number ?? '',
            'event_name' => $event->title ?? '',
        ];
    @endphp

    <div class="ticket-wrapper">
        @if(!empty($ticketBgBase64))
            <img
                src="{{ $ticketBgBase64 }}"
                class="ticket-background"
                alt="Ticket background"
            >
        @elseif(!empty($design['background']))
            {{-- The controller should convert the stored background to a data URI. --}}
        @endif

        @foreach($elements as $element)
            @php
                $type = $element['type'] ?? '';

                $x = max(0, min(1600, (float) ($element['x'] ?? 0)));
                $y = max(0, min(517, (float) ($element['y'] ?? 0)));
                $width = max(1, min(1600, (float) ($element['width'] ?? 100)));
                $height = max(1, min(517, (float) ($element['height'] ?? 40)));
                $fontSize = max(6, min(120, (float) ($element['fontSize'] ?? 24)));
                $rotation = max(-360, min(360, (float) ($element['rotation'] ?? 0)));

                $color = $element['color'] ?? '#222222';

                if (!is_string($color) || !preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                    $color = '#222222';
                }

                $style = sprintf(
                    'left: %.2fpx; top: %.2fpx; width: %.2fpx; height: %.2fpx; font-size: %.2fpx; color: %s; transform: rotate(%.2fdeg);',
                    $x,
                    $y,
                    $width,
                    $height,
                    $fontSize,
                    $color,
                    $rotation
                );
            @endphp

            <div
                class="ticket-element {{ $type === 'qr' ? 'ticket-element-qr' : '' }}"
                style="{{ $style }}"
            >
                @if($type === 'qr')
                    @if(!empty($qrBase64))
                        <img
                            src="{{ $qrBase64 }}"
                            alt="Ticket verification QR code"
                        >
                    @endif
                @elseif(array_key_exists($type, $elementValues))
                    <span class="ticket-element-text">{{ $elementValues[$type] }}</span>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
