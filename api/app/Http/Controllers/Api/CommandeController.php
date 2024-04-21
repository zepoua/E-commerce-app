<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Panier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user)
    {
        $commande = Commande::where('user_id', $user)
                        ->get();
        return response()->json($commande);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $quantite = 0;
            $montant = 0;

            $user_id = $request->input('user_id');
            $userStructure = User::where('id', $user_id)->value('structure');
            $panierItems = $request->input('panier');

            foreach ($panierItems as $panierItem) {
                $quantite = $quantite + $panierItem->quantite;
                $montant = $montant + $panierItem->montant;
            }

            $commande = Commande::create([
                'date_commande' => Carbon::now()->format('Y-m-d'),
                'user_id' => $user_id,
                'quantite' => $quantite,
                'montant_commande' => $montant,
                'structure' => $userStructure
            ]);

            foreach ($panierItems as $panierItem) {
                CommandeProduit::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $panierItem->produit_id,
                    'quantite' => $panierItem->quantite,
                    'montant' => $panierItem->montant,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Validation du panier réussie.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Commande $commande)
    {
        return response()->json($commande);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande)
    {
        try {
            $commande->update($request->all());
            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Commande Mise a jour.'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite. Verifiez votre connexion'
            ], 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande)
    {
        try {
            $commande->delete();
            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Commande supprimee.'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite. Verifiez votre connexion'
            ], 201);
        }
    }
}
