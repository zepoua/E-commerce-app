<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_verification(Request $request){
        $validator = validator(
            $request->all(),
            [
                'name' => ['required', 'string'],
                'structure' => ['required', 'string'],
                'email' => ['required', 'email', 'unique:users,email'],
                'telephone' => ['required', 'numeric', 'digits:8', 'unique:users,telephone'],
            ],
            [
                'required' => ':attribute est obligatoire.',
                'unique' => ':attribute existe déjà.',
                'numeric' => ':attribute doit être que des chiffres.',
                'digits' => ':attribute doit être de 8 chiffres.',
                'email.email' => 'L\'adresse email doit être une adresse email.',
            ],
            [
                'name' => "Le nom",
                'structure' => "Le nom de la structure",
                'email' => "L'adresse mail",
                'telephone' => "Le numéro de téléphone",
            ]
           );
          try{
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => $validator->errors()->first()], 500);
               }else{

                    $coderand = rand(100000, 999999);

                        $parametre = [
                            'username'=>'tikegne',
                            'password'=>'Tikegne2@21',
                            'destination'=>'228'.$request->telephone,
                            'source'=>'ECOMMERCE',
                            'message'=>'Votre code de confirmation de votre inscription sur l\'application ecommerce est : '.$coderand.'.'];
                        $reponse_sms = Http::get('http://sendsms.e-mobiletech.com/',$parametre);

                        $new_code = new Code;
                        $new_code->code = $coderand;
                        $new_code->telephone = $request->input('telephone');
                        $new_code->save();

                        return response()->json([
                            'status' => 'success',
                            'code' => 201,
                            'message' => 'Code de confirmation d\'inscription envoyé.']);
               }
          }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite lors de l\'envoi du code de confirmation. Verifiez votre connexion.'], 500);
            }
    }

    public function store(Request $request)
    {
        try {
            $validator = validator(
                $request->all(),
                [
                    'code' => ['required', 'numeric', 'digits:6'],
                ],
                [
                    'required' => ':attribute est obligatoire.',
                    'numeric' => ':attribute doit être que des chiffres.',
                    'digits' => ':attribute doit être de 6 chiffres.',
                ],
                [
                    'code' => "Le code de confirmation",
                ]
               );
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $th->getMessage()], 500);        }

      try{
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $validator->errors()->first()], 500);
        }else{
                $telephone = $request->input('telephone');
                $coderand = $request->input('code');
                $code = Code::where('telephone', $telephone)->value('code');

                if ($coderand == $code) {
                    $userData = $request->except('code');
                    $user = User::create($userData);
                    return response()->json([
                        'status' => 'success',
                        'code' => 201,
                        'message' => 'Compte utilisateur créé avec succès.',
                        'user_id' => $user->id], 201);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'code' => 500,
                        'message' => 'Code de Confirmation non valide.'], 500);
                }
           }
      }catch (\Throwable $th) {
        return response()->json([
            'status' => 'error',
            'code' => 500,
            'message' => 'Erreur lors de l\'inscription. Verifiez votre connexion.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = validator(
            $request->all(),
            [
                'name' => ['required', 'string'],
                'structure' => ['required', 'string'],
                'email' => ['required', 'email',],
                'telephone' => ['required', 'numeric', 'digits:8',],
            ],
            [
                'required' => ':attribute est obligatoire.',
                'unique' => ':attribute existe déjà.',
                'numeric' => ':attribute doit être que des chiffres.',
                'digits' => ':attribute doit être de 8 chiffres.',
                'email.email' => 'L\'adresse email doit être une adresse email.',
            ],
            [
                'email' => "L'adresse mail",
                'telephone' => "Le numéro de téléphone",
                'name' => "Le nom",
                'structure' => "Le nom de la structure",

            ]
           );

          try{
            if($validator->fails()){
                return response()->json([
                    'status' => 'failed',
                    'code' => 500,
                    'message' => $validator->errors()->first()
                ]);
               }else{
                    $user->update($request->all());
                    return response()->json([
                        'status' => 'success',
                        'code' => 201,
                        'message' => 'Votre compte a été mise à jour avec succès.'], 201);
               }
          }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite lors de la mise à jour. Verifiez votre connexion.'], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['Compte supprime avec succès']);
    }

    public function login_code(Request $request)
    {
        $validator = validator(
        $request->all(),
        [
            'telephone' => ['required', 'numeric', 'digits:8'],
        ],
        [
            'required' => ':attribute est obligatoire.',
            'numeric' => ':attribute doit être que des chiffres.',
            'digits' => ':attribute doit être de 8 chiffres.',
        ],
        [

            'telephone' => "Le numero de telephone"
        ]
        );

        try{
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => $validator->errors()->first()], 500);
                }else{

                    $telephone = $request->input('telephone');
                    $telephone = Code::where('telephone', $telephone)->value('telephone');

                    if ($telephone) {
                        $coderand = rand(100000, 999999);
                        $parametre = [
                            'username'=>'tikegne',
                            'password'=>'Tikegne2@21',
                            'destination'=>'228'.$request->telephone,
                            'source'=>'ECOMMERCE',
                            'message'=>'Votre code de connexion sur l\'application ecommerce est : '.$coderand.'.'];
                        $reponse_sms = Http::get('http://sendsms.e-mobiletech.com/',$parametre);

                        DB::table('codes')->where('telephone', $telephone)->update(['code' => $coderand]);

                    return response()->json([
                        'status' => 'success',
                        'code' => 201,
                        'message' => 'Code de Connexion envoyé.'], 201);

                }else
                    return response()->json([
                        'status' => 'error',
                        'code' => 500,
                        'message' => 'Numéro de téléphone invalide.'], 500);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite lors de l\'envoi du code de connexion. Verifiez votre connexion.'],500);
        }

    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'code' => ['required', 'numeric', 'digits:6'],
            ], [
                'required' => ':attribute est obligatoire.',
                'numeric' => ':attribute doit être que des chiffres.',
                'digits' => ':attribute doit être de 6 chiffres.',
            ], [
                'code' => 'Le code de confirmation',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }

        try {
            $telephone = $request->input('telephone');
            $coderand = $request->input('code');
            $code = Code::where('telephone', $telephone)->value('code');

            if ($coderand == $code) {
                    $user = User::where('telephone', $telephone)->first();
                    return response()->json([
                        'status' => 'success',
                        'code' => 201,
                        'message' => 'Heureux de vous revoir.',
                        'data' => $user
                    ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'Code de Confirmation non valide.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Une erreur s\'est produite lors de la connexion. Verifiez votre connexion.'], 500);
        }
    }
}
