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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('code_aux')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('tax');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->float('price');
            //  Para el contribuyente que lo creó
            $table->foreignId('contributor_id');
            $table->foreign('contributor_id')
                ->references('id')
                ->on('contributors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            //  Para el tipo de producto
            $table->foreignId('type_id');
            $table->foreign('type_id')
                ->references('id')
                ->on('types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
