<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('floor');
            $table->string('table');
            $table->string('create_date');
            $table->string('details');
            $table->string('notes')->nullable();
            $table->string('status');
            $table->string('client_name');
            $table->string('client_identification')->nullable();
            $table->string('order_number_day');
            $table->foreignId('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('contributor_id');
            $table->foreign('contributor_id')
                ->references('id')
                ->on('contributors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
