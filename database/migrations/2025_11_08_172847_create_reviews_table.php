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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->morphs('reviewable'); // Product or Vendor
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->integer('rating'); // 1-5
            $table->string('title')->nullable();
            $table->text('comment');
            $table->boolean('is_verified_purchase')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('rating');

            // One review per user per product
            $table->unique(['user_id', 'reviewable_id', 'reviewable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
