<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Classes\ProductsExcelReader;
use App\Models\Product;
use App\Models\Provider;

class ProviderController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $proveedores = Proveedor::orderBy('nombre')->get();
    return response()->json($proveedores);
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
