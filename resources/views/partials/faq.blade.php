<section id="faq" class="section faq">
  <div class="wrap">
    <h2 class="display h2">Vanliga frågor</h2>
    @foreach (config('festival.faq') as $item)
      <details>
        <summary>{{ $item['q'] }} <span class="plus">+</span></summary>
        <p>{{ $item['a'] }}</p>
      </details>
    @endforeach
  </div>
</section>
