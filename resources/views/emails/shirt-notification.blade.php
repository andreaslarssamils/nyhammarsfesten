Ny t-shirtbeställning: {{ $order->reference }}

{{ $order->name }} <{{ $order->email }}>
Telefon: {{ $order->phone ?: '—' }}

{{ $order->quantity }} st {{ $order->modelLabel() }}, {{ $order->colorLabel() }}, storlek {{ $order->size }}
Summa: {{ $order->total() }} kr

@if ($order->note)
Meddelande: {{ $order->note }}
@endif
