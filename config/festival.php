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
        'phone'     => '070-213 13 72',
        'sponsor'   => 'info@nyhammarsfesten.se',
        'facebook'  => '#',
        'instagram' => '#',
    ],

    'ticker' => '26 september 2026 ✶ Nyhammar, Dalarna ✶ Vuxna 100 kr ✶ Ungdomar gratis ✶ Musik hela dagen ✶ Fika & food truck ✶ Hela familjen ✶',

    /*
     |--------------------------------------------------------------------------
     | Lineup — driver artistsektionen
     |--------------------------------------------------------------------------
     | size: 1–4 (1 = störst, huvudakt).  color: null, 'gold' eller 'olive'.
     |
     | bild: valfri sökväg under public/, t.ex. 'assets/bandnamn.webp'. null — eller
     | ingen nyckel alls — ger raden utan bild, precis som förut. Bilden visas som
     | kvadratisk miniatyr och skalas med size, så beskär motivet kvadratiskt.
     | Filnamn i gemener utan å/ä/ö: driften är Linux och skiftlägeskänslig.
     | Optimera med: cwebp -q 80 -resize 600 0 in.jpg -o public/assets/bandnamn.webp
     */
    'lineup' => [
        ['name' => 'Konstcyklist Erik Ivarsson', 'meta' => 'Grangärde',    'size' => 1, 'color' => 'gold',  'bild' => null],
        ['name' => 'Trashcan Band',              'meta' => 'Borlänge/Falun',    'size' => 2, 'color' => 'olive', 'bild' => null],
        ['name' => 'Walls Of Glass',             'meta' => 'Nyhammar', 'size' => 2, 'color' => null,    'bild' => null],
        ['name' => 'FINALLY',                    'meta' => 'Sala', 'size' => 3, 'color' => 'gold',  'bild' => null],
        ['name' => 'Ritzy Rock',                    'meta' => '', 'size' => 3, 'color' => null,  'bild' => null],
        ['name' => 'Dödens Gudbarn',              'meta' => 'Ludvika', 'size' => 3, 'color' => 'gold',  'bild' => null],
        ['name' => 'Mattias Bredenberg Hellre Bipolär Än Populär',
            'meta' => '', 'size' => 4, 'color' => 'olive', 'bild' => null],
        ['name' => 'Sped Up',                        'meta' => 'Borlänge', 'size' => 4, 'color' => null,    'bild' => null],
        ['name' => 'Khar',                        'meta' => 'Sala', 'size' => 4, 'color' => 'olive',  'bild' => null],
        ['name' => 'PJ Myers',                        'meta' => 'Australien/Ludvika', 'size' => 2, 'color' => 'gold',  'bild' => null],
        ['name' => 'Finally',                        'meta' => 'Sala', 'size' => 1, 'color' => null,  'bild' => null],
    ],

    /*
     |--------------------------------------------------------------------------
     | Program — stage_class: null, 'alt' (lilla scenen) eller 'hot' (huvudakt)
     |--------------------------------------------------------------------------
     */
    'program_lead' => 'Lördag 26 september — från lunch till midnatt.',

    'program' => [
        ['time' => '12:00', 'act' => 'Portarna öppnas', 'stage' => 'Området',      'stage_class' => null],
        ['time' => '13:00', 'act' => 'Mattias Bredenberg, Hellre bipolär än popluär', 'stage' => 'Lilla scenen', 'stage_class' => 'alt'],
        ['time' => '16:00', 'act' => 'Konstcyklist Erik Ivarsson', 'stage' => 'Stora scenen', 'stage_class' => 'hot'],
        ['time' => '17:00', 'act' => 'PJ Myers', 'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '18:00', 'act' => 'SpedUp', 'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '18:00', 'act' => 'Ritzy Rock', 'stage' => 'Stora scenen', 'stage_class' => 'alt'],
        ['time' => '20:00', 'act' => 'Walls Of Glass', 'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '21:15', 'act' => 'Finally', 'stage' => 'Stora scenen', 'stage_class' => 'hot'],
        ['time' => '22:30', 'act' => 'Trashcan Band', 'stage' => 'Stora scenen', 'stage_class' => 'hot'],
        ['time' => '23:45', 'act' => 'Dödens Gudbarn', 'stage' => 'Stora scenen', 'stage_class' => null],
        ['time' => '01:00', 'act' => 'Khar', 'stage' => 'Stora scenen', 'stage_class' => 'alt'],
    ],

    'tickets' => [
        'items'      => [
            ['label' => 'Vuxen',             'price' => '100 kr', 'note' => 'Hela dagen, alla scener.', 'variant' => 'dark'],
            ['label' => 'Ungdom — under 18', 'price' => 'Gratis', 'note' => 'Ta med hela klassen!',     'variant' => 'light'],
        ],
        'info'       => 'Förköp din biljett på Billetto — eller betala i entrén med Swish eller kontanter. Portarna öppnar 12:00.',

        // Förköp hos Billetto. '#' eller null döljer knappen i nav, hero och biljettsektionen
        // (samma platshållarkonvention som contact.facebook) — nav och hero faller då tillbaka
        // på ankaret #biljetter. Obs: 'info' ovan och FAQ-svaret nedan nämner Billetto i
        // löptext, så de måste redigeras för hand om förköpet upphör.
        'forkop_url' => 'https://billetto.se/e/nyhammarsfesten-musik-i-obygden-biljetter-1983011',
    ],

    'travel' => [
        ['title' => 'Med bil',   'text' => 'Cirka 15-20 minuter från Ludvika.'],
        ['title' => 'Med buss',  'text' => 'Dalatrafik kör mot Nyhammar — kliv av vid hållplatsen på torget i Nyhammar, sedan 3 minuters promenad.'],
        ['title' => 'Parkering', 'text' => 'Gratis parkering och ställplatser finns.'],
    ],

    'faq' => [
        ['q' => 'Vad kostar det?',                     'a' => '100 kr för vuxna, gratis för alla under 18. Förköp på Billetto eller betala i entrén med Swish eller kontanter.'],
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
