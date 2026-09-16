@php
  $forelasning = config('festival.lecture', []);
@endphp

@if (filled($forelasning['text'] ?? null))
  <section id="forelasning" class="section forelasning stjarnfalt">
    <div class="wrap">
      @if (filled($forelasning['tag'] ?? null))
        <div class="lineup-head">
          <span class="lineup-tag">{{ $forelasning['tag'] }}</span>
          <span class="lineup-stars">✶✶✶</span>
        </div>
      @endif

      @if (filled($forelasning['title'] ?? null))
        <h2 class="display h2">{{ $forelasning['title'] }}</h2>
      @endif

      <p class="forelasning-text">{{ $forelasning['text'] }}</p>
    </div>
  </section>
@endif
