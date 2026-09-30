@php $sponsors = config('festival.sponsors.items', []); @endphp

<section id="sponsorer" class="sponsorer section">
  <div class="wrap">
    <h2 class="display h2">{{ config('festival.tack.sponsorer_title') }}</h2>
    <p class="sponsor-lead">{{ config('festival.tack.sponsorer_text') }}</p>

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
    </div>
  </div>
</section>
