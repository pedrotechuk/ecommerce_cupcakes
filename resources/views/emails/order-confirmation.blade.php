<x-mail::message>
# Pedido confirmado!

Olá {{ $order->user->name }}, seu pedido **#{{ $order->id }}** foi confirmado com sucesso.

<x-mail::table>
| Produto | Qtd | Preço unit. | Subtotal |
| :------ | :-: | ----------: | -------: |
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | R$ {{ number_format($item->unit_price, 2, ',', '.') }} | R$ {{ number_format($item->subtotal, 2, ',', '.') }} |
@endforeach
</x-mail::table>

**Subtotal:** R$ {{ number_format($order->subtotal, 2, ',', '.') }}
**Desconto:** R$ {{ number_format($order->discount, 2, ',', '.') }}
**Frete:** R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}
**Total:** R$ {{ number_format($order->total, 2, ',', '.') }}

**Endereço de entrega:** {{ $order->shipping_street }}, {{ $order->shipping_number }} - {{ $order->shipping_city }}

**Forma de pagamento:** {{ $order->paymentLabel() }}

<x-mail::button :url="route('orders.show', $order)">
Acompanhar pedido
</x-mail::button>

Obrigado por comprar na Cupcakes!<br>
{{ config('app.name') }}
</x-mail::message>
