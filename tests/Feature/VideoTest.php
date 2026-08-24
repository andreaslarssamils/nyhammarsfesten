<?php

namespace Tests\Feature;

use Tests\TestCase;

class VideoTest extends TestCase
{
    public function test_videons_filer_pekar_pa_filer_som_finns(): void
    {
        // Samlas i en lista i stället för en assertion per fil: annars gör testet
        // noll assertions så länge sektionen är avstängd, och räknas som riskabelt.
        $saknade = collect(['fil', 'poster'])
            ->mapWithKeys(fn ($nyckel) => [$nyckel => config("festival.video.{$nyckel}")])
            ->filter(fn ($sokvag) => filled($sokvag))
            ->reject(fn ($sokvag) => file_exists(public_path($sokvag)))
            ->map(fn ($sokvag, $nyckel) => "{$nyckel} → {$sokvag}")
            ->values()
            ->all();

        $this->assertSame([], $saknade, 'Videofiler som inte finns under public/');
    }

    public function test_sektionen_visas_nar_en_fil_ar_angiven(): void
    {
        config(['festival.video' => [
            'fil'    => 'assets/erik-ivarsson.mp4',
            'poster' => null,
            'tag'    => 'Huvudakten',
            'title'  => 'Konstcyklist Erik Ivarsson',
            'text'   => 'Femton sekunder.',
        ]]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('video-sektion', false);
        $response->assertSee('Huvudakten');
        $response->assertSee('Konstcyklist Erik Ivarsson');

        // Utan posterbild måste tidsfragmentet hänga på, annars står spelaren svart:
        // preload="metadata" hämtar rubriken men ingen bildruta.
        $response->assertSee('<source src="'.asset('assets/erik-ivarsson.mp4').'#t=0.1"', false);
        $this->assertStringNotContainsString('poster=', $response->getContent());
    }

    /**
     * Posterbilden ersätter tidsfragmentet — båda behövs inte, och #t=0.1 hade
     * annars laddat en bildruta i onödan ovanpå postern.
     */
    public function test_posterbilden_ersatter_tidsfragmentet(): void
    {
        config(['festival.video' => [
            'fil'    => 'assets/erik-ivarsson.mp4',
            'poster' => 'assets/svart-nf.webp',
        ]]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('poster="'.asset('assets/svart-nf.webp').'"', false);
        $response->assertSee('<source src="'.asset('assets/erik-ivarsson.mp4').'"', false);
        $this->assertStringNotContainsString('#t=0.1', $response->getContent());
    }

    /**
     * Tom fil döljer hela sektionen, och en config utan nyckeln alls får inte
     * krascha vyn — samma platshållarkonvention som tickets.forkop_url.
     */
    public function test_sektionen_forsvinner_utan_fil(): void
    {
        config(['festival.video' => ['fil' => null]]);
        $this->get('/')->assertOk()->assertDontSee('video-sektion', false);

        config(['festival.video' => null]);
        $this->get('/')->assertOk()->assertDontSee('video-sektion', false);
    }
}
