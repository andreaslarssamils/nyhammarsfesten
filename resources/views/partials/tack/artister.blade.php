<section id="artister" class="section artister stjarnfalt">
  <div class="wrap">
    <div class="lineup-head">
      <span class="lineup-tag">Lineup 2026</span>
      <span class="lineup-stars">✶✶✶</span>
    </div>

    <h2 class="display h2 tack-rubrik">{{ config('festival.tack.artister_title') }}</h2>
    <p class="tack-text">{{ config('festival.tack.artister_text') }}</p>

    {{-- div, inte länk: programmet som festivalsidan länkar till finns inte här. --}}
    @foreach (config('festival.lineup') as $act)
      <div class="act">
        @if (! empty($act['bild']))
          <img class="act-bild size-{{ $act['size'] }}" src="{{ asset($act['bild']) }}" alt="" loading="lazy" decoding="async">
        @endif
        <span class="act-name size-{{ $act['size'] }} @if($act['color']) c-{{ $act['color'] }} @endif">{{ $act['name'] }}</span>
        <span class="act-meta">{{ $act['meta'] }}</span>
      </div>
    @endforeach
  </div>
</section>
