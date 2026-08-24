@php
  $video  = config('festival.video', []);
  $poster = $video['poster'] ?? null;
  $titel  = $video['title'] ?? null;
  $text   = $video['text'] ?? null;
@endphp

@if (filled($video['fil'] ?? null))
  <section id="video" class="section video-sektion stjarnfalt">
    <div class="wrap">
      @if (filled($video['tag'] ?? null))
        <div class="lineup-head">
          <span class="lineup-tag">{{ $video['tag'] }}</span>
          <span class="lineup-stars">✶✶✶</span>
        </div>
      @endif

      {{--
        Inget autoplay-attribut här: det hade startat klippet innan site.js hunnit
        läsa prefers-reduced-motion, och en mediefråga i CSS kan inte stoppa en
        video. controls sitter kvar oavsett — rörlig bild som startar av sig själv
        och håller på längre än fem sekunder måste gå att pausa, och utan JS blir
        det i stället en helt vanlig klick-för-att-spela-spelare.

        #t=0.1 på källan när posterbild saknas: utan tidsfragmentet står spelaren
        svart tills någon trycker play, eftersom preload="metadata" bara hämtar
        rubriken och ingen bildruta.
      --}}
      {{-- figure, inte video + fristående p: bildtexten ska följa klippets bredd, och
           den är inte känd i förväg — den beror på filens egna proportioner. --}}
      <figure class="video-figur">
        <video id="videoKlipp" class="video-klipp"
               muted loop playsinline controls preload="metadata"
               @if (filled($poster)) poster="{{ asset($poster) }}" @endif>
          <source src="{{ asset($video['fil']).(filled($poster) ? '' : '#t=0.1') }}" type="video/mp4">
        </video>

        @if (filled($titel) || filled($text))
          <figcaption class="video-text">
            @if (filled($titel))<b>{{ $titel }}</b>@endif
            {{ $text }}
          </figcaption>
        @endif
      </figure>
    </div>
  </section>
@endif
