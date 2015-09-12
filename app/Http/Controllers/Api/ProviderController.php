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
use App\Models\Price;

class ProviderController extends Controller
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


  //TODO: replace this with a trait
  private function stats(Request $request)
  {
    $metric = $request->input('metric', null);

    $response = [];
    switch ($metric) {
      case self::METRIC_SALES:
        $response = $this->sales($request);
        break;
    }

    return response()->json($response);
  }

  private function sales($request)
  {
    //TODO: make this last year from now, not current year
    $year       = $request->input('year', date('Y'));
    $graphType  = $request->input('graphType', null);
    $timeRange  = $request->input('time-range', 'year');
    $perPage    = $request->input('per-page', 5);
    $page       = $request->input('page', 0);
    $providerId = $request->input('product-id', 0);
    $offset     = $page * $perPage;

    $timeframeLimit = 6;
    $timeframeUnit = 'months';
    $timeframe = date('Y-m-d',strtotime('-'.$timeframeLimit.' '.$timeframeUnit));

    $providers = Order::select(
        DB::raw('provider_id, provider.name, sum(price*quantity) as value')
      )
      ->join('order_item','order_item.order_id','=','order.id')
      ->leftJoin('provider', 'provider.id', '=', 'order.provider_id')
      ->where('order.date_required', '>=', $timeframe)
      ->where('order.status', '!=', 'canceled')
      ->groupBy('provider_id')
      ->skip($offset)
      ->take($perPage);
    switch ($graphType) {
      case 'last':
        $providers
          ->orderBy('value','asc');
        break;
      case 'top':
        $providers
          ->orderBy('value','desc');
        break;
      default:
        die;
    }
    $providers = $providers->get();
    //TODO: I guess yearly resumes will have to change this...
    $month    = intval(date('m', strtotime($timeframe)));
    $year     = intval(date('Y', strtotime($timeframe)));
    $today    = [
      'year'  => date('Y'),
      'month' => intval(date('m')),
    ];
    while (
      $year<$today['year'] ||
      ($year==$today['year'] && $month<=$today['month'])
    ) {
      $date = $year . '-' . ($month<10? '0' : '') . $month . '-%';
      $row  = [$month . '/' . $year];
      $month++;
      if ($month>12) {
        $month = 1;
        $year++;
      }
      foreach ($providers as $provider) {
        $result = Order::select(DB::raw('sum(price * quantity) as value'))
          ->join('order_item','order_item.order_id','=','order.id')
          ->where('order.provider_id', '=', $provider->provider_id)
          ->where('order.date_required', 'LIKE', $date)
          ->where('order.status', '!=', 'canceled')
          ->first();
        $row[]  = (empty($result)? 0 : intval($result->value));
      }
      $rows[] = $row;
    }
    $columns   = [];
    $columns[] = [
      'name'     => 'Fecha',
      'dataType' => 'string'
    ];
    foreach ($providers as $provider) {
      $columns[] = [
        'name'     => $provider->name,
        'dataType' => 'number'
      ];
    }
    $title = $request->input('title',false);
    $response = [
      'title'       => $title,
      'vAxisTitle'  => '$',
      'hAxisTitle'  => 'Fecha',
      'rows'        => $rows,
      'columns'     => $columns,
    ];
    return $response;
  }
}
