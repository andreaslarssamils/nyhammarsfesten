<?php

namespace Tests\Feature;

use Tests\TestCase;

class ForkopTest extends TestCase
{
    public function test_forkopslanken_renderas_pa_alla_tre_stallena(): void
    {
        config(['festival.tickets.forkop_url' => 'https://exempel.test/biljetter']);

        $response = $this->get('/');

        $response->assertOk();

        // Hela taggen, inte bara href: rel och target är det som gör länken extern,
        // och klassen är det som skiljer de tre placeringarna åt.
        foreach (['nav-cta-mobile', 'nav-cta', 'hero-cta', 'biljett-cta'] as $klass) {
            $response->assertSee(
                '<a class="'.$klass.'" href="https://exempel.test/biljetter" rel="noopener" target="_blank">',
                false
            );
        }
    }

    /**
     * '#' är platshållaren i config. Då ska adressen försvinna helt, medan nav och
     * hero faller tillbaka på ankaret — biljettsektionen ritar inget alls.
     */
    public function test_platshallaren_doljer_knappen_och_ger_ankaret_i_stallet(): void
    {
        config(['festival.tickets.forkop_url' => '#']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('rel="noopener" target="_blank">26 sept', false);

        // Hela taggen: '#biljetter' finns redan i nav via den permanenta
        // Biljetter-länken, så ett test på bara adressen skulle förbli grönt
        // även om fallback-grenen togs bort.
        $response->assertSee('<a class="nav-cta" href="#biljetter">26 sept ✶ Förköp</a>', false);
        $response->assertSee('<a class="hero-cta" href="#biljetter">Förköp biljett ✶</a>', false);
        $response->assertDontSee('biljett-cta', false);
    }
}
