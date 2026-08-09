{{-- Notsystemet. $id måste vara unikt per förekomst (SVG-id delas i dokumentet). --}}
<svg class="notrad {{ $class }}" viewBox="0 0 1200 96" aria-hidden="true" focusable="false">
  <g fill="none" stroke="currentColor" stroke-width="1.6" opacity="0.85">
    <path id="{{ $id }}" d="M0 18 C200 -2 400 38 600 18 S1000 -2 1200 18"/>
    <use href="#{{ $id }}" y="9"/><use href="#{{ $id }}" y="18"/><use href="#{{ $id }}" y="27"/><use href="#{{ $id }}" y="36"/>
  </g>
  <g fill="currentColor">
    @if ($id === 'nl1')
      <ellipse cx="250" cy="50" rx="9" ry="6.5" transform="rotate(-20 250 50)"/>
      <rect x="257" y="20" width="2.6" height="31"/>
      <path d="M259.6 20 C267 22 270 26 269 32 C267 27 264 25 259.6 25 Z"/>
      <ellipse cx="700" cy="44" rx="9" ry="6.5" transform="rotate(-20 700 44)"/>
      <ellipse cx="760" cy="36" rx="9" ry="6.5" transform="rotate(-20 760 36)"/>
      <rect x="707" y="8" width="2.6" height="37"/>
      <rect x="767" y="0" width="2.6" height="37"/>
      <path d="M706 8 L770 0 L770 7 L706 15 Z"/>
      <ellipse cx="1050" cy="56" rx="9" ry="6.5" transform="rotate(-20 1050 56)"/>
      <rect x="1057" y="26" width="2.6" height="31"/>
    @else
      <ellipse cx="180" cy="52" rx="9" ry="6.5" transform="rotate(-20 180 52)"/>
      <rect x="187" y="22" width="2.6" height="31"/>
      <path d="M189.6 22 C197 24 200 28 199 34 C197 29 194 27 189.6 27 Z"/>
      <ellipse cx="620" cy="42" rx="9" ry="6.5" transform="rotate(-20 620 42)"/>
      <ellipse cx="680" cy="34" rx="9" ry="6.5" transform="rotate(-20 680 34)"/>
      <rect x="627" y="6" width="2.6" height="37"/>
      <rect x="687" y="0" width="2.6" height="35"/>
      <path d="M626 6 L690 0 L690 7 L626 13 Z"/>
      <ellipse cx="980" cy="54" rx="9" ry="6.5" transform="rotate(-20 980 54)"/>
      <rect x="987" y="24" width="2.6" height="31"/>
    @endif
  </g>
</svg>
