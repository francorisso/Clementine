<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Price;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  Request  $request
   * @return Response
   */
  public function store(Request $request)
  {
    $orderId   = $request->input('order_id');
    $productId = $request->input('product_id');
    $price     = $request->input('price');
    $quantity  = $request->input('quantity');

    $orderItem = OrderItem::where('product_id','=',$productId)
      ->where('order_id', '=', $orderId)
      ->first();
    if (empty($orderItem)) {
      $orderItem = new OrderItem;
      $orderItem->order_id    = $orderId;
      $orderItem->product_id  = $productId;
      $orderItem->quantity    = $quantity;
    }
    else {
      $orderItem->quantity = $orderItem->quantity + $quantity;
    }
    $orderItem->price = $price;
    $orderItem->save();

    $order = Order::find($orderItem->order_id);

    $price = new Price;
    $price->product_id  = $orderItem->product_id;
    $price->provider_id = $order->provider_id;
    $price->price = $orderItem->price;
    $price->save();

    return response()->json($orderItem);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  Request  $request
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    //
  }
}
