<section id="artister" class="section artister stjarnfalt">
  <div class="wrap">
    <div class="lineup-head">
      <span class="lineup-tag">Lineup 2026</span>
      <span class="lineup-stars">✶✶✶</span>
    </div>

    @foreach (config('festival.lineup') as $act)
      <a href="#program" class="act">
        <span class="act-name size-{{ $act['size'] }} @if($act['color']) c-{{ $act['color'] }} @endif">{{ $act['name'] }}</span>
        <span class="act-meta">{{ $act['meta'] }}</span>
      </a>
    @endforeach
  </div>
</section>
