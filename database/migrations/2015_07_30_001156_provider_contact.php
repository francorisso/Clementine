<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProviderContact extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('provider_contact', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('provider_id')->unsigned();
      $table->string('media');
      $table->string('value');
      $table->boolean('is_default')->default(0);
      $table->timestamp('created_at');
      $table->timestamp('updated_at');

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
    //
  }
}
