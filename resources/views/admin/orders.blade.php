<x-layout title="Beställningar" :noindex="true">
  <div class="admin">
    <div class="wrap">
      <h1>T-shirtbeställningar</h1>

      <div>
        <h2>Underlag till tryckeriet</h2>
        <div class="admin-svep">
          <table class="admin-tabell">
            <thead><tr><th>Modell</th><th>Färg</th><th>Storlek</th><th>Antal</th></tr></thead>
            <tbody>
              @foreach ($summary as $row)
                <tr>
                  <td>{{ $row->modelLabel() }}</td>
                  <td>{{ $row->colorLabel() }}</td>
                  <td>{{ $row->size }}</td>
                  <td>{{ $row->total }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr><td colspan="3">Totalt</td><td>{{ $summary->sum('total') }}</td></tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div>
        <h2>Alla beställningar ({{ $orders->count() }})</h2>
        <div class="admin-svep">
          <table class="admin-tabell">
            <thead>
              <tr>
                <th>Order</th><th>Namn</th><th>Kontakt</th>
                <th>Tröja</th><th>Summa</th><th>Betald</th><th>Hämtad</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($orders as $order)
                <tr>
                  <td>{{ $order->reference }}</td>
                  <td>{{ $order->name }}</td>
                  <td>{{ $order->email }}<br>{{ $order->phone }}</td>
                  <td>{{ $order->quantity }} × {{ $order->modelLabel() }} {{ $order->colorLabel() }} {{ $order->size }}</td>
                  <td>{{ $order->total() }} kr</td>
                  <td @class(['obetald' => ! $order->isPaid()])>{{ $order->paid_at?->format('j/n') ?? 'Nej' }}</td>
                  <td>{{ $order->collected_at?->format('j/n') ?? '—' }}</td>
                </tr>
              @empty
                <tr><td colspan="7">Inga beställningar än.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-layout>
