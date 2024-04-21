<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Panier;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user)
    {
        $paniers = Panier::where('user_id', $user)
                        ->with('produit') // Charger les informations liées au produit
                        ->get();
        return response()->json($paniers);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = $request->input('user_id');
        $produit_id = $request->input('produit_id');
        $montant = $request->input('montant');
        $quantite = $request->input('quantite');

        try {
            // Vérifier si l'article existe déjà dans le panier de l'utilisateur
            $panierExist = Panier::where('user_id', $user_id)
                                ->where('produit_id', $produit_id)
                                ->first();

            if ($panierExist) {
                // L'article existe déjà, effectuez la mise à jour
                $panierExist->update([
                    'montant' => $montant, // Mise à jour du montant si nécessaire
                    'quantite' => $quantite, // Mise à jour de la quantité si nécessaire
                ]);
                return response()->json([
                    'status' => 'success',
                    'code' => 201,
                    'message' => 'Article mise a jour dans le panier.'
                ], 201);
            } else {
                // L'article n'existe pas encore, effectuez l'insertion
                Panier::create($request->all());
                return response()->json([
                    'status' => 'success',
                    'code' => 201,
                    'message' => 'Article ajouté au panier.'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Panier $panier)
    {
        return response()->json($panier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Panier $panier)
    {
        $panier->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Panier $panier)
    {
        try {
            $panier->delete();
            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Article supprime du panier.'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }

    }
}
