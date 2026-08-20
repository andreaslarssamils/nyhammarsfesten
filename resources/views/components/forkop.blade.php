@props(['label', 'fallback' => null])

@php
  // '#' är platshållaren i config, precis som för contact.facebook — en länk dit tar
  // besökaren ingenstans. Saknas adressen faller call-siten tillbaka på sitt ankare,
  // om den har ett; biljettsektionen har ingen fallback och ritar då inget alls.
  $url = config('festival.tickets.forkop_url');
@endphp

@if (filled($url) && $url !== '#')
  <a {{ $attributes }} href="{{ $url }}" rel="noopener" target="_blank">{{ $label }}</a>
@elseif ($fallback)
  <a {{ $attributes }} href="{{ $fallback }}">{{ $label }}</a>
@endif
