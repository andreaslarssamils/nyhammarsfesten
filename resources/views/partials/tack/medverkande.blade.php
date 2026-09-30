<section id="medverkande" class="section medverkande">
  <div class="wrap">
    <h2 class="display h2">{{ config('festival.tack.medverkande_title') }}</h2>

    <div class="tack-lista">
      @foreach (config('festival.tack.medverkande', []) as $rad)
        <div class="tack-kort">
          <h3 class="tack-kort-titel">{{ $rad['title'] }}</h3>
          <p>{{ $rad['text'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
