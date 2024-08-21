<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * @implements WithMapping<Order>
 */
class OrdersExport implements FromQuery, WithMapping, WithHeadings
{
    /**
     * Fetch all orders with their related data.
     *
     * @return \Illuminate\Database\Eloquent\Builder<Order>.
     */
    public function query()
    {
        return Order::query()->where('created_at', '>=', now()->subDay());
    }

    /**
     * Map the orders data to the desired format.
     *
     * @param  Order  $order
     * @return array<mixed>
     */
    public function map($order): array
    {
        $mappedOrders = [];

        /** @var \App\Models\Address $address */
        $address = $order->address;

        /** @var \App\Models\PaymentMethod $payment_method */
        $payment_method = $order->payment_method;

        foreach ($order->items as $order_item) {

            /** @var \App\Models\Product $product */
            $product = $order_item->product;

            $mappedOrders[] = [
                'Order ID' => $order->id,
                'User ID' => $order->user_id,
                'Status' => $order->status,
                'Total' => $order->total,

                'Product ID' => $order_item->product_id,
                'Product Name' => $product->name,
                'Product Quantity' => $order_item->quantity,
                'Product Price' => $order_item->price,
                'Product Total' => $order_item->price * $order_item->quantity,

                'Address Name' => $address->label,
                'Address line 1' => $address->address_line_1,
                'Address line 2' => $address->address_line_2,
                'Address City' => $address->city,
                'Address State' => $address->state,
                'Address Postal Code' => $address->postal_code,
                'Address Country' => $address->country,
                'Address Phone Number' => $address->phone_number,

                'Payment Method' => $payment_method->type,

                'Order Created At' => $order->created_at,
                'Order Updated At' => $order->updated_at,
            ];
        }

        return $mappedOrders;
    }

    /**
     * Set the headings for the exported data.
     *
     * @return array<string>
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'User ID',
            'Status',
            'Total',

            'Product ID',
            'Product Name',
            'Product Quantity',
            'Product Price',
            'Product Total',

            'Address Name',
            'Address line 1',
            'Address line 2',
            'Address City',
            'Address State',
            'Address Postal Code',
            'Address Country',
            'Address Phone Number',

            'Payment Method',

            'Order Created At',
            'Order Updated At',
        ];
    }
}
