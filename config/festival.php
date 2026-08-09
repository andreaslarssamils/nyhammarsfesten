<?php

return [

    'name'    => 'Nyhammarsfesten',
    'tagline' => 'Musik i Obygden',
    'lead'    => 'Lördag 26 september 2026. Musik hela dagen, fika + gott sällskap!',
    'badge'   => 'En dag ✶ Hela byn ✶ All musik',

    // ISO 8601 med tidszon — driver nedräkningen
    'date'       => '2026-09-26T12:00:00+02:00',
    'date_label' => ['dag' => 'Lördag', 'datum' => '26', 'manad' => 'September'],

    'venue' => [
        'name'    => 'Folkets Hus',
        'address' => 'Folkets Hus väg 12, 770 14 Nyhammar',
    ],

    'contact' => [
        'email'     => 'info@nyhammarsfesten.se',
        'phone'     => '070-123 45 67',
        'phone_tel' => '+46701234567',
        'sponsor'   => 'brippen@hotmail.com',
        'facebook'  => '#',
        'instagram' => '#',
    ],

    'ticker' => '26 september 2026 ✶ Nyhammar, Dalarna ✶ Vuxna 100 kr ✶ Ungdomar gratis ✶ Musik hela dagen ✶ Fika & food truck ✶ Hela familjen ✶',

    /*
     |--------------------------------------------------------------------------
     | Lineup — driver artistsektionen
     |--------------------------------------------------------------------------
     | size: 1–4 (1 = störst, huvudakt).  color: null, 'gold' eller 'olive'.
     */
    'lineup' => [
        ['name' => 'TBA', 'meta' => 'TBA', 'size' => 1, 'color' => 'gold'],
        ['name' => 'TBA', 'meta' => 'TBA', 'size' => 2, 'color' => 'olive'],
        ['name' => 'TBA', 'meta' => 'TBA', 'size' => 2, 'color' => null],
        ['name' => 'TBA', 'meta' => 'TBA', 'size' => 3, 'color' => 'gold'],
        ['name' => 'TBA', 'meta' => 'TBA', 'size' => 3, 'color' => 'olive'],
        ['name' => 'TBA', 'meta' => 'TBA', 'size' => 4, 'color' => null],
        ['name' => 'TBA', 'meta' => 'TBA', 'size' => 4, 'color' => 'gold'],
    ],

    /*
     |--------------------------------------------------------------------------
     | Program — stage_class: null, 'alt' (lilla scenen) eller 'hot' (huvudakt)
     |--------------------------------------------------------------------------
     */
    'program_lead' => 'Lördag 26 september — från lunch till midnatt.',

    'program' => [
        ['time' => '12:00', 'act' => 'Portarna öppnas',             'stage' => 'Området',      'stage_class' => null],
        ['time' => '12:30', 'act' => 'TBA',                         'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '13:30', 'act' => 'TBA — familjeshow',           'stage' => 'Lilla scenen', 'stage_class' => 'alt'],
        ['time' => '15:00', 'act' => 'TBA',                         'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '16:30', 'act' => 'TBA',                         'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '18:00', 'act' => 'TBA',                         'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '20:00', 'act' => 'TBA',                         'stage' => 'Lilla scenen', 'stage_class' => 'alt'],
        ['time' => '21:30', 'act' => 'TBA ✶ huvudakt',              'stage' => 'Stora scenen', 'stage_class' => 'hot'],
        ['time' => '23:00', 'act' => 'Tack och godnatt, Nyhammar!', 'stage' => null,           'stage_class' => null],
    ],

    'tickets' => [
        'items' => [
            ['label' => 'Vuxen',             'price' => '100 kr', 'note' => 'Hela dagen, alla scener.', 'variant' => 'dark'],
            ['label' => 'Ungdom — under 18', 'price' => 'Gratis', 'note' => 'Ta med hela klassen!',     'variant' => 'light'],
        ],
        'info' => 'Inga förbokningar behövs — betala i entrén med Swish eller kontanter. Portarna öppnar 12:00.',
    ],

    'travel' => [
        ['title' => 'Med bil',   'text' => 'Cirka 15-20 minuter från Ludvika.'],
        ['title' => 'Med buss',  'text' => 'Dalatrafik kör mot Nyhammar — kliv av vid hållplatsen på torget i Nyhammar, sedan 3 minuters promenad.'],
        ['title' => 'Parkering', 'text' => 'Gratis parkering och ställplatser finns.'],
    ],

    'faq' => [
        ['q' => 'Vad kostar det?',                     'a' => '100 kr för vuxna, gratis för alla under 18. Betala i entrén med Swish eller kontanter — inga förbokningar behövs.'],
        ['q' => 'Finns det mat och dryck?',            'a' => 'Ja! Food truck.'],
        ['q' => 'Vad händer om det regnar?',           'a' => 'Festivalen körs oavsett väder för det är inomhus, så lite regn är inget problem.'],
        ['q' => 'Får jag ta med hunden?',              'a' => 'Kopplade hundar är välkomna på området, men tänk på att det kan bli högt ljud nära scenerna.'],
        ['q' => 'Är området tillgänglighetsanpassat?', 'a' => 'Ja — tillgänglighetsanpassade toaletter och reserverad plats nära scenen. Hör av dig i förväg om du har frågor.'],
        ['q' => 'Finns det åldersgräns?',              'a' => 'Nej, festivalen är för hela familjen.'],
    ],

    'sponsors' => [
        'lead' => 'Festivalen görs möjlig av bygdens hjältar. Vill ditt företag synas här?',
        // ['name' => 'Företaget AB', 'logo' => 'sponsors/foretaget.svg', 'url' => 'https://...']
        // Tomma platser fylls ut automatiskt upp till fyra.
        'items' => [],
    ],

    /*
     |--------------------------------------------------------------------------
     | T-shirt
     |--------------------------------------------------------------------------
     */
    'shirt' => [
        'price'        => 200,
        'deadline'     => '2026-09-06',
        'payment'      => 'prepay',       // 'prepay' eller 'on_pickup'
        'swish_number' => '123 456 78 90',

        'models' => [
            'unisex' => 'Unisex',
            'dam'    => 'Dam',
            'barn'   => 'Barn',
        ],

        // Tröjan trycks bara i två färger. Bilderna ligger i public/assets/ och
        // visas som färgprover i bokningsformuläret — originalen (flera MB) ligger
        // i storage/app/private/tshirt-original/ och serveras inte.
        'colors' => [
            'svart' => ['label' => 'Svart', 'bild' => 'assets/svart-nf.webp'],
            'vit'   => ['label' => 'Vit',   'bild' => 'assets/vit-nf.webp'],
        ],

        'sizes' => [
            'vuxen' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'barn'  => ['110', '122', '134', '146', '158'],
        ],
    ],

    'admin_key' => env('FESTIVAL_ADMIN_KEY'),

];
