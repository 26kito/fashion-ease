<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TransactionDetailModal extends Component
{
    public $openModal = false;
    public $data;

    public $listeners = ['openModalTransactionDetail' => 'showModal'];

    public function render()
    {
        return view('livewire.transaction-detail-modal');
    }

    public function showModal($orderID)
    {
        $this->data = DB::table('order_items')
            ->join('products', 'order_items.product_id', 'products.product_id')
            ->join('detail_products', function ($join) {
                $join->on('order_items.product_id', 'detail_products.product_id')
                    ->whereColumn('order_items.size', 'detail_products.size');
            })
            ->where('order_items.order_id', $orderID)
            ->select(
                'products.name',
                'products.image',
                'detail_products.size',
                'detail_products.price',
                'order_items.qty',
                DB::raw('detail_products.price * order_items.qty AS total_price')
            )
            ->get();

            foreach ($this->data as $row) {
                $row->price = rupiah($row->price);
                $row->total_price = rupiah($row->total_price);
            }

        $this->dispatchBrowserEvent('openModalTransactionDetail');
    }
}
