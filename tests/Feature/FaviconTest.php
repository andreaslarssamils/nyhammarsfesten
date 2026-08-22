<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaviconTest extends TestCase
{
    public function test_alla_ikoner_som_layouten_lankar_till_finns_under_public(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        preg_match_all('/<link rel="(?:icon|apple-touch-icon)"[^>]*href="([^"]+)"/', $html, $traffar);

        $lankar = $traffar[1];

        $this->assertCount(3, $lankar, 'Layouten ska länka .ico, .svg och apple-touch-icon');

        $saknade = collect($lankar)
            ->map(fn ($url) => ltrim((string) parse_url($url, PHP_URL_PATH), '/'))
            ->reject(fn ($fil) => $this->finnsMedExaktSkiftlage($fil))
            ->values()
            ->all();

        $this->assertSame([], $saknade, 'Ikonfiler som layouten länkar till men som inte finns under public/');
    }

    public function test_favicon_ico_innehaller_riktiga_ikoner(): void
    {
        $ico = public_path('favicon.ico');

        // Filen låg länge kvar som en tom platshållare. En nolla här betyder att
        // varje webbläsare som frågar efter /favicon.ico får en trasig ikon.
        $this->assertGreaterThan(0, filesize($ico), 'favicon.ico är tom');

        $huvud = unpack('vreserved/vtyp/vantal', (string) file_get_contents($ico, false, null, 0, 6));

        $this->assertSame(0, $huvud['reserved'], 'favicon.ico har inte en giltig ICONDIR');
        $this->assertSame(1, $huvud['typ'], 'favicon.ico är inte av typen ikon');
        $this->assertGreaterThanOrEqual(2, $huvud['antal'], 'favicon.ico ska innehålla både 16 och 32 px');
    }

    public function test_ikonkallorna_ar_valformad_xml(): void
    {
        // Webbläsaren läser favicon.svg fristående som image/svg+xml, alltså med
        // en strikt XML-parser: två bindestreck i rad inuti en kommentar — ett
        // CSS-variabelnamn, till exempel — och ikonen ritas inte alls. Att rendera
        // samma SVG inbäddad i en HTML-sida döljer felet, för HTML-parsern är
        // förlåtande. Därför kontrolleras källorna som XML här.
        $trasiga = collect([
            public_path('favicon.svg'),
            resource_path('ikoner/favicon-16.svg'),
        ])->mapWithKeys(function (string $fil) {
            // DOMDocument, inte SimpleXML: ext-dom och ext-libxml krävs redan av
            // phpunit, så testet lägger inte till något nytt beroende.
            $tidigare = libxml_use_internal_errors(true);
            libxml_clear_errors();

            $giltig = (new \DOMDocument)->load($fil);
            $fel = collect(libxml_get_errors())->map(fn ($e) => trim($e->message))->first();

            libxml_clear_errors();
            libxml_use_internal_errors($tidigare);

            return $giltig ? [] : [basename($fil) => $fel];
        })->all();

        $this->assertSame([], $trasiga, 'SVG-källor som inte är välformad XML');
    }

    /**
     * file_exists() duger inte: macOS filsystem är skiftlägesokänsligt, så en
     * länk till Favicon.svg passerar lokalt och 404:ar först på Linux i drift.
     * Repot har redan haft tre fel av den sorten. Därför jämförs varje segment
     * mot den faktiska kataloglistningen i stället.
     */
    private function finnsMedExaktSkiftlage(string $relativSokvag): bool
    {
        $katalog = public_path();

        foreach (explode('/', $relativSokvag) as $del) {
            if (! in_array($del, scandir($katalog) ?: [], true)) {
                return false;
            }

            $katalog .= '/'.$del;
        }

        return true;
    }
}
