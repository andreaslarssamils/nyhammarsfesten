<?php

/*
 |--------------------------------------------------------------------------
 | Svenska valideringsmeddelanden
 |--------------------------------------------------------------------------
 | Laravel levererar ingen svensk översättning, så bara de regler som faktiskt
 | används i StoreShirtOrderRequest ligger här. Allt annat faller tillbaka på
 | ramverkets engelska via APP_FALLBACK_LOCALE — läsbart, till skillnad från
 | den råa nyckeln man får om fallbacken också pekar på sv.
 */

return [

    'email'      => ':attribute måste vara en giltig e-postadress.',
    'in'         => 'Valt värde för :attribute finns inte.',
    'integer'    => ':attribute måste vara ett heltal.',
    'prohibited' => ':attribute får inte fyllas i.',
    'required'   => ':attribute måste fyllas i.',
    'string'     => ':attribute måste vara text.',

    'max' => [
        'array'   => ':attribute får inte innehålla fler än :max saker.',
        'file'    => ':attribute får inte vara större än :max kilobyte.',
        'numeric' => ':attribute får högst vara :max.',
        'string'  => ':attribute får inte vara längre än :max tecken.',
    ],

    'min' => [
        'array'   => ':attribute måste innehålla minst :min saker.',
        'file'    => ':attribute måste vara minst :min kilobyte.',
        'numeric' => ':attribute måste vara minst :min.',
        'string'  => ':attribute måste vara minst :min tecken.',
    ],

    'custom' => [],

    'attributes' => [
        'name'     => 'Namn',
        'email'    => 'E-post',
        'phone'    => 'Telefon',
        'model'    => 'Modell',
        'color'    => 'Färg',
        'size'     => 'Storlek',
        'quantity' => 'Antal',
        'note'     => 'Meddelande',
        'website'  => 'Webbplats',
    ],

    'values' => [],

];
