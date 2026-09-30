@php $text ??= config('festival.ticker'); @endphp

<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <span>{{ $text }}</span>
    <span>{{ $text }}</span>
  </div>
</div>
