<?php

namespace Tests\Feature;

use Tests\TestCase;

class TackTest extends TestCase
{
    public function test_tacksidan_tackar_artister_och_sponsorer(): void
    {
        config(['festival.avslutad' => true]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(config('festival.tack.artister_title'));
        $response->assertSee(config('festival.tack.medverkande_title'));
        $response->assertSee(config('festival.tack.sponsorer_title'));
        $response->assertSee(config('festival.tack.nasta_ar_title'));

        foreach (config('festival.lineup') as $band) {
            $response->assertSee($band['name']);
        }

        foreach (config('festival.sponsors.items') as $sponsor) {
            $response->assertSee($sponsor['name']);
        }
    }

    /**
     * Festivalen är över: inget som uppmanar till köp eller räknar ner, och inga
     * ankare till sektioner som inte finns på sidan.
     */
    public function test_tacksidan_saknar_forkop_nedrakning_och_bokning(): void
    {
        config([
            'festival.avslutad'           => true,
            'festival.tickets.forkop_url' => 'https://exempel.test/biljetter',
        ]);

        $response = $this->get('/');

        $response->assertDontSee('id="countdown"', false);
        $response->assertDontSee('https://exempel.test/biljetter', false);
        $response->assertDontSee('Förköp');
        $response->assertDontSee('Bli sponsor');
        $response->assertDontSee('Din logga här');
        $response->assertDontSee('id="tshirt"', false);

        foreach (['#program', '#biljetter', '#tshirt', '#hitta', '#faq'] as $ankare) {
            $response->assertDontSee('href="'.$ankare.'"', false);
        }
    }

    public function test_vaxeln_av_ger_festivalsidan_tillbaka(): void
    {
        config(['festival.avslutad' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('id="countdown"', false)
            ->assertSee('Vi ses där ✶')
            ->assertDontSee(config('festival.tack.nasta_ar_title'));
    }
}
