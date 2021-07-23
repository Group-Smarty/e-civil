<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Billetage;
use App\Models\Taxes\Caisse;
use App\Models\Taxes\CaisseOuverte;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\json_decode;
use function now;
use function response;

class CaisseOuverteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function listeCaisseOuverte()
    {
        
    }
    
    public function getCaisseOuverte($caisse){
        $caisses = CaisseOuverte::join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                                    ->Where([['caisse_ouvertes.deleted_at', NULL],['caisse_ouvertes.caisse_id',$caisse],['caisse_ouvertes.date_fermeture',null]])
                                    ->select('caisse_ouvertes.*')
                                    ->get();
        
       $jsonData["rows"] = $caisses->toArray();
       $jsonData["total"] = $caisses->count();
       return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($request->isMethod('post') && $request->input('caisse_id')) {
                $data = $request->all(); 
            try {
                //Si la caisse est déjà ouverte ou n'existe pas
                $Caisse = Caisse::find($data['caisse_id']);
                if($Caisse->ouvert==1 or !$Caisse){
                    return response()->json(["code" => 0, "msg" => "Cette caisse est déjà ouverte ou n'existe pas", "data" => NULL]);
                }
                //Si la personne à déjà fait une ouverture de caisse sans la fermer
                $caisse_ouverte_sans_fermee = CaisseOuverte::where([['caisse_id',$data['caisse_id']],['user_id',Auth::user()->id],['date_fermeture',null]])->first();
                if($caisse_ouverte_sans_fermee!=null){
                    return response()->json(["code" => 0, "msg" => "Vous avez une session ouverte sur cette caisse.", "data" => NULL]);
                }
                
                //Mise à jour
                $Caisse->ouvert = TRUE;
                $Caisse->updated_by = Auth::user()->id;
                $Caisse->save();
                
                $caisseOuverte = new CaisseOuverte;
                $caisseOuverte->montant_ouverture = $data['montant_ouverture'];
                $caisseOuverte->date_ouverture = now();
                $caisseOuverte->caisse_id = $data['caisse_id'];
                $caisseOuverte->user_id = Auth::user()->id;
                $caisseOuverte->created_by = Auth::user()->id;
                $caisseOuverte->save();
                
                //Stockage en session
                $request->session()->put('session_caisse_ouverte',$caisseOuverte->id);
                $jsonData["data"] = json_decode($caisseOuverte);
                return response()->json($jsonData);

            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CaisseOuverte  $caisseOuverte
     * @return Response
     */
    public function femetureCaisse(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
        $caisseOuverte = CaisseOuverte::where([['caisse_id',$request->caisses_fermeture],['date_fermeture',null]])->first();
        if($caisseOuverte){
            try {
                //On récupere la caisse pour fermer
                $caisse = Caisse::find($request->caisses_fermeture);

                if($caisse->ouvert==0 or !$caisse){
                    return response()->json(["code" => 0, "msg" => "Cette caisse est déjà fermée", "data" => NULL]);
                }
                $data = $request->all();
                //Controle du billetage
                if(empty($data["panierBillet"])){
                    return response()->json(["code" => 0, "msg" => "Veillez remplir le billetage svp!", "data" => NULL]);
                }
                //Recuperation du montant total du billetage
                $billetageContent = is_array($data["panierBillet"]) ? $data["panierBillet"] : array($data["panierBillet"]);
                $montantBilletage = 0;
                foreach($billetageContent as $index => $billetage) {
                    $montantBilletage = $montantBilletage + $data["panierBillet"][$index]["billets"]*$data["panierBillet"][$index]["quantite_billets"];
                }
                
                if($montantBilletage!=$request->get('solde_fermeture') &&  empty($data["motif_non_conformite"])){
                    return response()->json(["code" => 0, "msg" => "Le montant du billetage ne correspond pas au solde de la caisse!", "data" => NULL]);
                }
                
                //Si tout se passe bien
                foreach($billetageContent as $index => $billetage) {
                    $Billetage = new Billetage;
                    $Billetage->billet = $data["panierBillet"][$index]["billets"];
                    $Billetage->quantite = $data["panierBillet"][$index]["quantite_billets"];
                    $Billetage->caisse_ouverte_id = $caisseOuverte->id;
                    $Billetage->created_by = Auth::user()->id;
                    $Billetage->save();
                }
                //Mise à jour
                $caisse->ouvert = FALSE;
                $caisse->updated_by = Auth::user()->id;
                $caisse->save();
                
                //Mise à jour caisse ouverte 
                $caisseOuverte->solde_fermeture = $request->get('solde_fermeture');
                $caisseOuverte->date_fermeture = now();
                $caisseOuverte->updated_by = Auth::user()->id;
                $caisseOuverte->save();
                
                //Destruction de la session de caisse ouverte
                if($request->session()->has('session_caisse_ouverte')){
                    $request->session()->forget('session_caisse_ouverte');
                }
                
                $jsonData["data"] = json_decode($caisseOuverte);
                return response()->json($jsonData);
            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            } 
        }
        return response()->json(["code" => 0, "msg" => "Echec de fermeture", "data" => NULL]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CaisseOuverte  $caisseOuverte
     * @return Response
     */
    public function edit(CaisseOuverte $caisseOuverte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\CaisseOuverte  $caisseOuverte
     * @return Response
     */
    public function update(Request $request, CaisseOuverte $caisseOuverte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CaisseOuverte  $caisseOuverte
     * @return Response
     */
    public function destroy(CaisseOuverte $caisseOuverte)
    {
        //
    }
}
