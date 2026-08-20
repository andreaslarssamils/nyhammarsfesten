@php $label = config('festival.date_label'); @endphp

<header id="hem" class="hero stjarnfalt">
  @include('partials.notrad', ['id' => 'nl1', 'class' => 'notrad-hero'])

  <div class="star star-1">✶</div>
  <div class="star star-2">✶</div>
  <div class="star star-3">✦</div>

  <div class="hero-inner">
    <div class="hero-badge">{{ config('festival.badge') }}</div>

    <h1 class="hero-title">
      <span class="sr-only">{{ config('festival.name') }}</span>
      <svg class="hero-arc" viewBox="0 0 1200 300" aria-hidden="true" focusable="false">
        <defs><path id="bage" fill="none" d="M40 268 Q600 8 1160 268"/></defs>
        <text textLength="1130" lengthAdjust="spacingAndGlyphs">
          <textPath href="#bage" startOffset="50%" text-anchor="middle">{{ config('festival.name') }}</textPath>
        </text>
      </svg>
      <span class="hero-flat" aria-hidden="true">Nyhammars<wbr>festen</span>
    </h1>

    <span class="hero-sub">{{ config('festival.tagline') }}</span>
    <p class="hero-lead">{{ config('festival.lead') }}</p>

    <div class="sticker">
      <span class="s1">{{ $label['dag'] }}</span>
      <span class="s2">{{ $label['datum'] }}</span>
      <span class="s3">{{ $label['manad'] }}</span>
    </div>

    <x-forkop class="hero-cta" label="Förköp biljett ✶" fallback="#biljetter" />
  </div>

  <div class="countdown" id="countdown" data-target="{{ config('festival.date') }}">
    <div class="count-chip"><b id="cdDagar">–</b><span>Dagar</span></div>
    <div class="count-chip"><b id="cdTimmar">–</b><span>Timmar</span></div>
    <div class="count-chip"><b id="cdMinuter">–</b><span>Minuter</span></div>
    <div class="count-chip"><b id="cdSekunder">–</b><span>Sekunder</span></div>
  </div>

  <div class="maskot" aria-hidden="true">
    <svg class="note n1" viewBox="0 0 40 40" focusable="false"><g fill="currentColor"><ellipse cx="14" cy="30" rx="8" ry="6" transform="rotate(-20 14 30)"/><rect x="20" y="4" width="2.8" height="27"/><path d="M22.8 4 C30 6 33 10 32 16 C30 11 27 9 22.8 9 Z"/></g></svg>
    <svg class="note n2" viewBox="0 0 40 40" focusable="false"><g fill="currentColor"><ellipse cx="10" cy="32" rx="7.5" ry="5.6" transform="rotate(-20 10 32)"/><ellipse cx="30" cy="28" rx="7.5" ry="5.6" transform="rotate(-20 30 28)"/><rect x="16" y="8" width="2.6" height="25"/><rect x="36" y="4" width="2.6" height="25"/><path d="M15 8 L39 4 L39 10 L15 14 Z"/></g></svg>
    <svg class="note n3" viewBox="0 0 40 40" focusable="false"><g fill="currentColor"><ellipse cx="14" cy="30" rx="8" ry="6" transform="rotate(-20 14 30)"/><rect x="20" y="4" width="2.8" height="27"/><path d="M22.8 4 C30 6 33 10 32 16 C30 11 27 9 22.8 9 Z"/></g></svg>
    <img src="{{ asset('assets/apa.png') }}" alt="" width="460" height="752">
  </div>
</header>
