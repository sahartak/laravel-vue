<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->integer('user_id');
            $table->integer('amount')->unsigned();
            $table->boolean('is_auto')->default(0);
            $table->boolean('is_last')->default(1);
            $table->timestamps();

            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_bids');
    }
}
