<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Classes\ProductsExcelReader;
use App\Models\Product;
use App\Models\Price;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
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
}
