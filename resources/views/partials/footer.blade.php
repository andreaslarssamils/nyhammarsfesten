<footer id="kontakt" class="footer stjarnfalt">
  <div class="wrap">
    <h2 class="display footer-title">Vi ses där ✶</h2>
    <div class="contact-row">
      <div class="contact-col">
        <span class="contact-label">Mejl</span>
        <a href="mailto:{{ config('festival.contact.email') }}" class="contact-big">{{ config('festival.contact.email') }}</a>
      </div>
      <div class="contact-col">
        <span class="contact-label">Telefon</span>
        <a href="tel:{{ config('festival.contact.phone_tel') }}" class="contact-big">{{ config('festival.contact.phone') }}</a>
      </div>
      @php
        // '#' är platshållaren i config — en länk dit tar besökaren ingenstans,
        // så kolumnen ritas bara när det finns någon adress att gå till.
        $some = collect(['Facebook' => 'facebook', 'Instagram' => 'instagram'])
            ->map(fn ($key) => config("festival.contact.$key"))
            ->reject(fn ($url) => blank($url) || $url === '#');
      @endphp
      @if ($some->isNotEmpty())
        <div class="contact-col">
          <span class="contact-label">Följ oss</span>
          <div class="some">
            @foreach ($some as $namn => $url)
              <a href="{{ $url }}" rel="noopener" target="_blank">{{ $namn }}</a>
            @endforeach
          </div>
        </div>
      @endif
    </div>
    <div class="fine">
      <span>{{ config('festival.name') }} ✶ 26 september 2026 ✶ Nyhammar, Dalarna</span>
      <span>Arrangeras av bygden, för bygden.</span>
    </div>
  </div>
  @include('partials.notrad', ['id' => 'nl2', 'class' => 'notrad-footer'])
</footer>
