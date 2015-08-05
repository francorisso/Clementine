<?php namespace App\Classes;

use App\Models\Product;
use App\Models\Provider;
use App\Models\Price;

class ProductsExcelReader
{
  private $file             = null;
  private $filename         = null;
  private $expression       = null;
  private $namesForMatches  = null;
  private $parserType       = null;
  private $gramsPerUnit     = null;
  private $provider        = null;
  private $columns          = null;
  private $results          = null;

  public function __construct($args)
  {
    foreach ($args as $key=>$value) {
      $this->{$key} = $value;
    }
    $this->readExcel();
  }

  public function importToDatabase()
  {
    $result = $this->result;
    if (empty($result)) {
      $error = "The result set is empty";
      Log::error($error);
      abort(500, $error);
    }
    $productsAdded   = 0;
    $productsUpdated = 0;
    foreach ($result as $row) {
      $provider = Provider::where('name',$row['provider'])
        ->first();
      if (empty($provider)) {
        $provider = new Provider;
        $provider->name = $row['provider'];
        $provider->save();
      }

      $product = Product::where('code',$row['code'])
        ->first();
      if (empty($product)) {
        $product = new Product();
        $product->code = $row['code'];
        $productsAdded++;
      }
      else {
        $productsUpdated++;
      }
      $product->name = $row['name'];
      $product->save();

      $price = new Price;
      $price->provider_id = $provider->id;
      $price->product_id  = $product->id;
      $price->price       = floatval($row['price']);
      $price->save();
    }
    return [
      'added'    => $productsAdded,
      'updated'  => $productsUpdated
    ];
  }

  protected function readExcel()
  {
    if(empty($this->file)) {
      $error = "You should provide a file as argument of ".__CLASS__;
      Log::error($error);
      abort(500, $error);
    }
    $objPHPExcel    = \PHPExcel_IOFactory::load($this->file);
    $sheet          = $objPHPExcel->getSheet(0);
    $highestRow     = $sheet->getHighestRow();
    $highestColumn  = $sheet->getHighestColumn();

    $result = [];
    //NOTE: starts from 2 because 1 is titles
    for ($row = 2; $row <= $highestRow; $row++) {
      $rowData  = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row);
      if(
        empty($rowData[0][$this->columns['name']]) ||
        empty($rowData[0][$this->columns['code']])
      ){
        continue;
      }
      $result[] = $this->parse($rowData[0]);
    }
    $this->result = $result;
  }

  private function parse($row)
  {
    $name = trim(ucfirst(mb_strtolower($row[$this->columns['name']])));
    $res = [
      'code'      => $row[$this->columns['code']],
      'name'      => $name,
      'price'     => $row[$this->columns['price']],
      'provider'  => $row[$this->columns['provider']],
    ];
    return $res;
  }
}