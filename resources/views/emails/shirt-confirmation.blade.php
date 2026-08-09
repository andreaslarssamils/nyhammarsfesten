Hej {{ $order->name }}!

Tack för din t-shirtbeställning till Nyhammarsfesten.

Ordernummer: {{ $order->reference }}
Modell: {{ $order->modelLabel() }}
Färg: {{ $order->colorLabel() }}
Storlek: {{ $order->size }}
Antal: {{ $order->quantity }} st
Summa: {{ $order->total() }} kr

@if (config('festival.shirt.payment') === 'prepay')
Swisha {{ $order->total() }} kr till {{ config('festival.shirt.swish_number') }}
och ange {{ $order->reference }} som meddelande.
@else
Du betalar när du hämtar tröjan i entrén.
@endif

Tröjan hämtas i entrén lördag 26 september från kl 12:00.
Ta med ordernumret, så går det snabbt.

Vi ses!
Nyhammarsfesten
{{ config('festival.contact.email') }}
