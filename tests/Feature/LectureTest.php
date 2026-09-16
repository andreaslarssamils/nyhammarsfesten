<?php

namespace Tests\Feature;

use Tests\TestCase;

class LectureTest extends TestCase
{
    public function test_sektionen_visas_nar_text_ar_angiven(): void
    {
        config(['festival.lecture' => [
            'tag'   => 'Mattias Bredenberg',
            'title' => 'Hellre bipolär än populär',
            'text'  => 'En föreläsning om psykisk ohälsa, framförd i musikform.',
        ]]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('forelasning', false);
        $response->assertSee('Mattias Bredenberg');
        $response->assertSee('Hellre bipolär än populär');
        $response->assertSee('En föreläsning om psykisk ohälsa, framförd i musikform.');
    }

    /**
     * Tom text döljer hela sektionen, och en config utan nyckeln alls får inte
     * krascha vyn — samma platshållarkonvention som video.fil.
     */
    public function test_sektionen_forsvinner_utan_text(): void
    {
        config(['festival.lecture' => ['text' => null]]);
        $this->get('/')->assertOk()->assertDontSee('forelasning', false);

        config(['festival.lecture' => null]);
        $this->get('/')->assertOk()->assertDontSee('forelasning', false);
    }
}
