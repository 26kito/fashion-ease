<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    public function index($order_id)
    {
        try {
            $data = DB::table('orders')
                ->join('order_items', 'orders.order_id', 'order_items.order_id')
                ->join('products', 'order_items.product_id', 'products.id')
                ->where('orders.id', $order_id)
                ->select('orders.id', 'orders.order_id', 'products.name', 'order_items.size', 'order_items.qty', 'products.price')
                ->get();

            if (count($data) == 0) {
                return response()->json(['data' => 'data tidak ditemukan'], 400);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'integer|exists:orders,id',
            'product_id' => 'integer|exists:products,id',
            'size' => 'string',
            'qty' => 'integer|min:1'
        ]);

        $availSize = DB::table('detail_products')
            ->where('dp_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if ($availSize != null && $availSize->stock >= $request->qty && $request->qty !== 0) {
            // Setelah di validasi, tampung semua request kedalam variabel
            $data = $request->all();
            OrderItem::create($data);
            $orderItem = DB::table('orders')
                ->join('order_items', 'orders.id', 'order_items.order_id')
                ->join('products', 'order_items.product_id', 'products.id')
                ->join('users', 'users.id', 'orders.user_id')
                ->where('order_items.order_id', $request->order_id)
                ->select('orders.id AS order_id', 'order_items.id', 'products.name', 'order_items.size', 'order_items.qty', 'products.price')
                ->get();

            return $this->successResponse(200, 'OK', $orderItem);
        } else {
            return $this->failedResponse(200, 'Tidak ada stock yang tersedia di ukuran ini!');
        }
    }

    public function show($order_id, $id)
    {
        try {
            $data = OrderItem::with('product')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->where('order_id', '=', $order_id)
                ->where('order_items.id', '=', $id)
                ->select('order_items.*', 'users.name')
                ->get();

            if (count($data) === 0) {
                return response()->json(['data' => 'data tidak ditemukan'], 400);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function edit($order_id, $id)
    {
        $orderItem = OrderItem::where('id', $id)->where('order_id', $order_id)->first();
        $products = Product::all();
        $size = ['S', 'M', 'L', 'XL'];
        return view('admin.order.editOrder', compact('orderItem', 'products', 'size'));
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'order_id' => 'integer|exists:orders,id',
            'product_id' => 'integer|exists:products,id',
            'qty' => 'integer|min:1'
        ]);

        if ($validate->passes()) {
            $orderItem = DB::table('order_items')->where('id', $request->id)->where('order_id', $request->order_id)->first();
            if ($orderItem) {
                $availSize = DB::table('detail_products')
                    ->where('dp_id', $request->product_id)
                    ->where('size', $request->size)
                    ->first();
                if ($availSize != null && $availSize->stock >= $request->qty && $request->qty !== 0) {
                    DB::table('order_items')->update([
                        'product_id' => $request->product_id,
                        'size' => $request->size,
                        'qty' => $request->qty
                    ]);
                    $data = DB::table('orders')
                        ->join('order_items', 'orders.id', 'order_items.order_id')
                        ->join('products', 'order_items.product_id', 'products.id')
                        ->join('users', 'users.id', 'orders.user_id')
                        ->where('order_items.order_id', $request->order_id)
                        ->select('orders.id AS order_id', 'order_items.id', 'products.name', 'order_items.size', 'order_items.qty', 'products.price')
                        ->get();
                    return $this->successResponse(200, 'Berhasil mengubah', $data);
                } else {
                    return $this->failedResponse(200, 'Tidak ada stock yang tersedia di ukuran ini!');
                }
            } else {
                return $this->failedResponse(200, 'tidak ditemukan');
            }
        } else {
            return $this->failedResponse(200, $validate->errors()->first());
        }
    }

    public function destroy($order_id, $id)
    {
        $data = OrderItem::where('order_id', $order_id)->where('id', $id)->first();
        if (!$data) {
            return response()->json(['message' => 'data tidak ditemukan'], 400);
        }
        $data->delete();
        $orderItem = DB::table('orders')
            ->join('order_items', 'orders.id', 'order_items.order_id')
            ->join('products', 'order_items.product_id', 'products.id')
            ->join('users', 'users.id', 'orders.user_id')
            ->where('order_items.order_id', $order_id)
            ->select('orders.id AS order_id', 'order_items.id', 'products.name', 'order_items.size', 'order_items.qty', 'products.price')
            ->get();

        return $this->successResponse(200, 'Pesanan berhasil dihapus', $orderItem);
    }
}
