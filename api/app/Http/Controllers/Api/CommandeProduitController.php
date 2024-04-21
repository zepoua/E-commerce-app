<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommandeProduit;
use Illuminate\Http\Request;

class CommandeProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($commande_id)
    {
        $details = CommandeProduit::where('commande_id', $commande_id)
                                ->with(['commande' => function ($query) {
            $query->select('id', 'montant_commande', 'montant_paye');}])
                                ->with(['produit' => function ($query1) {
            $query1->select('id', 'image1', 'titre');}])
                                ->get();
        return response()->json($details);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CommandeProduit $commandeProduit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommandeProduit $commandeProduit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommandeProduit $commandeProduit)
    {
        //
    }
}
