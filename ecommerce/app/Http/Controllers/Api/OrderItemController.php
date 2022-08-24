<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($order_id)
    {
        try {
            $data = OrderItem::with('product')
                            ->join('orders', 'orders.id', '=', 'order_items.order_id')
                            ->join('users', 'users.id', '=', 'orders.user_id')
                            ->where('order_id', '=', $order_id)
                            ->select('order_items.*', 'users.name')
                            ->get();
            if ( count($data) === 0 ) {
                return response()->json(['data' => 'data tidak ditemukan'], 400);
            }
            return response()->json(['data' => $data], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'integer|exists:orders,id',
                'product_id' => 'integer|exists:products,id',
                'size' => 'string',
                'qty' => 'integer|min:1'
            ]);
            // Setelah di validasi, tampung semua request kedalam variabel
            $data = $request->all();
            OrderItem::create($data);
            $orderItem = OrderItem::with('product')
                            ->join('orders', 'orders.id', '=', 'order_items.order_id')
                            ->join('users', 'users.id', '=', 'orders.user_id')
                            ->where('order_items.order_id', '=', $request->order_id)
                            ->select('order_items.*', 'users.name')
                            ->get();
    
            return response()->json(['message' => 'berhasil menambahkan data', 'data' => $orderItem], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

            if ( count($data) === 0 ) {
                return response()->json(['data' => 'data tidak ditemukan'], 400);
            }

            return response()->json(['data' => $data], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $orderItem = OrderItem::where('id', '=', $id)->first();
        $products = Product::all();
        return view('admin.order.editOrder', compact('orderItem', 'id', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'integer|exists:orders,id',
                'product_id' => 'integer|exists:products,id',
                'size' => 'string',
                'qty' => 'integer|min:1'
            ]);

            $orderItem = OrderItem::findOrFail($request->id);
            $orderItem->order_id = $request->order_id;
            $orderItem->product_id = $request->product_id;
            $orderItem->qty = $request->qty;
            $orderItem->save();
            $orderItem = OrderItem::with('product')
                                ->select('order_items.*', 'users.name')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->join('users', 'users.id', '=', 'orders.user_id')
                                ->where('order_items.order_id', '=', $request->order_id)
                                ->get();

            return response()->json(['data' => $orderItem]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_id, $id)
    {
        try {
            $data = OrderItem::where('order_id', $order_id)->where('id', $id)->first();
            if ( !$data ) {
                return response()->json(['message' => 'data tidak ditemukan'], 400);
            }
            $data->delete();
            $orderItem = OrderItem::with('product', 'user')->where('order_id', $order_id)->get();

            return response()->json([ 'message' => 'data berhasil dihapus', 'data' => $orderItem ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
