<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderItem extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_item', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->boolean('delivered')->default(0);
			$table->decimal('price', 9, 2);
			$table->integer('quantity');
			$table->timestamp('created_at');
			$table->timestamp('updated_at');

			$table->foreign('product_id')
        ->references('id')
        ->on('product')
          ->onDelete('cascade')
          ->onUpdate('cascade');

      $table->foreign('order_id')
        ->references('id')
        ->on('order')
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
		Schema::drop('order_item');
	}
}
