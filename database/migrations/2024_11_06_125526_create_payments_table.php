<?php

use App\Enums\PaymentStatus;
use App\Models\Order;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', PaymentStatus::values())->default(PaymentStatus::IN_PROGRESS->value);
            $table->tinyInteger('error_code')->nullable();
            $table->string('error_description')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->timestamps();

            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
