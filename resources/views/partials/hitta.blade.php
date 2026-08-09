<section id="hitta" class="section hitta stjarnfalt">
  <div class="wrap">
    <div>
      <h2 class="display h2">Hitta hit</h2>
      <p class="hitta-sub">{{ config('festival.venue.address') }}</p>
    </div>
    <div class="hitta-grid">
      @foreach (config('festival.travel') as $card)
        <div class="hitta-card">
          <h3>{{ $card['title'] }}</h3>
          <p>{{ $card['text'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
