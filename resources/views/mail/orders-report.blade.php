<x-mail::message>
# Orders Report

<x-mail::table>

| Order ID | Customer Name | Items Count | Total Amount |
|:--------:|:-------------:|:-----------:|:------------:|
@foreach($orders as $order)
| <div style="text-align: center;">{{ $order->id }}</div> | <div style="text-align: center;">{{ $order->user->name }}</div> | <div style="text-align: center;">{{ $order->items->count() }}</div> | <div style="text-align: center;">${{ $order->total }}</div> |
@endforeach

</x-mail::table>

---

## Summary

<x-mail::panel>
**Total number of orders:** {{ $orders->count() }}<br>
**Total sales for the day:** ${{ $orders->sum('total') }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
