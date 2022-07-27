<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = OrderItem::with('products')->where('order_id',$id)->get();

        return response()->json(['data' => $data]);
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
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer',
            'qty' => 'required'
        ]);
        
        $data = $request->all();
        OrderItem::create($data);
        $orderItem = OrderItem::with('product')
                        ->select('order_items.*', 'users.name')
                        ->join('orders', 'orders.id', '=', 'order_items.order_id')
                        ->join('users', 'users.id', '=', 'orders.user_id')
                        ->get();
        
        return response()->json(['data' => $orderItem]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $orderItem = OrderItem::select('order_items.*')->where('id', '=', $id)->first();
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
    public function update(Request $request, $id)
    {
        $orderItem = OrderItem::find($id);
        $orderItem->order_id = $request->order_id;
        $orderItem->product_id = $request->product_id;
        $orderItem->qty = $request->qty;
        $orderItem->save();

        $orderItem = OrderItem::with('product')
                        ->select('order_items.*', 'users.name')
                        ->join('orders', 'orders.id', '=', 'order_items.order_id')
                        ->join('users', 'users.id', '=', 'orders.user_id')
                        ->get();
        
        return response()->json(['data' => $orderItem]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_id, $id)
    {
        OrderItem::where('order_id', $order_id)->where('id', $id)->delete();
        $orderItem = OrderItem::with('product', 'user')->where('order_id', $order_id)->get();

        return response()->json(['data' => $orderItem]);
    }
}
