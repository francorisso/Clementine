<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;


class UserController extends Controller
{
  const TIMERANGE_YEAR  = 'year';
  const TIMERANGE_MONTH = 'month';
  const TIMERANGE_DAY   = 'day';

  const METRIC_SALES            = 'sales';
  const METRIC_PRICE_EVOLUTION  = 'price_evolution';
  const METRIC_PROVIDERS        = 'providers';

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $is_stat = $request->input('is_stat',false);
    if ($is_stat) {
      return $this->stats($request);
    }

    $providers = Provider::orderBy('name')->get();
    foreach ($providers as $provider) {
      $provider->products = \DB::table('price')
        ->join('product', 'product.id', '=', 'price.product_id')
        ->where('price.provider_id', $provider->id)
        ->groupBy('price.product_id')
        ->select('product.id', 'product.name')
        ->get();

      $productsCount = \DB::table('price')
        ->where('price.provider_id', $provider->id)
        ->groupBy('price.product_id')
        ->get();
      $provider->productsCount = count($productsCount);
      foreach ($provider->products as $product) {
        $price = Price::where('product_id','=',$product->id)
          ->where('provider_id', '=', $provider->id)
          ->orderBy('created_at','desc')
          ->first();
        $product->price = $price->price;
      }
    }
    return response()->json($providers);
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
