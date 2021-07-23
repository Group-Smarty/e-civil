<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\EnfantEnCharge;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnfantEnChargeController extends Controller
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

    public function listeEnfantsEnCharge($idCertificat)
    {
        $enfants = EnfantEnCharge::where([['enfant_en_charges.deleted_at', NULL],['enfant_en_charges.certificat_vie_entretien_id', $idCertificat]]) 
                    ->select('enfant_en_charges.*',DB::raw('DATE_FORMAT(enfant_en_charges.date_naissance, "%d-%m-%Y") as date_naissances'))
                    ->orderBy('enfant_en_charges.id', 'DESC')
                    ->get();
       $jsonData["rows"] = $enfants->toArray();
       $jsonData["total"] = $enfants->count();
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
        if ($request->isMethod('post') && $request->input('numero_extrait_child')) {
            $data = $request->all(); 
        try{
            
                //On verifie si l'enfant n'est pas deja enregistré 
                $EnfantEnCharge = EnfantEnCharge::where('numero_extrait_enfant', $data['numero_extrait_child'])->first();
                if($EnfantEnCharge!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enfant est enregistré déja, vérifier le numéro de l'acte de naissance", "data" => NULL]);
                }
                
                //Enregistrement 
                $enfantEnCharge = new EnfantEnCharge;
                $enfantEnCharge->nom_complet_enfant = $data['nom_complet_child'];
                $enfantEnCharge->numero_extrait_enfant = $data['numero_extrait_child'];
                $enfantEnCharge->lieu_naissance_enfant = $data['lieu_naissance_child'];
                $enfantEnCharge->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance_child']);
                $enfantEnCharge->certificat_vie_entretien_id = $data['idPapa']; 
                $enfantEnCharge->created_by = Auth::user()->id;
                $enfantEnCharge->save();
               
                $jsonData["data"] = json_decode($enfantEnCharge);
                return response()->json($jsonData);
            }catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\EnfantEnCharge  $enfantEnCharge
     * @return Response
     */
    public function update(Request $request, EnfantEnCharge $enfantEnCharge)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($enfantEnCharge) {
            $data = $request->all(); 
        try{
                $enfantEnCharge->nom_complet_enfant = $data['nom_complet_child'];
                $enfantEnCharge->numero_extrait_enfant = $data['numero_extrait_child'];
                $enfantEnCharge->lieu_naissance_enfant = $data['lieu_naissance_child'];
                $enfantEnCharge->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance_child']);
                $enfantEnCharge->updated_by = Auth::user()->id;
                $enfantEnCharge->save();
                $jsonData["data"] = json_decode($enfantEnCharge);
                return response()->json($jsonData);
            }catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EnfantEnCharge  $enfantEnCharge
     * @return Response
     */
    public function destroy(EnfantEnCharge $enfantEnCharge)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($enfantEnCharge){
                try {
                    $enfantEnCharge->update(['deleted_by' => Auth::user()->id]);
                    $enfantEnCharge->delete();
                    $jsonData["data"] = json_decode($enfantEnCharge);
                    return response()->json($jsonData);
                } catch (Exception $exc) {
                   $jsonData["code"] = -1;
                   $jsonData["data"] = NULL;
                   $jsonData["msg"] = $exc->getMessage();
                   return response()->json($jsonData); 
                }
            }
        return response()->json(["code" => 0, "msg" => "Echec de suppression", "data" => NULL]);
    }
}
