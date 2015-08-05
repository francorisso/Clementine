<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('order', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('provider_id')->unsigned();
      $table->timestamp('date_required');
      $table->timestamp('date_delivered');//or canceled
      $table->timestamp('created_at');
      $table->timestamp('updated_at');
      $table->enum('status',[
        'pending',
        'required',
        'delivered',
        'canceled'
      ])->default('pending');

      $table->foreign('provider_id')
        ->references('id')
        ->on('provider')
          ->onDelete('cascade')
          ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('order');
  }
}
