<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_commande',
        'quantite',
        'montant_commande',
        'montant_paye',
        'user_id',
        'etat_livraison',
        'etat_payement'
    ];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function commande_produits(){

        return $this->hasMany(CommandeProduit:: class);
    }
}
