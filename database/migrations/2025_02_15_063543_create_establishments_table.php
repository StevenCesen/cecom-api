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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('nro_estab');
            $table->string('name');
            $table->bigInteger('nro_invoices');
            $table->bigInteger('nro_liquidations');
            $table->bigInteger('nro_credit_note');
            $table->bigInteger('nro_debit_note');
            $table->bigInteger('nro_guides');
            $table->bigInteger('nro_retains');
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
        Schema::dropIfExists('establishments');
    }
};
