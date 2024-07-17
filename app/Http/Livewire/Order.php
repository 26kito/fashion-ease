<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class Order extends Component
{
    public $data = [];
    public $orderStatus = ["Semua", "Berlangsung", "Berhasil", "Tidak Berhasil"];
    public $orderStatusSelected = "Semua";
    public $keywordOrderSearch = "";

    public function render()
    {
        $query = $this->getOrder();

        if ($this->orderStatusSelected == "Berlangsung") {
            $query->whereIn("orders.status_order_id", [2, 3, 7]);
        }

        if ($this->orderStatusSelected == "Berhasil") {
            $query->where("orders.status_order_id", 8);
        }

        if ($this->orderStatusSelected == "Tidak Berhasil") {
            $query->where("orders.status_order_id", 4);
        }

        $this->data = $query->get();

        foreach ($this->data as $key => $value) {
            // format price to rupiah
            $value->product_price = rupiah($value->product_price);
            $value->grand_total = rupiah($value->grand_total);
            // parse and format date
            $orderDate = Date::createFromFormat("Y-m-d", $value->order_date);
            $value->order_date = date_format($orderDate, "d M Y");
        }

        return view("livewire.order");
    }

    protected function getOrder()
    {
        $data = DB::table("orders")
            ->join("order_items", "orders.order_id", "order_items.order_id")
            ->join("products", "order_items.product_id", "products.id")
            ->join("status_order", "orders.status_order_id", "status_order.id")
            ->select("orders.order_id", "orders.order_date", "orders.status_order_id", "orders.grand_total", "order_items.qty", "products.name AS product_name", "products.image AS product_image", "products.price AS product_price", "status_order.description AS order_status", "status_order.status_order_id AS order_status_id")
            ->where("orders.user_id", Auth::id());

        return $data;
    }

    public function searchOrder()
    {
        dd($this->keywordOrderSearch);
    }

    public function selectStatus($status)
    {
        $this->orderStatusSelected = $status;
    }

    public function resetSelectedOrderStatus()
    {
        $this->reset('orderStatusSelected');
    }
}
