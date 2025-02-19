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
        Schema::create('contributors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('identification');
            $table->string('direction')->nullable();
            $table->string('commercial_name');
            $table->string('regimen');
            $table->string('phone');
            $table->string('signature_path')->nullable();
            $table->string('x509_serial_number')->nullable();
            $table->string('x509_der_hash')->nullable();
            $table->string('exponent')->nullable();
            $table->string('module')->nullable();
            $table->string('issuer_name')->nullable();
            $table->string('validity_date')->nullable();
            $table->string('x509_self')->nullable();
            $table->text('certificate')->nullable();
            $table->integer('user_limit');
            $table->integer('doc_limit');
            $table->integer('estab_limit');
            $table->string('logo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributors');
    }
};
