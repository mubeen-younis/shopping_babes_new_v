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
        Schema::create('rider_finances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rider_id')->unsigned();
            $table->mediumText('data')->nullable();
            $table->float('net_amount');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('status')->default('not_paid');
            $table->timestamps();
            $table->foreign('rider_id')->references('id')->on('delivery_men');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_finances');
    }
};
