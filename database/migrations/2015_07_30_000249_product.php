<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('name_url');
      $table->enum('unit',['kg','gr','units'])->default('kg');
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
    //
  }
}
