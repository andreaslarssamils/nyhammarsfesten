<x-layout title="Tack för din beställning" :noindex="true">
  <section class="section tack stjarnfalt">
    <div class="wrap">
      <h1 class="display h2">Tack, {{ $order->name }}! ✶</h1>

      <div class="tack-kvitto">
        <span class="tack-ref">{{ $order->reference }}</span>
        <div class="tack-rad">
          <span>{{ $order->quantity }} st {{ $order->modelLabel() }}</span>
          <span>{{ $order->colorLabel() }} ✶ storlek {{ $order->size }}</span>
        </div>
        <div class="tack-rad">
          <span>Att betala</span>
          <span>{{ $order->total() }} kr</span>
        </div>
      </div>

      @if (config('festival.shirt.payment') === 'prepay')
        <p class="tack-swish">
          Swisha {{ $order->total() }} kr till
          <strong>{{ config('festival.shirt.swish_number') }}</strong>
          och skriv <strong>{{ $order->reference }}</strong> som meddelande.
        </p>
      @else
        <p class="tack-swish">Du betalar {{ $order->total() }} kr när du hämtar tröjan i entrén.</p>
      @endif

      <p>
        Tröjan hämtar du i entrén lördag 26 september från kl 12:00 — ta med ordernumret,
        så går det snabbt. En bekräftelse är på väg till {{ $order->email }}.
      </p>

      <a href="{{ route('home') }}" class="tack-tillbaka">← Tillbaka till startsidan</a>
    </div>
  </section>
</x-layout>
