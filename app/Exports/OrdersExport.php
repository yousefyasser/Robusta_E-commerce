<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithMapping, WithHeadings
{
    /**
     * Fetch all orders with their related data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return Order::query()->where('created_at', '>=', now(env('APP_TIMEZONE', 'UTC'))->subDay());
    }

    /**
     * Map the orders data to the desired format.
     *
     * @param  mixed  $order
     * @return array
     */
    public function map($order): array
    {
        $mappedOrders = [];

        foreach ($order->items as $order_item) {
            $mappedOrders[] = [
                'Order ID' => $order->id,
                'User ID' => $order->user_id,
                'Status' => $order->status,
                'Total' => $order->total,

                'Product ID' => $order_item->product_id,
                'Product Name' => $order_item->product->name,
                'Product Quantity' => $order_item->quantity,
                'Product Price' => $order_item->price,
                'Product Total' => $order_item->price * $order_item->quantity,

                'Address Name' => $order->address->label,
                'Address line 1' => $order->address->address_line_1,
                'Address line 2' => $order->address->address_line_2,
                'Address City' => $order->address->city,
                'Address State' => $order->address->state,
                'Address Postal Code' => $order->address->postal_code,
                'Address Country' => $order->address->country,
                'Address Phone Number' => $order->address->phone_number,

                'Payment Method' => $order->payment_method->type,

                'Order Created At' => $order->created_at,
                'Order Updated At' => $order->updated_at,
            ];
        }

        return $mappedOrders;
    }

    /**
     * Set the headings for the exported data.
     *
     * @return array
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
