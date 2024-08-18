<x-mail::message>
# Purchase Confirmation

Hi {{ $order->user->name }},<br>
Your order has been placed successfully.<br>
Here are the details of your order:<br>

**Order ID:** {{ $order->id }}<br>
**Order Date:** {{ $order->created_at }}<br>
**Order Status:** {{ $order->status }}<br>
**Total Amount:** ${{ $order->total }}<br>

**Items Ordered:**<br>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ $item->price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
