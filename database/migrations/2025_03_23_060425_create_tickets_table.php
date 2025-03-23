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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->date('date_create');
            $table->date('date_finish');
            $table->string('last_interaction')->nullable();
            $table->string('status');
            $table->string('stars')->nullable();
            $table->string('comment')->nullable();
            $table->string('ride_path')->nullable();
            $table->string('xml_path')->nullable();
            $table->foreignId('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onUpdate('cascade');
            $table->foreignId('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
            $table->foreignId('contributor_id');
            $table->foreign('contributor_id')
                ->references('id')
                ->on('contributors')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
