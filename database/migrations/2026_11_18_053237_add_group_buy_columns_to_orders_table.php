<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('group_buy_id')
                ->nullable()
                ->after('total_amount')
                ->constrained('event_group_buys')
                ->nullOnDelete();

            // Snapshots, so records stay correct if the deal is edited or deleted later
            $table->string('group_buy_name')->nullable()->after('group_buy_id');
            $table->unsignedSmallInteger('group_buy_quantity')->nullable()->after('group_buy_name');
            $table->decimal('group_buy_discount', 10, 2)->nullable()->after('group_buy_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_buy_id');
            $table->dropColumn(['group_buy_name', 'group_buy_quantity', 'group_buy_discount']);
        });
    }
};