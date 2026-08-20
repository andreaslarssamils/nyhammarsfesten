<section id="biljetter" class="section biljetter stjarnfalt">
  <div class="wrap">
    <h2 class="display h2">Biljetter</h2>
    <div class="ticket-grid">
      @foreach (config('festival.tickets.items') as $ticket)
        <div class="ticket ticket-{{ $ticket['variant'] }}">
          <span class="t-label">{{ $ticket['label'] }}</span>
          <span class="t-price">{{ $ticket['price'] }}</span>
          <span class="t-note">{{ $ticket['note'] }}</span>
        </div>
      @endforeach
    </div>
    <p class="ticket-info">{{ config('festival.tickets.info') }}</p>
    <x-forkop class="biljett-cta" label="Förköp på Billetto ✶" />
  </div>
</section>
