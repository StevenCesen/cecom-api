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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('sequential')->nullable();
            $table->string('access_key')->nullable();
            $table->string('doc_type');
            $table->string('issue_date')->nullable();
            $table->string('create_date');
            $table->float('subtotal_amount');
            $table->float('tax_value');
            $table->float('total_amount');
            $table->string('ride_path')->nullable();
            $table->string('xml_path')->nullable();
            $table->string('status');
            $table->foreignId('contributor_id');
            $table->foreign('contributor_id')
                ->references('id')
                ->on('contributors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
