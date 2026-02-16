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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id(); 
        $table->string('type');
        $table->string('compte_source')->nullable();
        $table->string('compte_dest')->nullable();
        $table->decimal('montant', 10, 2);
        $table->text('description')->nullable();
        $table->timestamp('date');
        $table->string('statut')->default('validé');
        $table->unsignedBigInteger('user_id');
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
