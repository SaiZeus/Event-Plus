<?php



namespace App\Http\Controllers;



use App\Models\Event;

use App\Models\TicketCategory;

use App\Models\PromoCode;

use App\Models\Attendee;

use App\Models\Order;

use App\Models\EventItem;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use Barryvdh\DomPDF\Facade\Pdf;

use Throwable;

use App\Exports\EventAttendeesExport;

use Maatwebsite\Excel\Facades\Excel;



class AdminEventController extends Controller

{

    public function index()

    {

        $events = Event::with([

                'ticketCategories',

                'items'

            ])

            ->withCount('ticketCategories')

            ->latest()

            ->get();



        return view('admin.events.index', compact('events'));

    }



    public function create()

    {

        return view('admin.events.create');

    }



    public function store(Request $request)

    {

        if ($request->input('promo_ticket_category_id') === '') {

            $request->merge(['promo_ticket_category_id' => null]);

        }



        $validated = $request->validate(

            [

                'title' => 'required|string|max:255',

                'description' => 'required|string',

                'location' => 'required|string',



                // Google Maps coordinates

                'latitude' => 'nullable|numeric|between:-90,90',

                'longitude' => 'nullable|numeric|between:-180,180',



                'event_date' => 'required|date',

                'status' => 'required|in:upcoming,live,past',



                'creator_name' => 'required|string|max:255',

                'creator_phone' => 'required|string|max:50',

                'creator_email' => 'required|email|max:255',



                'overall_capacity' => 'nullable|integer|min:1',



                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',



                'english_waiver' => 'nullable|file|mimes:pdf|max:10240',

                'burmese_waiver' => 'nullable|file|mimes:pdf|max:10240',

                'english_consent' => 'nullable|file|mimes:pdf|max:10240',

                'burmese_consent' => 'nullable|file|mimes:pdf|max:10240',

                'english_race_guide' => 'nullable|file|mimes:pdf|max:10240',

                'burmese_race_guide' => 'nullable|file|mimes:pdf|max:10240',



                // T-Shirt Sizes

                'tshirt_sizes' => 'nullable|array',

                'tshirt_sizes.*' => 'string|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',



                'categories' => 'required|array|min:1',

                'categories.*.name' => 'required|string|max:255',

                'categories.*.local_price' => 'required|numeric|min:0',

                'categories.*.foreign_price' => 'nullable|numeric|min:0',

                'categories.*.capacity' => 'nullable|integer|min:1',

                'categories.*.bib_prefix' => 'nullable|string|max:3',

                'categories.*.bib_start_number' => 'nullable|integer|min:1',



                'promo_code' => 'nullable|string|max:100',

                'promo_type' => 'nullable|in:fixed,percentage',

                'promo_value' => 'nullable|numeric|min:0',

                'promo_ticket_category_id' => 'nullable',

                'company_name' => 'nullable|string|max:255',

                'promo_quantity' => 'nullable|integer|min:1|max:500',

                'max_uses' => 'nullable|integer|min:1',



                'items' => 'nullable|array',

                'items.*.title' => 'nullable|string|max:255',

                'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            ],

            [

                'categories.required' => 'Please add at least one ticket category.',

                'categories.min' => 'Please add at least one ticket category.',

                'categories.*.name.required' => 'Every ticket category must have a name.',

                'categories.*.local_price.required' => 'Every ticket category must have a local price.',

                'categories.*.local_price.numeric' => 'Ticket category price must be a valid number.',



                'creator_name.required' => 'Please enter the event creator name.',

                'creator_phone.required' => 'Please enter the event creator phone number.',

                'creator_email.required' => 'Please enter the event creator email address.',



                'image.max' => 'The event banner must not be larger than 2MB.',



                'english_waiver.mimes' => 'The English waiver must be a valid PDF file.',

                'english_waiver.max' => 'The English waiver must not be larger than 10MB.',



                'burmese_waiver.mimes' => 'The Burmese waiver must be a valid PDF file.',

                'burmese_waiver.max' => 'The Burmese waiver must not be larger than 10MB.',



                'english_consent.mimes' => 'The English consent form must be a valid PDF file.',

                'english_consent.max' => 'The English consent form must not be larger than 10MB.',



                'burmese_consent.mimes' => 'The Burmese consent form must be a valid PDF file.',

                'burmese_consent.max' => 'The Burmese consent form must not be larger than 10MB.',



                'english_race_guide.mimes' => 'The English race guide must be a valid PDF file.',

                'english_race_guide.max' => 'The English race guide must not be larger than 10MB.',



                'burmese_race_guide.mimes' => 'The Burmese race guide must be a valid PDF file.',

                'burmese_race_guide.max' => 'The Burmese race guide must not be larger than 10MB.',



                'tshirt_sizes.*.in' => 'Invalid T-shirt size selected.',



                'items.*.image.max' => 'An event item image must not be larger than 2MB.',

            ]

        );



        try {

            DB::transaction(function () use ($request) {



                $slug = Str::slug($request->title);

                $originalSlug = $slug;

                $count = 1;



                while (Event::where('slug', $slug)->exists()) {

                    $slug = $originalSlug . '-' . $count++;

                }



                $imagePath = $request->hasFile('image')

                    ? $request->file('image')->store('events', 'public')

                    : null;



                $englishWaiverPath = $request->hasFile('english_waiver')

                    ? $request->file('english_waiver')->store('waivers', 'public')

                    : null;



                $burmeseWaiverPath = $request->hasFile('burmese_waiver')

                    ? $request->file('burmese_waiver')->store('waivers', 'public')

                    : null;



                $englishConsentPath = $request->hasFile('english_consent')

                    ? $request->file('english_consent')->store('consents', 'public')

                    : null;



                $burmeseConsentPath = $request->hasFile('burmese_consent')

                    ? $request->file('burmese_consent')->store('consents', 'public')

                    : null;



                $englishRaceGuidePath = $request->hasFile('english_race_guide')

                    ? $request->file('english_race_guide')->store('race_guides', 'public')

                    : null;



                $burmeseRaceGuidePath = $request->hasFile('burmese_race_guide')

                    ? $request->file('burmese_race_guide')->store('race_guides', 'public')

                    : null;



                $event = Event::create([

                    'title' => $request->title,

                    'slug' => $slug,

                    'description' => $request->description,

                    'location' => $request->location,



                    // Google Maps coordinates

                    'latitude' => $request->input('latitude'),

                    'longitude' => $request->input('longitude'),



                    'event_date' => $request->event_date,

                    'status' => $request->status,



                    'image' => $imagePath,



                    'english_waiver' => $englishWaiverPath,

                    'burmese_waiver' => $burmeseWaiverPath,

                    'english_consent' => $englishConsentPath,

                    'burmese_consent' => $burmeseConsentPath,

                    'english_race_guide' => $englishRaceGuidePath,

                    'burmese_race_guide' => $burmeseRaceGuidePath,



                    'creator_name' => $request->creator_name,

                    'creator_phone' => $request->creator_phone,

                    'creator_email' => $request->creator_email,



                    'overall_capacity' => $request->overall_capacity,



                    'enabled_fields' => $request->input('enabled_fields', []),

                    'tshirt_sizes' => $request->input('tshirt_sizes', []),



                    'enable_bib_number' => $request->has('enable_bib_number'),



                    'share_bib_prefix' => $request->input('share_bib_prefix', '1') === '1',



                    'event_bib_prefix' => strtoupper(

                        substr($request->input('event_bib_prefix', ''), 0, 3)

                    ),



                    'event_bib_start_number' => $request->input(

                        'event_bib_start_number',

                        1

                    ),

                ]);



                $categoryMap = [];



                foreach ($request->categories as $index => $categoryData) {



                    $cat = TicketCategory::create([

                        'event_id' => $event->id,

                        'name' => $categoryData['name'],

                        'local_price' => $categoryData['local_price'],

                        'foreign_price' => $categoryData['foreign_price'] ?? null,

                        'capacity' => $categoryData['capacity'] ?? null,

                        'tickets_sold' => 0,



                        'bib_prefix' => strtoupper(

                            substr($categoryData['bib_prefix'] ?? '', 0, 3)

                        ),



                        'bib_start_number' => $categoryData['bib_start_number'] ?? 1,

                    ]);



                    $categoryMap[$index] = $cat;

                    $categoryMap[$cat->id] = $cat;

                }



                if (

                    $request->filled('promo_code') &&

                    $request->filled('promo_value')

                ) {



                    $promoTicketCategoryId = null;



                    if ($request->filled('promo_ticket_category_id')) {



                        $selectedVal = $request->promo_ticket_category_id;



                        if (isset($categoryMap[$selectedVal])) {

                            $promoTicketCategoryId =

                                $categoryMap[$selectedVal]->id;

                        }

                    }



                    $quantity = (int) $request->input('promo_quantity', 1);



                    $companyName = $request->input('company_name');



                    $baseCode = strtoupper(

                        trim($request->promo_code)

                    );



                    if ($quantity > 1) {



                        for ($i = 1; $i <= $quantity; $i++) {



                            $formattedCode = $baseCode . '-' . str_pad(

                                $i,

                                3,

                                '0',

                                STR_PAD_LEFT

                            );



                            PromoCode::create([

                                'event_id' => $event->id,

                                'ticket_category_id' => $promoTicketCategoryId,

                                'company_name' => $companyName,

                                'code' => $formattedCode,

                                'discount_type' => $request->promo_type ?? 'fixed',

                                'discount_value' => $request->promo_value,

                                'max_uses' => 1,

                                'uses_count' => 0,

                                'status' => 'active',

                            ]);

                        }



                    } else {



                        PromoCode::create([

                            'event_id' => $event->id,

                            'ticket_category_id' => $promoTicketCategoryId,

                            'company_name' => $companyName,

                            'code' => $baseCode,

                            'discount_type' => $request->promo_type ?? 'fixed',

                            'discount_value' => $request->promo_value,

                            'max_uses' => $request->input('max_uses', 1),

                            'uses_count' => 0,

                            'status' => 'active',

                        ]);

                    }

                }



                if ($request->has('items')) {



                    foreach ($request->items as $itemData) {



                        if (

                            empty($itemData['title']) &&

                            empty($itemData['image'])

                        ) {

                            continue;

                        }



                        $itemImage = null;



                        if (

                            isset($itemData['image']) &&

                            $itemData['image'] instanceof \Illuminate\Http\UploadedFile

                        ) {

                            $itemImage = $itemData['image']

                                ->store('event-items', 'public');

                        }



                        EventItem::create([

                            'event_id' => $event->id,

                            'title' => $itemData['title'] ?? null,

                            'image' => $itemImage,

                        ]);

                    }

                }

            });



            return redirect()

                ->route('admin.events.index')

                ->with('success', 'Event created successfully!');



        } catch (Throwable $e) {



            \Log::error('Event creation failed', [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

            ]);



            return redirect()

                ->back()

                ->withInput()

                ->with(

                    'error',

                    'The event could not be published. Please check the form and try again.'

                );

        }

    }



    public function edit(Event $event)

    {

        $event->load([

            'ticketCategories',

            'promoCodes',

            'items'

        ]);



        return view('admin.events.edit', compact('event'));

    }



    public function update(Request $request, Event $event)

    {

        if ($request->input('promo_ticket_category_id') === '') {

            $request->merge(['promo_ticket_category_id' => null]);

        }



        $request->validate([

            'title' => 'required|string|max:255',

            'description' => 'required|string',

            'location' => 'required|string',



            // Google Maps coordinates

            'latitude' => 'nullable|numeric|between:-90,90',

            'longitude' => 'nullable|numeric|between:-180,180',



            'event_date' => 'required|date',

            'status' => 'required|in:upcoming,live,past',



            'creator_name' => 'required|string|max:255',

            'creator_phone' => 'required|string|max:50',

            'creator_email' => 'required|email|max:255',



            'overall_capacity' => 'nullable|integer|min:1',



            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',



            'english_waiver' => 'nullable|file|mimes:pdf|max:10240',

            'burmese_waiver' => 'nullable|file|mimes:pdf|max:10240',

            'english_consent' => 'nullable|file|mimes:pdf|max:10240',

            'burmese_consent' => 'nullable|file|mimes:pdf|max:10240',

            'english_race_guide' => 'nullable|file|mimes:pdf|max:10240',

            'burmese_race_guide' => 'nullable|file|mimes:pdf|max:10240',



            // T-Shirt Sizes

            'tshirt_sizes' => 'nullable|array',

            'tshirt_sizes.*' => 'string|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',



            /*

             * PDF deletion flags

             */

            'delete_english_waiver' => 'nullable|boolean',

            'delete_burmese_waiver' => 'nullable|boolean',

            'delete_english_consent' => 'nullable|boolean',

            'delete_burmese_consent' => 'nullable|boolean',

            'delete_english_race_guide' => 'nullable|boolean',

            'delete_burmese_race_guide' => 'nullable|boolean',



            'categories' => 'required|array|min:1',



            'categories.*.id' => 'nullable|integer',

            'categories.*.name' => 'required|string|max:255',

            'categories.*.local_price' => 'required|numeric|min:0',

            'categories.*.foreign_price' => 'nullable|numeric|min:0',

            'categories.*.capacity' => 'nullable|integer|min:1',

            'categories.*.bib_prefix' => 'nullable|string|max:3',

            'categories.*.bib_start_number' => 'nullable|integer|min:1',



            'promo_code' => 'nullable|string|max:100',

            'promo_type' => 'nullable|in:fixed,percentage',

            'promo_value' => 'nullable|numeric|min:0',

            'promo_ticket_category_id' => 'nullable',

            'company_name' => 'nullable|string|max:255',

            'promo_quantity' => 'nullable|integer|min:1|max:500',

            'max_uses' => 'nullable|integer|min:1',



            'items' => 'nullable|array',

            'items.*.id' => 'nullable|integer',

            'items.*.title' => 'nullable|string|max:255',

            'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',



            /*

             * Event item deletion

             */

            'deleted_items' => 'nullable|array',

            'deleted_items.*' => 'integer',

        ]);



        DB::transaction(function () use ($request, $event) {



            /*

             * =========================================================

             * SLUG

             * =========================================================

             */



            $slug = Str::slug($request->title);

            $originalSlug = $slug;

            $count = 1;



            while (

                Event::where('slug', $slug)

                    ->where('id', '!=', $event->id)

                    ->exists()

            ) {

                $slug = $originalSlug . '-' . $count++;

            }



            /*

             * =========================================================

             * EVENT IMAGE

             * =========================================================

             */



            if ($request->hasFile('image')) {



                if ($event->image) {

                    Storage::disk('public')->delete($event->image);

                }



                $event->image = $request->file('image')

                    ->store('events', 'public');

            }



            /*

             * =========================================================

             * PDF DELETE / REPLACE

             * =========================================================

             */



            // English Waiver

            if ($request->boolean('delete_english_waiver')) {



                if ($event->english_waiver) {

                    Storage::disk('public')->delete(

                        $event->english_waiver

                    );

                }



                $event->english_waiver = null;

            }



            if ($request->hasFile('english_waiver')) {



                if ($event->english_waiver) {

                    Storage::disk('public')->delete(

                        $event->english_waiver

                    );

                }



                $event->english_waiver = $request

                    ->file('english_waiver')

                    ->store('waivers', 'public');

            }





            // Burmese Waiver

            if ($request->boolean('delete_burmese_waiver')) {



                if ($event->burmese_waiver) {

                    Storage::disk('public')->delete(

                        $event->burmese_waiver

                    );

                }



                $event->burmese_waiver = null;

            }



            if ($request->hasFile('burmese_waiver')) {



                if ($event->burmese_waiver) {

                    Storage::disk('public')->delete(

                        $event->burmese_waiver

                    );

                }



                $event->burmese_waiver = $request

                    ->file('burmese_waiver')

                    ->store('waivers', 'public');

            }





            // English Consent

            if ($request->boolean('delete_english_consent')) {



                if ($event->english_consent) {

                    Storage::disk('public')->delete(

                        $event->english_consent

                    );

                }



                $event->english_consent = null;

            }



            if ($request->hasFile('english_consent')) {



                if ($event->english_consent) {

                    Storage::disk('public')->delete(

                        $event->english_consent

                    );

                }



                $event->english_consent = $request

                    ->file('english_consent')

                    ->store('consents', 'public');

            }





            // Burmese Consent

            if ($request->boolean('delete_burmese_consent')) {



                if ($event->burmese_consent) {

                    Storage::disk('public')->delete(

                        $event->burmese_consent

                    );

                }



                $event->burmese_consent = null;

            }



            if ($request->hasFile('burmese_consent')) {



                if ($event->burmese_consent) {

                    Storage::disk('public')->delete(

                        $event->burmese_consent

                    );

                }



                $event->burmese_consent = $request

                    ->file('burmese_consent')

                    ->store('consents', 'public');

            }





            // English Race Guide

            if ($request->boolean('delete_english_race_guide')) {



                if ($event->english_race_guide) {

                    Storage::disk('public')->delete(

                        $event->english_race_guide

                    );

                }



                $event->english_race_guide = null;

            }



            if ($request->hasFile('english_race_guide')) {



                if ($event->english_race_guide) {

                    Storage::disk('public')->delete(

                        $event->english_race_guide

                    );

                }



                $event->english_race_guide = $request

                    ->file('english_race_guide')

                    ->store('race_guides', 'public');

            }





            // Burmese Race Guide

            if ($request->boolean('delete_burmese_race_guide')) {



                if ($event->burmese_race_guide) {

                    Storage::disk('public')->delete(

                        $event->burmese_race_guide

                    );

                }



                $event->burmese_race_guide = null;

            }



            if ($request->hasFile('burmese_race_guide')) {



                if ($event->burmese_race_guide) {

                    Storage::disk('public')->delete(

                        $event->burmese_race_guide

                    );

                }



                $event->burmese_race_guide = $request

                    ->file('burmese_race_guide')

                    ->store('race_guides', 'public');

            }



            /*

             * =========================================================

             * UPDATE EVENT

             * =========================================================

             */



            $event->update([

                'title' => $request->title,

                'slug' => $slug,

                'description' => $request->description,

                'location' => $request->location,



                // Google Maps coordinates

                'latitude' => $request->input('latitude'),

                'longitude' => $request->input('longitude'),



                'event_date' => $request->event_date,

                'status' => $request->status,



                'creator_name' => $request->creator_name,

                'creator_phone' => $request->creator_phone,

                'creator_email' => $request->creator_email,



                'overall_capacity' => $request->overall_capacity,



                'enabled_fields' => $request->input('enabled_fields', []),

                'tshirt_sizes' => $request->input('tshirt_sizes', []),



                'enable_bib_number' => $request->has('enable_bib_number'),



                'share_bib_prefix' =>

                    $request->input('share_bib_prefix', '1') === '1',



                'event_bib_prefix' => strtoupper(

                    substr(

                        $request->input('event_bib_prefix', ''),

                        0,

                        3

                    )

                ),



                'event_bib_start_number' => $request->input(

                    'event_bib_start_number',

                    1

                ),



                'image' => $event->image,



                'english_waiver' => $event->english_waiver,

                'burmese_waiver' => $event->burmese_waiver,



                'english_consent' => $event->english_consent,

                'burmese_consent' => $event->burmese_consent,



                'english_race_guide' => $event->english_race_guide,

                'burmese_race_guide' => $event->burmese_race_guide,

            ]);



            /*

             * =========================================================

             * TICKET CATEGORIES

             * =========================================================

             */



            $submittedCategoryIds = [];

            $categoryMap = [];



            foreach ($request->categories as $index => $categoryData) {



                if (!empty($categoryData['id'])) {



                    $category = TicketCategory::where(

                            'id',

                            $categoryData['id']

                        )

                        ->where('event_id', $event->id)

                        ->first();



                    if ($category) {



                        $category->update([

                            'name' => $categoryData['name'],

                            'local_price' => $categoryData['local_price'],

                            'foreign_price' =>

                                $categoryData['foreign_price'] ?? null,

                            'capacity' =>

                                $categoryData['capacity'] ?? null,



                            'bib_prefix' => strtoupper(

                                substr(

                                    $categoryData['bib_prefix'] ?? '',

                                    0,

                                    3

                                )

                            ),



                            'bib_start_number' =>

                                $categoryData['bib_start_number'] ?? 1,

                        ]);



                        $submittedCategoryIds[] = $category->id;



                        $categoryMap[$category->id] = $category;

                        $categoryMap[$index] = $category;

                    }



                } else {



                    $category = TicketCategory::create([

                        'event_id' => $event->id,

                        'name' => $categoryData['name'],

                        'local_price' => $categoryData['local_price'],

                        'foreign_price' =>

                            $categoryData['foreign_price'] ?? null,

                        'capacity' =>

                            $categoryData['capacity'] ?? null,

                        'tickets_sold' => 0,



                        'bib_prefix' => strtoupper(

                            substr(

                                $categoryData['bib_prefix'] ?? '',

                                0,

                                3

                            )

                        ),



                        'bib_start_number' =>

                            $categoryData['bib_start_number'] ?? 1,

                    ]);



                    $submittedCategoryIds[] = $category->id;



                    $categoryMap[$category->id] = $category;

                    $categoryMap[$index] = $category;

                }

            }



            $existingCategoryIds = $event->ticketCategories()

                ->pluck('id')

                ->toArray();



            $categoriesToDelete = array_diff(

                $existingCategoryIds,

                $submittedCategoryIds

            );



            foreach ($categoriesToDelete as $categoryId) {



                $category = TicketCategory::find($categoryId);



                if (

                    $category &&

                    $category->attendees()->count() === 0

                ) {

                    $category->delete();

                }

            }



            /*

             * =========================================================

             * EVENT ITEM DELETION

             * =========================================================

             */



            $deletedItemIds = $request->input(

                'deleted_items',

                []

            );



            if (!empty($deletedItemIds)) {



                $itemsToDelete = EventItem::where(

                        'event_id',

                        $event->id

                    )

                    ->whereIn('id', $deletedItemIds)

                    ->get();



                foreach ($itemsToDelete as $item) {



                    if ($item->image) {

                        Storage::disk('public')->delete(

                            $item->image

                        );

                    }



                    $item->delete();

                }

            }



            /*

             * =========================================================

             * EVENT ITEMS

             * =========================================================

             */



            if ($request->has('items')) {



                foreach ($request->items as $itemData) {



                    $title = $itemData['title'] ?? null;



                    /*

                     * Existing item

                     */

                    if (!empty($itemData['id'])) {



                        $item = EventItem::where(

                                'id',

                                $itemData['id']

                            )

                            ->where('event_id', $event->id)

                            ->first();



                        if (!$item) {

                            continue;

                        }



                        $item->title = $title;



                        if (

                            isset($itemData['image']) &&

                            $itemData['image'] instanceof \Illuminate\Http\UploadedFile

                        ) {



                            if ($item->image) {

                                Storage::disk('public')->delete(

                                    $item->image

                                );

                            }



                            $item->image = $itemData['image']

                                ->store('event-items', 'public');

                        }



                        $item->save();



                    } else {



                        /*

                         * New item

                         */

                        if (

                            empty($title) &&

                            empty($itemData['image'])

                        ) {

                            continue;

                        }



                        $itemImage = null;



                        if (

                            isset($itemData['image']) &&

                            $itemData['image'] instanceof \Illuminate\Http\UploadedFile

                        ) {



                            $itemImage = $itemData['image']

                                ->store('event-items', 'public');

                        }



                        EventItem::create([

                            'event_id' => $event->id,

                            'title' => $title,

                            'image' => $itemImage,

                        ]);

                    }

                }

            }

        });



        return redirect()

            ->route('admin.events.index')

            ->with('success', 'Event updated successfully!');

    }



    /**

     * Display Promo Codes management page for a specific event.

     */

    public function promoCodes(Event $event)

    {

        $event->load([

            'ticketCategories',

            'promoCodes'

        ]);



        return view(

            'admin.events.promo_codes',

            compact('event')

        );

    }



    /**

     * Store single or bulk promo codes for a specific event.

     */

    public function storePromoCode(Request $request, Event $event)

    {

        $request->validate([

            'company_name' => 'nullable|string|max:255',

            'code' => 'required|string|max:50',

            'discount_type' => 'required|in:fixed,percentage',

            'discount_value' => 'required|numeric|min:0',

            'promo_quantity' => 'required|integer|min:1|max:500',

            'max_uses' => 'required|integer|min:1',

            'ticket_category_id' => 'nullable|exists:ticket_categories,id',

        ]);



        $quantity = (int) $request->input(

            'promo_quantity',

            1

        );



        $baseCode = strtoupper(

            trim($request->input('code'))

        );



        if ($quantity > 1) {



            for ($i = 1; $i <= $quantity; $i++) {



                $formattedIndex = str_pad(

                    $i,

                    3,

                    '0',

                    STR_PAD_LEFT

                );



                $generatedCode =

                    "{$baseCode}-{$formattedIndex}";



                PromoCode::create([

                    'event_id' => $event->id,

                    'company_name' =>

                        $request->input('company_name'),

                    'code' => $generatedCode,



                    'discount_type' =>

                        $request->input('discount_type'),



                    'discount_value' =>

                        $request->input('discount_value'),



                    'max_uses' => 1,

                    'uses_count' => 0,



                    'ticket_category_id' =>

                        $request->input('promo_scope') === 'ticket'

                            ? $request->input('ticket_category_id')

                            : null,



                    'status' => 'active',

                ]);

            }



            return redirect()

                ->back()

                ->with(

                    'success',

                    "Generated {$quantity} promo codes starting with {$baseCode}-001!"

                );

        }



        PromoCode::create([

            'event_id' => $event->id,

            'company_name' =>

                $request->input('company_name'),



            'code' => $baseCode,



            'discount_type' =>

                $request->input('discount_type'),



            'discount_value' =>

                $request->input('discount_value'),



            'max_uses' =>

                $request->input('max_uses', 1),



            'uses_count' => 0,



            'ticket_category_id' =>

                $request->input('promo_scope') === 'ticket'

                    ? $request->input('ticket_category_id')

                    : null,



            'status' => 'active',

        ]);



        return redirect()

            ->back()

            ->with(

                'success',

                "Promo code {$baseCode} created successfully!"

            );

    }



    /**

     * Delete a promo code.

     */

    public function destroyPromoCode($id)

    {

        $promo = PromoCode::findOrFail($id);



        $promo->delete();



        return redirect()

            ->back()

            ->with(

                'success',

                'Promo code deleted successfully!'

            );

    }



    public function attendees(Event $event)

    {

        $eventId = $event->id;



        $event->load('ticketCategories');



        $attendees = Attendee::whereHas(

                'ticketCategory',

                function ($q) use ($eventId) {

                    $q->where('event_id', $eventId);

                }

            )

            ->whereHas(

                'order',

                function ($q) {

                    $q->whereRaw(

                        "LOWER(payment_status) IN ('paid', 'approved', 'completed')"

                    );

                }

            )

            ->with([

                'ticketCategory',

                'order',

                'promoCode'

            ])

            ->get();



        $totalRevenue = Order::where(

                function ($q) use ($eventId) {



                    $q->where(

                        'event_id',

                        $eventId

                    )

                    ->orWhereHas(

                        'attendees.ticketCategory',

                        function ($sub) use ($eventId) {

                            $sub->where(

                                'event_id',

                                $eventId

                            );

                        }

                    );

                }

            )

            ->whereRaw(

                "LOWER(payment_status) IN ('paid', 'approved', 'completed')"

            )

            ->sum('total_amount');



        return view(

            'admin.events.attendees',

            compact(

                'event',

                'attendees',

                'totalRevenue'

            )

        );

    }



    public function updateAttendee(Request $request, $id)

    {

        $attendee = Attendee::findOrFail($id);



        $validated = $request->validate([

            'full_name' => 'required|string|max:255',

            'father_name' => 'nullable|string|max:255',

            'email' => 'required|email|max:255',

            'phone' => 'required|string|max:50',

            'viber' => 'nullable|string|max:50',

            'emergency_contact' => 'nullable|string|max:50',

            'nationality' => 'required|string',

            'nrc_passport' => 'required|string|max:100',

            'country' => 'nullable|string|max:100',

            'gender' => 'nullable|string',

            'date_of_birth' => 'nullable|date',

            'bib_number' => 'nullable|string|max:50',

            'bib_name' => 'nullable|string|max:10',

            'tshirt_size' => 'required|string',

            'blood_type' => 'nullable|string',

            'has_medical_condition' => 'nullable|string',

            'medical_details' => 'nullable|string',

            'itra' => 'nullable|string',

            'itra_details' => 'nullable|string',

            'address' => 'nullable|string',

            'experience' => 'nullable|string',

        ]);



        $attendee->update($validated);



        return back()->with(

            'success',

            'Attendee updated successfully!'

        );

    }



    public function destroyAttendee($id)
    {
        DB::transaction(function () use ($id) {
            $attendee = Attendee::with('ticketCategory', 'order')->lockForUpdate()->findOrFail($id);
            $category = $attendee->ticketCategory;
            $order = $attendee->order;

            // Recalculate every category that could be affected if this order is deleted.
            $affectedCategoryIds = [];
            if ($category) {
                $affectedCategoryIds[] = $category->id;
            }

            if ($order) {
                $affectedCategoryIds = array_merge(
                    $affectedCategoryIds,
                    $order->attendees()->pluck('ticket_category_id')->all()
                );
                $affectedCategoryIds = array_values(array_unique(array_filter($affectedCategoryIds)));

                // Preserve the existing revenue adjustment behavior.
                $ticketPrice = (strtolower((string) $attendee->nationality) === 'foreigner')
                    ? ($category->foreign_price ?? $category->local_price ?? 0)
                    : ($category->local_price ?? 0);

                $newTotal = max(0, (float) $order->total_amount - (float) $ticketPrice);
                $hasOtherAttendees = $order->attendees()->where('id', '!=', $attendee->id)->exists();

                // Delete the attendee first so the order's attendee relation is accurate.
                $attendee->delete();

                if (!$hasOtherAttendees || $newTotal <= 0) {
                    $order->delete();
                } else {
                    $order->update(['total_amount' => $newTotal]);
                }
            } else {
                $attendee->delete();
            }

            // Match tickets_sold to the admin Attendees page's paid-equivalent statuses.
            // Pending/failed attendees do not count, and existing counter drift is corrected.
            foreach ($affectedCategoryIds as $categoryId) {
                $soldCount = Attendee::where('ticket_category_id', $categoryId)
                    ->whereHas('order', function ($query) {
                        $query->whereRaw("LOWER(payment_status) IN ('paid', 'approved', 'completed')");
                    })
                    ->count();

                TicketCategory::whereKey($categoryId)->update(['tickets_sold' => $soldCount]);
            }
        });

        return back()->with(
            'success',
            'Attendee deleted and revenue updated successfully!'
        );
    }

    

public function downloadAttendeeTicket($id)
{
    ini_set('memory_limit', '1024M');

    $attendee = Attendee::with([
        'ticketCategory.event',
    ])->findOrFail($id);

    if (empty($attendee->verification_token)) {
        $attendee->verification_token = Str::random(64);
        $attendee->save();
    }

    $category = $attendee->ticketCategory;
    $event = $category?->event;

    $sequentialTicketNumber = $attendee->ticket_code;

    /*
    |--------------------------------------------------------------------------
    | Load the saved category ticket design
    |--------------------------------------------------------------------------
    */
    $design = $category?->ticket_design ?? [];
    $design = is_array($design) ? $design : [];

    $elements = $design['elements'] ?? [];

    /*
    |--------------------------------------------------------------------------
    | Load the custom background, with ticket1.jpg as a fallback
    |--------------------------------------------------------------------------
    */
    $ticketBgBase64 = null;
    $customBackground = $design['background'] ?? null;

    if ($customBackground) {
        $customPath = Storage::disk('public')->path($customBackground);

        if (
            is_file($customPath) &&
            filesize($customPath) <= 10 * 1024 * 1024
        ) {
            $imageInfo = @getimagesize($customPath);

            if ($imageInfo !== false) {
                $mime = $imageInfo['mime'] ?? '';

                if (in_array($mime, ['image/jpeg', 'image/png'], true)) {
                    $ticketBgBase64 = 'data:' . $mime . ';base64,' .
                        base64_encode(file_get_contents($customPath));
                }
            }
        }
    }

    // Use the old background only when no valid custom background is available.
    if (!$ticketBgBase64) {
        $ticketBgPath = public_path('assets/img/ticket/ticket1.jpg');

        if (
            is_file($ticketBgPath) &&
            filesize($ticketBgPath) <= 10 * 1024 * 1024
        ) {
            $imageInfo = @getimagesize($ticketBgPath);

            if ($imageInfo !== false) {
                $mime = $imageInfo['mime'] ?? 'image/jpeg';

                $ticketBgBase64 = 'data:' . $mime . ';base64,' .
                    base64_encode(file_get_contents($ticketBgPath));
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate the verification QR code locally
    |--------------------------------------------------------------------------
    */
    $verificationUrl = route('ticket.verify', [
        'token' => $attendee->verification_token,
    ]);

    $qrBase64 = null;

    if (function_exists('imagecreatetruecolor')) {
        $qrSize = 300;
        $qrImage = imagecreatetruecolor($qrSize, $qrSize);

        if ($qrImage !== false) {
            $white = imagecolorallocate($qrImage, 255, 255, 255);
            $black = imagecolorallocate($qrImage, 0, 0, 0);

            imagefill($qrImage, 0, 0, $white);

            // A QR code requires a QR encoder; GD alone cannot encode one.
            imagedestroy($qrImage);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Create QR code using the configured external QR service
    |--------------------------------------------------------------------------
    | The API response is converted to a data URI so Dompdf doesn't need
    | to load a remote image while rendering the PDF.
    */
    $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&format=png&data=' .
        urlencode($verificationUrl);

    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'follow_location' => 1,
            'user_agent' => 'EventPlusTicketPDF/1.0',
        ],
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
        ],
    ]);

    $qrImageData = @file_get_contents($qrApiUrl, false, $context);

    if ($qrImageData !== false && str_starts_with($qrImageData, "\x89PNG\r\n\x1a\n")) {
        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrImageData);
    } else {
        \Log::warning('Ticket QR code generation failed', [
            'attendee_id' => $attendee->id,
            'verification_url' => $verificationUrl,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Render the PDF
    |--------------------------------------------------------------------------
    */
    $pdf = Pdf::setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => false,
        'defaultFont' => 'sans-serif',
        'isFontSubsettingEnabled' => true,
    ])->loadView('emails.ticket_pdf', [
        'attendee' => $attendee,
        'event' => $event,
        'ticketCategory' => $category,
        'ticketBgBase64' => $ticketBgBase64,
        'qrBase64' => $qrBase64,
        'formattedTicketRef' => $sequentialTicketNumber,
        'ticketDesign' => $design,
        'ticketElements' => $elements,
    ])->setPaper('a4', 'landscape');

    $safeTicketCode = preg_replace(
        '/[^A-Za-z0-9_-]/',
        '_',
        (string) ($sequentialTicketNumber ?: $attendee->id)
    );

    return $pdf->download('Ticket_' . $safeTicketCode . '.pdf');
}



    private function resizeImageToBase64(

        $filePath,

        $maxWidth,

        $maxHeight

    ) {

        if (!file_exists($filePath)) {

            return null;

        }



        $imageInfo = @getImageSize($filePath);



        if (!$imageInfo) {

            return null;

        }



        list(

            $origWidth,

            $origHeight,

            $imageType

        ) = $imageInfo;



        switch ($imageType) {



            case IMAGETYPE_JPEG:

                $sourceImage =

                    @imagecreatefromjpeg($filePath);

                break;



            case IMAGETYPE_PNG:

                $sourceImage =

                    @imagecreatefrompng($filePath);

                break;



            case IMAGETYPE_GIF:

                $sourceImage =

                    @imagecreatefromgif($filePath);

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



        $newWidth = max(

            1,

            round($origWidth * $ratio)

        );



        $newHeight = max(

            1,

            round($origHeight * $ratio)

        );



        $virtualImage = imagecreatetruecolor(

            $newWidth,

            $newHeight

        );



        if (

            $imageType == IMAGETYPE_PNG ||

            $imageType == IMAGETYPE_GIF

        ) {



            imagecolortransparent(

                $virtualImage,

                imagecolorallocatealpha(

                    $virtualImage,

                    0,

                    0,

                    0,

                    127

                )

            );



            imagealphablending(

                $virtualImage,

                false

            );



            imagesavealpha(

                $virtualImage,

                true

            );

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



        if ($imageType == IMAGETYPE_JPEG) {



            imagejpeg(

                $virtualImage,

                null,

                75

            );



            $mime = 'image/jpeg';



        } else {



            imagepng(

                $virtualImage,

                null,

                6

            );



            $mime = 'image/png';

        }



        $imageData = ob_get_clean();



        imagedestroy($sourceImage);

        imagedestroy($virtualImage);



        return 'data:' .

            $mime .

            ';base64,' .

            base64_encode($imageData);

    }



    public function destroy(Event $event)

    {

        $event->load([

            'ticketCategories',

            'promoCodes',

            'items'

        ]);



        $categoryIds = $event->ticketCategories()

            ->pluck('id');



        Attendee::whereIn(

            'ticket_category_id',

            $categoryIds

        )->delete();



        Order::where(

            'event_id',

            $event->id

        )->delete();



        $event->ticketCategories()->delete();



        $event->promoCodes()->delete();



        foreach ($event->items as $item) {



            if ($item->image) {

                Storage::disk('public')->delete(

                    $item->image

                );

            }



            $item->delete();

        }



        if ($event->image) {

            Storage::disk('public')->delete(

                $event->image

            );

        }



        if ($event->english_waiver) {

            Storage::disk('public')->delete(

                $event->english_waiver

            );

        }



        if ($event->burmese_waiver) {

            Storage::disk('public')->delete(

                $event->burmese_waiver

            );

        }



        if ($event->english_consent) {

            Storage::disk('public')->delete(

                $event->english_consent

            );

        }



        if ($event->burmese_consent) {

            Storage::disk('public')->delete(

                $event->burmese_consent

            );

        }



        if ($event->english_race_guide) {

            Storage::disk('public')->delete(

                $event->english_race_guide

            );

        }



        if ($event->burmese_race_guide) {

            Storage::disk('public')->delete(

                $event->burmese_race_guide

            );

        }



        $event->delete();



        return redirect()

            ->route('admin.events.index')

            ->with(

                'success',

                'Event deleted successfully!'

            );

    }



    public function exportAttendees(Event $event)

    {

        $fileName =

            Str::slug($event->title) .

            '-attendees-' .

            date('Y-m-d') .

            '.xlsx';



        return Excel::download(

            new EventAttendeesExport($event),

            $fileName

        );

    }

 
public function ticketDesign(\App\Models\Event $event)
{
    $categories = $event->ticketCategories()
        ->orderBy('id')
        ->get();

    $selectedCategory = $categories->first();

    return view('admin.events.ticket-design', [
        'event' => $event,
        'categories' => $categories,
        'selectedCategory' => $selectedCategory,
    ]);
}

public function saveTicketDesign(
    \Illuminate\Http\Request $request,
    \App\Models\Event $event
) {
    // Decode the ticket design JSON sent by the Blade form.
    $decodedDesign = json_decode(
        $request->input('ticket_design', ''),
        true
    );

    if (!is_array($decodedDesign)) {
        return back()
            ->withErrors([
                'ticket_design' => 'Invalid ticket design data. Please try again.',
            ])
            ->withInput();
    }

    $request->merge([
        'ticket_design' => $decodedDesign,
    ]);

    // Validate the design and optional background image.
    $validated = $request->validate([
        'ticket_category_id' => [
            'required',
            'integer',
        ],

        'ticket_design' => [
            'required',
            'array',
        ],

        'ticket_design.elements' => [
            'required',
            'array',
        ],

        'ticket_design.elements.*.type' => [
            'required',
            'in:qr,attendee_name,phone,ticket_number,bib_number,event_name',
        ],

        'ticket_design.elements.*.x' => [
            'required',
            'numeric',
            'between:0,1600',
        ],

        'ticket_design.elements.*.y' => [
            'required',
            'numeric',
            'between:0,517',
        ],

        'ticket_design.elements.*.width' => [
            'required',
            'numeric',
            'between:1,1600',
        ],

        'ticket_design.elements.*.height' => [
            'required',
            'numeric',
            'between:1,517',
        ],

        'ticket_design.elements.*.fontSize' => [
            'nullable',
            'numeric',
            'between:6,120',
        ],

        'ticket_design.elements.*.color' => [
            'nullable',
            'regex:/^#[0-9A-Fa-f]{6}$/',
        ],

        'ticket_design.elements.*.rotation' => [
            'nullable',
            'numeric',
            'between:-360,360',
        ],

        'background_image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png',
            'max:5120', // Maximum 5 MB
        ],

        'remove_background' => [
            'nullable',
            'boolean',
        ],
    ]);

    // Ensure this category belongs to the selected event.
    $category = $event->ticketCategories()
        ->where('id', $validated['ticket_category_id'])
        ->firstOrFail();

    $design = $validated['ticket_design'];

    // Get the previously saved background image path.
    $existingDesign = $category->ticket_design ?? [];
    $oldBackground = $existingDesign['background'] ?? null;

    if ($request->hasFile('background_image')) {
        // Store the new JPG/PNG in storage/app/public/ticket-backgrounds.
        $newBackground = $request->file('background_image')
            ->store('ticket-backgrounds', 'public');

        $design['background'] = $newBackground;

        // Delete the previous image after the new upload succeeds.
        if ($oldBackground) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($oldBackground);
        }
    } elseif ($request->boolean('remove_background')) {
        // Remove the existing background when requested.
        $design['background'] = null;

        if ($oldBackground) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($oldBackground);
        }
    } else {
        // Keep the existing background if no change was requested.
        $design['background'] = $oldBackground;
    }

    // Save the design and background path for this category.
    $category->ticket_design = $design;
    $category->save();

    return redirect()
        ->route('admin.events.ticket-design', $event)
        ->with('success', 'Ticket design saved successfully.');
}

}