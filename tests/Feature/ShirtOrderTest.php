<?php

namespace Tests\Feature;

use App\Mail\ShirtOrderConfirmation;
use App\Mail\ShirtOrderNotification;
use App\Models\ShirtOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ShirtOrderTest extends TestCase
{
    use RefreshDatabase;

    /** Giltig beställning som varje test utgår från. */
    private function order(array $overrides = []): array
    {
        return [
            'name'     => 'Anna Andersson',
            'email'    => 'anna@example.com',
            'phone'    => '070-123 45 67',
            'model'    => 'unisex',
            'color'    => 'svart',
            'size'     => 'M',
            'quantity' => 2,
            'note'     => '',
            ...$overrides,
        ];
    }

    public function test_giltig_bestallning_sparas_och_mejlas(): void
    {
        Mail::fake();

        $response = $this->post('/tshirt', $this->order());

        $order = ShirtOrder::sole();

        $this->assertSame('svart', $order->color);
        $this->assertSame('unisex', $order->model);
        $this->assertSame('M', $order->size);
        $this->assertSame(config('festival.shirt.price'), $order->unit_price);
        $this->assertMatchesRegularExpression('/^NF-\d{4}-\d{4}$/', $order->reference);

        $response->assertRedirect(route('shirts.thanks', ['reference' => $order->reference]));

        Mail::assertSent(ShirtOrderConfirmation::class, fn ($mail) => $mail->hasTo('anna@example.com'));
        Mail::assertSent(ShirtOrderNotification::class, fn ($mail) => $mail->hasTo(config('festival.contact.email')));
    }

    public function test_kvittosidan_visar_ordern(): void
    {
        Mail::fake();
        $this->post('/tshirt', $this->order(['color' => 'vit']));

        $this->get(route('shirts.thanks', ['reference' => ShirtOrder::sole()->reference]))
            ->assertOk()
            ->assertSee('Vit', escape: false)
            ->assertSee('noindex', escape: false);
    }

    /**
     * Obs: fragmentet ensamt scrollar inte. Chrome hoppar över den initiala
     * fragmentscrollen medan typsnitt och maskotbilden laddar och sidan växer.
     * Det är blocket i site.js som söker upp .notis-fel och scrollar dit som
     * gör att besökaren faktiskt ser felen — tas det bort återkommer buggen
     * utan att det här testet blir rött.
     */
    public function test_barnstorlek_pa_vuxenmodell_avvisas_och_redirectar_till_fragmentet(): void
    {
        Mail::fake();

        $response = $this->from('/')->post('/tshirt', $this->order(['model' => 'unisex', 'size' => '122']));

        $response->assertSessionHasErrors('size');
        $this->assertStringEndsWith('#tshirt', $response->headers->get('Location'));
        $this->assertSame(0, ShirtOrder::count());
        Mail::assertNothingSent();
    }

    public function test_vuxenstorlek_pa_barnmodell_avvisas(): void
    {
        $this->post('/tshirt', $this->order(['model' => 'barn', 'size' => 'M']))
            ->assertSessionHasErrors('size');

        $this->assertSame(0, ShirtOrder::count());
    }

    public function test_barnstorlek_pa_barnmodell_gar_igenom(): void
    {
        Mail::fake();

        $this->post('/tshirt', $this->order(['model' => 'barn', 'size' => '122']));

        $this->assertSame('122', ShirtOrder::sole()->size);
    }

    public function test_okand_farg_avvisas(): void
    {
        $this->post('/tshirt', $this->order(['color' => 'rosa']))
            ->assertSessionHasErrors('color');

        $this->assertSame(0, ShirtOrder::count());
    }

    public function test_farg_kravs(): void
    {
        $order = $this->order();
        unset($order['color']);

        $this->post('/tshirt', $order)->assertSessionHasErrors('color');
    }

    public function test_ifyllt_honungsfalt_avvisas(): void
    {
        $this->post('/tshirt', $this->order(['website' => 'https://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertSame(0, ShirtOrder::count());
    }

    public function test_valideringsfel_ar_pa_svenska(): void
    {
        $this->post('/tshirt', $this->order(['name' => '']))
            ->assertSessionHasErrors(['name' => 'Namn måste fyllas i.']);
    }

    public function test_stangd_bokning_blockeras(): void
    {
        config(['festival.shirt.deadline' => '2020-01-01']);

        $this->post('/tshirt', $this->order())->assertForbidden();

        $this->assertSame(0, ShirtOrder::count());
    }

    public function test_ordern_sparas_aven_om_mejlet_fallerar(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP nere'));

        $this->post('/tshirt', $this->order())
            ->assertRedirect(route('shirts.thanks', ['reference' => 'NF-'.now()->year.'-0001']));

        $this->assertSame(1, ShirtOrder::count());
    }

    public function test_admin_utan_nyckel_ar_stangd(): void
    {
        config(['festival.admin_key' => null]);

        $this->get('/admin/bestallningar')->assertForbidden();
        $this->get('/admin/bestallningar?nyckel=')->assertForbidden();
    }

    public function test_admin_med_fel_nyckel_ar_stangd(): void
    {
        config(['festival.admin_key' => 'hemligt']);

        $this->get('/admin/bestallningar?nyckel=gissning')->assertForbidden();
    }

    public function test_admin_med_ratt_nyckel_visar_farg(): void
    {
        Mail::fake();
        config(['festival.admin_key' => 'hemligt']);

        $this->post('/tshirt', $this->order(['color' => 'vit']));

        $this->get('/admin/bestallningar?nyckel=hemligt')
            ->assertOk()
            ->assertSee('Färg', escape: false)
            ->assertSee('Vit', escape: false);
    }

    public function test_tryckunderlaget_sorteras_i_storleksordning(): void
    {
        foreach ([['unisex', 'svart', 'XXL'], ['unisex', 'svart', 'S'], ['unisex', 'vit', 'M'], ['barn', 'svart', '134']] as [$model, $color, $size]) {
            ShirtOrder::create($this->order(compact('model', 'color', 'size')));
        }

        $this->assertSame(
            [
                ['unisex', 'svart', 'S'],
                ['unisex', 'svart', 'XXL'],
                ['unisex', 'vit', 'M'],
                ['barn', 'svart', '134'],
            ],
            ShirtOrder::printSummary()
                ->map(fn ($rad) => [$rad->model, $rad->color, $rad->size])
                ->all()
        );
    }

    public function test_varje_farg_har_en_bildfil_som_finns(): void
    {
        foreach (config('festival.shirt.colors') as $nyckel => $farg) {
            $this->assertArrayHasKey('label', $farg, "Färgen {$nyckel} saknar label");
            $this->assertArrayHasKey('bild', $farg, "Färgen {$nyckel} saknar bild");
            $this->assertFileExists(
                public_path($farg['bild']),
                "Bilden för {$nyckel} finns inte: {$farg['bild']}"
            );
        }
    }

    public function test_etiketterna_faller_tillbaka_pa_radata_nar_confignyckeln_bytts(): void
    {
        $order = new ShirtOrder(['model' => 'borttagen', 'color' => 'gammal']);

        $this->assertSame('borttagen', $order->modelLabel());
        $this->assertSame('gammal', $order->colorLabel());
    }

    public function test_lopnumret_rader_upp_sig(): void
    {
        Mail::fake();

        $this->post('/tshirt', $this->order());
        $this->post('/tshirt', $this->order());

        $this->assertSame(
            ['NF-'.now()->year.'-0001', 'NF-'.now()->year.'-0002'],
            ShirtOrder::orderBy('id')->pluck('reference')->all()
        );
    }
}
