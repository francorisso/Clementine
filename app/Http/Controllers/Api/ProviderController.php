<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Classes\ProductsExcelReader;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Order;

class ProviderController extends Controller
{
  const TIMERANGE_YEAR  = 'year';
  const TIMERANGE_MONTH = 'month';
  const TIMERANGE_DAY   = 'day';

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

    $proveedores = Provider::orderBy('nombre')->get();
    return response()->json($proveedores);
  }

  private function stats(Request $request)
  {
    $timeRange  = $request->input('time-range', 'year');
    $perPage    = $request->input('per-page', 10);
    $page       = $request->input('page', 0);
    $providerId = $request->input('provider-id', 0);
    $offset     = $page * $perPage;

    $rows = [];
    switch ($timeRange) {
      case self::TIMERANGE_YEAR:
        $response = $this->statsYearly($request);
        break;
    }

    return response()->json($response);
  }

  private function statsYearly($request)
  {
    $year   = $request->input('year', date('Y'));
    $metric = $request->input('metric', 'quantity');

    $providers = Order::join('order_item','order_item.order_id','=','order.id')
      ->leftJoin('provider', 'provider.id', '=', 'order.provider_id')
      ->where('order.date_required', 'LIKE', $year.'-%')
      ->where('order.status', '!=', 'canceled')
      ->groupBy('provider_id')
      ->orderBy('value','desc')
      ->skip($offset)
      ->take($perPage);
    switch ($metric) {
      case 'quantity':
        $providers
          ->select(DB::raw('provider_id, provider.name, count(*) as value'));
        break;
      case 'amount':
        $providers
          ->select(DB::raw('provider_id, provider.name, sum(price*quantity) as value'));
        break;
    }
    $providers = $providers->get();
    for ($month=1; $month<=12; $month++) {
      $date = $year . '-'
        . ($month<10? '0'.$month : $month)
        . '-%';
      $row = [$month . '/' . $year];
      foreach ($providers as $provider) {
        $result = Order::select(DB::raw('count(*) as quantity'))
          ->join('order_item','order_item.order_id','=','order.id')
          ->where('order.date_required', 'LIKE', $date)
          ->where('order.status', '!=', 'canceled')
          ->where('provider_id', '=', $provider->provider_id)
          ->skip($offset)
          ->take($perPage);
        switch ($metric) {
          case 'quantity':
            $result
              ->select(DB::raw('count(*) as value'));
            break;
          case 'amount':
            $result
              ->select(DB::raw('sum(price*quantity) as value'));
            break;
        }
        $result = $result->first();
        $row[]  = intval($result->value);
      }
      $rows[] = $row;
    }
    $response = [
      'rows' => $rows,
      'providers' => $providers
    ];
    return $response;
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
    $pdf = $request->file('pdf');
    $columns = [
      'code'      => $request->input('codeColumn'),
      'precio'    => $request->input('priceColumn'),
      'nombre'    => $request->input('nameColumn'),
      'proveedor' => $request->input('proveedorColumn'),
    ];
    $productsReader = new ProductsExcelReader([
      'file'      => $pdf,
      'columns'   => $columns,
    ]);
    $result = $productsReader->importToDatabase();

    return \Redirect::to(
      'http://localhost:8080/#/productos'
      . '?nuevos=' . $result['nuevos'] . '&actualizados=' . $result['actualizados']
    );
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
