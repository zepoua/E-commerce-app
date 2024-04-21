<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'quantite',
        'prix',
        'image1',
        'image2',
        'image3',
    ];

    public function paniers(){

        return $this->hasMany(Panier::class);
    }

    public function commandes(){

        return $this->hasMany(Commande::class);
    }

    public function category(){

        return $this->belongsTo(Category::class);
    }

    public function commande_produits(){

        return $this->hasMany(CommandeProduit:: class);
    }
}
