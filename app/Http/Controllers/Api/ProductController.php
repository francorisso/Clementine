<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Classes\ProductsExcelReader;
use App\Models\Product;
use App\Models\Price;
use App\Models\OrderItem;

class ProductController extends Controller
{
  const TIMERANGE_YEAR  = 'year';
  const TIMERANGE_MONTH = 'month';
  const TIMERANGE_DAY   = 'day';

  const METRIC_SALES  = 'sales';
  const METRIC_PRICE_EVOLUTION = 'price_evolution';
  const METRIC_PROVIDERS   = 'providers';

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

    $searchTerm = $request->input('searchTerm', null);
    $limit = $request->input('limit', 10);

    $products = Product::orderBy('name');
    if (!empty($searchTerm)) {
      $products->where('name','LIKE', $searchTerm.'%');
    }
    if (!empty($limit)) {
      $products->limit($limit);
    }
    $products = $products->get();
    foreach ($products as $product) {
      $product->providers = \DB::table('price')
        ->join('provider', 'provider.id', '=', 'price.provider_id')
        ->where('price.product_id', $product->id)
        ->groupBy('price.provider_id')
        ->select('provider.id','provider.name')
        ->get();
      foreach ($product->providers as $provider) {
        $price = Price::where('provider_id','=',$provider->id)
          ->where('product_id', '=', $product->id)
          ->orderBy('created_at','desc')
          ->first();
        $provider->price = $price->price;
      }
    }
    return response()->json($products);
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
      'code'     => $request->input('codeColumn'),
      'price'    => $request->input('priceColumn'),
      'name'     => $request->input('nameColumn'),
      'provider' => $request->input('providerColumn'),
    ];
    $productsReader = new ProductsExcelReader([
      'file'      => $pdf,
      'columns'   => $columns,
    ]);
    $result = $productsReader->importToDatabase();

    return redirect(
      '/#/products'
      . '?added=' . $result['added']
      . '&updated=' . $result['updated']
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
    $metric     = $request->input('metric', null);

    $rows = [];
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
    $graphType  = $request->input('graphType', 'quantity');
    $timeRange  = $request->input('time-range', 'year');
    $perPage    = $request->input('per-page', 5);
    $page       = $request->input('page', 0);
    $providerId = $request->input('product-id', 0);
    $offset     = $page * $perPage;

    $products = OrderItem::select(DB::raw('product_id, product.name, sum(price*quantity) as value'))
      ->join('order','order_item.order_id','=','order.id')
      ->leftJoin('product', 'product.id', '=', 'order_item.product_id')
      ->where('order.date_required', 'LIKE', $year.'-%')
      ->where('order.status', '!=', 'canceled')
      ->groupBy('product_id')
      ->skip($offset)
      ->take($perPage);
    switch ($graphType) {
      case 'last':
        $title = 'Productos menos vendidos';
        $products
          ->orderBy('value','asc');
        break;
      case 'top':
        $title = 'Productos mas vendidos';
        $products
          ->orderBy('value','desc');
        break;
    }
    $products = $products->get();
    for ($month=1; $month<=12; $month++) {
      $date = $year . '-'
        . ($month<10? '0'.$month : $month)
        . '-%';
      $row = [$month . '/' . $year];
      foreach ($products as $product) {
        $result = OrderItem::select(DB::raw('product_id, sum(price*quantity) as value'))
          ->join('order','order_item.order_id','=','order.id')
          ->leftJoin('product', 'product.id', '=', 'order_item.product_id')
          ->where('order_item.product_id', '=', $product->product_id)
          ->where('order.date_required', 'LIKE', $date)
          ->where('order.status', '!=', 'canceled')
          ->groupBy('product_id')
          ->skip($offset)
          ->take($perPage);
        $result = $result->first();
        $row[]  = (empty($result)? 0 : intval($result->value));
      }
      $rows[] = $row;
    }
    $columns = [];
    $columns[] = [
      'name'     => 'Fecha',
      'dataType' => 'string'
    ];
    foreach ($products as $product) {
      $columns[] = [
        'name'     => $product->name,
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
