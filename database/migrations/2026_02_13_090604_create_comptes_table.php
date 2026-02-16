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
    Schema::create('comptes', function (Blueprint $table) {
        $table->id();
        $table->string('rib');
        $table->double('solde');
        $table->string('type');


        $table->unsignedBigInteger('banque_id');
        $table->unsignedBigInteger('user_id');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
