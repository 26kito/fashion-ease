<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class Order extends Component
{
    public $data = [];
    public $keywordOrderSearch = "";
    public $orderStatus = ["Semua", "Berlangsung", "Berhasil", "Tidak Berhasil"];
    public $orderStatusSelected = "Semua";
    public $subOrderStatus = ["Menunggu Konfirmasi", "Diproses", "Dikirim", "Tiba di Tujuan", "Dikomplain"];
    public $subDrderStatusSelected;
    public $orderStatusFilter = [
        "Berlangsung" => ["SO001", "SO002", "SO003", "SO005"],
        "Berhasil" => ["SO006"],
        "Tidak Berhasil" => ["SO004"],
    ];
    public $orderSubStatusFilter = [
        "Menunggu Konfirmasi" => "SO001",
        "Diproses" => "SO002",
        "Dikirim" => "SO005",
        "Tiba di Tujuan" => -1,
        "Dikomplain" => -1,
    ];

    public function render()
    {
        $query = $this->getOrder();

        if ($this->orderStatusSelected === "Semua") {
            $this->reset("orderStatusSelected");
            $this->reset("subDrderStatusSelected");
        }

        if ($this->orderStatusSelected !== "Semua" && isset($this->orderStatusFilter[$this->orderStatusSelected])) {
            $query->whereIn("orders.status_order_id", $this->orderStatusFilter[$this->orderStatusSelected]);
        }

        if (isset($this->orderSubStatusFilter[$this->subDrderStatusSelected])) {
            $statusIds = $this->orderSubStatusFilter[$this->subDrderStatusSelected];

            $query->where("orders.status_order_id", $statusIds);
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
            ->join("products", "order_items.product_id", "products.product_id")
            ->join("status_order", "orders.status_order_id", "status_order.status_order_id")
            ->select(
                "orders.order_id",
                "orders.order_date",
                "orders.status_order_id",
                "status_order.description AS order_status",
                "orders.grand_total",
                "order_items.qty",
                "products.name AS product_name",
                "products.image AS product_image",
                "order_items.product_id",
                "order_items.price AS product_price"
            )
            ->where("orders.user_id", Auth::id())
            ->groupBy("order_items.order_id");

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

    public function selectSubStatus($status)
    {
        $this->subDrderStatusSelected = $status;
    }

    public function resetSelectedOrderStatus()
    {
        $this->reset('orderStatusSelected');
    }
}
