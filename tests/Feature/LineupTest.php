<?php

namespace Tests\Feature;

use Tests\TestCase;

class LineupTest extends TestCase
{
    public function test_varje_band_med_bild_pekar_pa_en_fil_som_finns(): void
    {
        // Samlas i en lista i stället för en assertion per band: annars gör testet
        // noll assertions så länge alla rader saknar bild, och räknas som riskabelt.
        $saknade = collect(config('festival.lineup'))
            ->filter(fn ($band) => filled($band['bild'] ?? null))
            ->reject(fn ($band) => file_exists(public_path($band['bild'])))
            ->map(fn ($band) => "{$band['name']} → {$band['bild']}")
            ->values()
            ->all();

        $this->assertSame([], $saknade, 'Lineup-bilder som inte finns under public/');
    }

    /**
     * Bilden är valfri: band utan bild ska renderas precis som förut, och en rad
     * som saknar nyckeln helt får inte krascha vyn.
     */
    public function test_lineupsektionen_renderar_bild_bara_for_band_som_har_en(): void
    {
        config(['festival.lineup' => [
            ['name' => 'Med bild',    'meta' => 'Lör 21:30', 'size' => 1, 'color' => 'gold', 'bild' => 'assets/svart-nf.webp'],
            ['name' => 'Utan bild',   'meta' => 'Lör 18:00', 'size' => 2, 'color' => null,   'bild' => null],
            ['name' => 'Utan nyckel', 'meta' => 'Lör 15:00', 'size' => 3, 'color' => null],
        ]]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Utan nyckel');

        // Hela taggen, inte bara src: samma bildfil används av färgproven i
        // tröjformuläret längre ner på sidan.
        $response->assertSee(
            '<img class="act-bild size-1" src="'.asset('assets/svart-nf.webp').'"',
            false
        );

        $this->assertSame(
            1,
            substr_count($response->getContent(), 'act-bild'),
            'Bara bandet med bild ska få en <img> i lineupen'
        );
    }
}
