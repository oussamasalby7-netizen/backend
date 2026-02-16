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
    Schema::create('stats', function (Blueprint $table) {
        $table->id(); // AUTO
        $table->unsignedBigInteger('user_id');
        $table->json('favori'); // {compte_dest, total}
        $table->json('contact_frequent'); // object
        $table->json('total_transactions'); // object
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
