<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Price extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('price', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('provider_id')->unsigned();
      $table->integer('product_id')->unsigned();
      $table->decimal('price',9,2);
      $table->decimal('price_sale',9,2);
      $table->timestamp('created_at');
      $table->timestamp('updated_at');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('price');
  }
}
