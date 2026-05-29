<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->string('make');        // تويوتا، BMW
            $table->string('model');       // كامري، X5
            $table->integer('year');
            $table->integer('mileage')->default(0);
            $table->string('color')->nullable();
            $table->enum('fuel_type', ['petrol','diesel','electric','hybrid'])
                  ->default('petrol');
            $table->enum('transmission', ['automatic','manual'])
                  ->default('automatic');
            $table->enum('condition', ['new','used','certified'])
                  ->default('used');
            $table->enum('specs', ['gcc','american','european','other'])
                  ->default('gcc');
            $table->string('body_type')->nullable();
            $table->integer('cylinders')->nullable();
            $table->boolean('finance_available')->default(false);
            $table->boolean('is_documented')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_details');
    }
};