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
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->string('sku')->unique();
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'active',
                'inactive'
            ])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->integer('stock_quantity')->default(0);
            $table->integer('views_count')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete support

            // Indexes for fast queries
            $table->index('vendor_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('created_at');

            // Full-text search
            $table->fullText(['name', 'description']);
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
