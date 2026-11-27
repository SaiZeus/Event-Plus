<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_group_buys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->unsignedSmallInteger('quantity');            // must be 2 or more (validated in controller)
            $table->decimal('discount_amount', 10, 2);           // fixed amount off the group total
            $table->string('ticket_scope')->default('same_category'); // same_category | selected_categories
            $table->json('allowed_ticket_category_ids')->nullable();  // used when selected_categories
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_group_buys');
    }
};