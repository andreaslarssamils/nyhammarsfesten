@php
    $sponsors = config('festival.sponsors.items', []);
    $emptySlots = max(0, 4 - count($sponsors));
@endphp

<section id="sponsorer" class="sponsorer section">
  <div class="wrap">
    <h2 class="display h2">Sponsorer</h2>
    <p class="sponsor-lead">{{ config('festival.sponsors.lead') }}</p>

    @if (count($sponsors) > 0)
      <p class="sponsor-hint">{{ config('festival.sponsors.hint') }}</p>
    @endif

    <div class="sponsor-row">
      @foreach ($sponsors as $sponsor)
        @if (! empty($sponsor['url']))
          <a class="sponsor-slot sponsor-slot-namn" href="{{ $sponsor['url'] }}" rel="noopener" target="_blank">{{ $sponsor['name'] }}</a>
        @else
          <div class="sponsor-slot sponsor-slot-namn">{{ $sponsor['name'] }}</div>
        @endif
      @endforeach

      @for ($i = 0; $i < $emptySlots; $i++)
        <div class="sponsor-slot">Din logga här ✶</div>
      @endfor
    </div>
    <a href="mailto:{{ config('festival.contact.sponsor') }}" class="sponsor-cta">Bli sponsor</a>
  </div>
</section>
