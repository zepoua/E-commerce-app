<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Produit::all());
    }

    public function filtre($id) {

        $lastCategoryId = Category::latest('id')->value('id');

        if ($id == $lastCategoryId + 1) {
            $produits = Produit::all();
        } else {
            $produits = Produit::where('category_id', $id)->get();
        }

        return response()->json($produits);
    }

    public function filtre_category(Request $request) {

        $search = $request->input('search');
        $id = $request->input('category_id');

        $produits = Produit::where('category_id', $id)
                            ->where('titre', 'like', '%' . $search . '%')
                            ->get();

        return response()->json($produits);
    }


    public function search_produit(Request $request){

        $search = $request->input('search');

        $produits = Produit::where('titre', 'like', '%' . $search . '%')
                           ->get();
                           
        return response()->json($produits);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator(
            $request->all(),
            [
                'titre' => ['required', 'string'],
                'description' => ['required', 'string'],
                'prix' => ['required', 'double'],
                'quantite' => ['required', 'integer'],
                'image1' => ['required', 'string'],
            ],
            [
                'required' => ':attribute est obligatoire.',
                'double' => ':attribute doit etre un nombre.',
                'integer' => ':attribute doit etre un nombre ou un chiffre.',

            ],
            [
                'titre' => "Le titre",
                'description' => "La description",
                'prix' => "Le prix",
                'quantite' => "La quantite",
                'image1' => "L\'image"
            ]
           );
          try{
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => $validator->errors()->first()], 500);
               }else{
                    Produit::create($request->all());
                    return response()->json([
                        'status' => 'success',
                        'code' => 201,
                        'message' => 'Produit creer.']);
               }
          }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite lors de l\'insertion. Verifiez votre connexion.'], 500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit)
    {
        $produit->load('category');
       return response()->json($produit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit)
    {
        $validator = validator(
            $request->all(),
            [
                'titre' => ['required', 'string'],
                'description' => ['required', 'string'],
                'prix' => ['required', 'double'],
                'quantite' => ['required', 'integer'],
                'image1' => ['required', 'string'],
            ],
            [
                'required' => ':attribute est obligatoire.',
                'double' => ':attribute doit etre un nombre.',
                'integer' => ':attribute doit etre un nombre ou un chiffre.',

            ],
            [
                'titre' => "Le titre",
                'description' => "La description",
                'prix' => "Le prix",
                'quantite' => "La quantite",
                'image1' => "L\'image"
            ]
           );
          try{
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => $validator->errors()->first()], 500);
               }else{
                    $produit->upadte($request->all());
                    return response()->json([
                        'status' => 'success',
                        'code' => 201,
                        'message' => 'Produit Mise a jour.']);
               }
          }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite lors de la mise a jour. Verifiez votre connexion.'], 500);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        try{
            $produit->delete();
            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Produit Supprime.']);
          }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite lors de la suppression. Verifiez votre connexion.'], 500);
            }
    }
}
