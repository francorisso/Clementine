<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Provider;
use App\Models\Product;

class OrderController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $status = $request->input('status', null);
    if (!empty($status)) {
      $orders = Order::where('status', '=', $status)
        ->orderBy('created_at')
        ->get();
    }
    else {
      $orders = Order::all();
    }

    foreach ($orders as $order) {
      $items = OrderItem::where('order_id','=',$order->id)
        ->orderBy('created_at')
        ->get();
      $priceTotal = 0;
      foreach ($items as $item) {
        $priceTotal += ($item->price * $item->quantity);
        $item->product = Product::find($item->product_id);
      }
      $order->priceTotal = $priceTotal;
      $order->items = $items;
      $order->provider = Provider::find($order->provider_id);
      $order->showDetails = false;
    }
    return response()->json($orders);
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
    $providerId = $request->input('provider_id');

    $order = Order::where('provider_id','=',$providerId)
      ->where('status','=','pending')
      ->first();
    if(empty($order)) {
      $order = new Order;
      $order->provider_id = $providerId;
      $order->save();
    }

    return response()->json($order);
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
    $statusNew = $request->input('status', null);

    $order = Order::findOrFail($id);
    switch ($statusNew) {
      case 'pending':
        $order->date_required  = 0;
        $order->date_delivered = 0;
        break;
      case 'required':
        $order->date_required  = date('Y-m-d H:i:s');
        $order->date_delivered = 0;
        break;
      case 'delivered':
        $order->date_delivered = date('Y-m-d H:i:s');
        break;
      case 'canceled':
        $order->date_delivered = date('Y-m-d H:i:s');
        break;
    }
    $order->status = $statusNew;
    $order->save();
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
