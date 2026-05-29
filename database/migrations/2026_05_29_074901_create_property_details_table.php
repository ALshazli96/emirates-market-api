<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->enum('property_type', ['apartment','villa','studio','land','office','shop']);
            $table->enum('operation_type', ['sale','rent']);
            $table->decimal('area_sqft', 10, 2)->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('floor')->nullable();
            $table->integer('total_floors')->nullable();
            $table->enum('furnished', ['furnished','semi','unfurnished'])
                  ->default('unfurnished');
            $table->string('agent_name')->nullable();
            $table->string('permit_number')->nullable();
            $table->decimal('yearly_price', 15, 2)->nullable();
            $table->decimal('roi_percentage', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_details');
    }
};