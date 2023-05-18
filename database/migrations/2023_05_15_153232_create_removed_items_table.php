<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemovedItemsTable extends Migration
{
    public function up()
    {
        Schema::create('removed_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamp('removed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('product_id');
            $table->index('cart_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('removed_items');
    }
}