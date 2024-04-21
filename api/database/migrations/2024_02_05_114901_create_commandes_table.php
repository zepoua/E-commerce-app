<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->date('date_commande');
            $table->integer('quantite');
            $table->double('montant_commande');
            $table->double('montant_paye')->default(0);
            $table->string('etat_livraison')->default('En cours');
            $table->string('etat_payement')->default('Non solde');
            $table->string('structure');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('commandes', function (Blueprint $table){
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('commandes');
    }
};
