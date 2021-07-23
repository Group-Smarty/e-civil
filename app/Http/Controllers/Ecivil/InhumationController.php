<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Inhumation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InhumationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $listeDeces = DB::table('decedes')->select('decedes.numero_acte_deces','decedes.id',DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))->Where('deleted_at', NULL)->orderBy('id', 'desc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de permis d'inhumation";
       $btnModalAjout = "TRUE";
       return view('ecivil.inhumation.index',compact('fonctions','listeDeces', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
   
    public function listeInhumations()
    {
        $inhumations = Inhumation::with('fonction')
                ->Where('inhumations.deleted_at', NULL) 
                ->select('inhumations.*',DB::raw('DATE_FORMAT(inhumations.date_inhumation, "%d-%m-%Y %H:%i") as date_inhumations'),DB::raw('DATE_FORMAT(inhumations.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(inhumations.date_obseque, "%d-%m-%Y %H:%i") as date_obseques'),DB::raw('DATE_FORMAT(inhumations.date_demande_permis, "%d-%m-%Y %H:%i") as date_demande_permiss'))
                ->orderBy('inhumations.id', 'DESC')
                ->get();
       $jsonData["rows"] = $inhumations->toArray();
       $jsonData["total"] = $inhumations->count();
       return response()->json($jsonData);
    }
    
    public function listeInhumationsByName($name){
        $inhumations = Inhumation::with('fonction')
                ->Where([['inhumations.deleted_at', NULL],['inhumations.nom_complet_demandeur','like','%'.$name.'%']]) 
                ->orWhere([['inhumations.deleted_at', NULL],['inhumations.nom_complet_defunt','like','%'.$name.'%']]) 
                ->select('inhumations.*',DB::raw('DATE_FORMAT(inhumations.date_inhumation, "%d-%m-%Y %H:%i") as date_inhumations'),DB::raw('DATE_FORMAT(inhumations.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(inhumations.date_obseque, "%d-%m-%Y %H:%i") as date_obseques'),DB::raw('DATE_FORMAT(inhumations.date_demande_permis, "%d-%m-%Y %H:%i") as date_demande_permiss'))
                ->orderBy('inhumations.id', 'DESC')
                ->get();
       $jsonData["rows"] = $inhumations->toArray();
       $jsonData["total"] = $inhumations->count();
       return response()->json($jsonData);
    }
    
    public function listeInhumationsByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $inhumations = Inhumation::with('fonction')
                ->Where('inhumations.deleted_at', NULL) 
                ->whereDate('inhumations.date_inhumation','=', $date)
                ->orWhereDate('inhumations.date_deces','=', $date)
                ->orWhereDate('inhumations.date_demande_permis','=', $date)
                ->select('inhumations.*',DB::raw('DATE_FORMAT(inhumations.date_inhumation, "%d-%m-%Y %H:%i") as date_inhumations'),DB::raw('DATE_FORMAT(inhumations.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(inhumations.date_obseque, "%d-%m-%Y %H:%i") as date_obseques'),DB::raw('DATE_FORMAT(inhumations.date_demande_permis, "%d-%m-%Y %H:%i") as date_demande_permiss'))
                ->orderBy('inhumations.id', 'DESC')
                ->get();
        $jsonData["rows"] = $inhumations->toArray();
        $jsonData["total"] = $inhumations->count();
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
        if ($request->isMethod('post') && $request->input('nom_complet_defunt')) {
            $data = $request->all(); 
            
            try{
                //La date du décès doit etre avant la date d'inhumation
                if(Carbon::createFromFormat('d-m-Y', $data['date_deces']) > Carbon::createFromFormat('d-m-Y H:i', $data['date_inhumation'])){
                    throw new Exception("La date du décès ne peut pas être avant après l'inhumation vérifier ces deux dates");
                }
            
                //Verification de la disponibilité du document scnanner
                if(!isset($data['scanne_pv_ou_certificat_deces']) && empty($data['scanne_pv_ou_certificat_deces'])){
                    throw new Exception("Il faut ajouter le PV ou le certificat du décès délivré par le médecin");
                }
                
                //Le numéro d'acte de naissance ou de la pièce d'identité est necessaire
                if(empty($data['numero_piece_defunt']) && empty($data['numero_acte_naissance_defunt'])){
                    throw new Exception("Veillez fournir le numéro de l'acte du defunt ou celui de sa pièce d'identité");
                }
                
                //Enregistrement du l'inhumation
                $inhumation = new Inhumation; 
                $inhumation->date_demande_permis = now();
                $inhumation->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $inhumation->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur'] : null;
                $inhumation->adresse_demandeur = $data['adresse_demandeur'];
                $inhumation->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur'] : null;
                $inhumation->nom_complet_defunt = $data['nom_complet_defunt'];
                $inhumation->adresse_defunt = $data['adresse_defunt'];
                $inhumation->montant = $data['montant'];
                $inhumation->lieu_deces = $data['lieu_deces'];
                $inhumation->lieu_inhumation = $data['lieu_inhumation'];
                $inhumation->fonction_id = $data['fonction_id'];
                $inhumation->date_deces = Carbon::createFromFormat('d-m-Y', $data['date_deces']);
                $inhumation->date_inhumation = Carbon::createFromFormat('d-m-Y H:i', $data['date_inhumation']);
                $inhumation->numero_piece_defunt = isset($data['numero_piece_defunt']) && !empty($data['numero_piece_defunt']) ? $data['numero_piece_defunt'] : Null;
                $inhumation->numero_acte_naissance_defunt = isset($data['numero_acte_naissance_defunt']) && !empty($data['numero_acte_naissance_defunt']) ? $data['numero_acte_naissance_defunt'] : Null;
                $inhumation->deces_id = isset($data['deces_id']) && !empty($data['deces_id']) ? $data['deces_id'] : Null;
                $inhumation->inhumer_chez_lui = isset($data['inhumer_chez_lui']) && !empty($data['inhumer_chez_lui']) ? TRUE : FALSE;
                //Ajout du scanne du PV ou du certificat de décès
                if(isset($data['scanne_pv_ou_certificat_deces']) && !empty($data['scanne_pv_ou_certificat_deces'])){
                    $scanne_pv_ou_certificat_deces = request()->file('scanne_pv_ou_certificat_deces');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$scanne_pv_ou_certificat_deces->getClientOriginalName()));
                    $path = public_path().'/documents/pv_medecin/';
                    $scanne_pv_ou_certificat_deces->move($path,$file_name);
                    $inhumation->scanne_pv_ou_certificat_deces = 'documents/pv_medecin/'.$file_name;
                }
                $inhumation->created_by = Auth::user()->id;
                $inhumation->save();
                $jsonData["data"] = json_decode($inhumation);
                return response()->json($jsonData);
            }catch(Exception $exc){
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
     * @param  \App\Inhumation  $inhumation
     * @return Response
     */
    public function updateInhumation(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        $inhumation = Inhumation::find($request->get('idInhumation'));
        if($inhumation){
            $data = $request->all();
            try{
                
                //La date du décès doit etre avant la date d'inhumation
                if(Carbon::createFromFormat('d-m-Y', $data['date_deces']) > Carbon::createFromFormat('d-m-Y H:i', $data['date_inhumation'])){
                    throw new Exception("La date du décès ne peut pas être avant après l'inhumation vérifier ces deux dates");
                }
                
                //Le numéro d'acte de naissance ou de la pièce d'identité est necessaire
                if(empty($data['numero_piece_defunt']) && empty($data['numero_acte_naissance_defunt'])){
                    throw new Exception("Veillez fournir le numéro de l'acte du defunt ou celui de sa pièce d'identité");
                }
                
                $inhumation->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $inhumation->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur'] : null;
                $inhumation->adresse_demandeur = $data['adresse_demandeur'];
                $inhumation->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur'] : null;
                $inhumation->nom_complet_defunt = $data['nom_complet_defunt'];
                $inhumation->adresse_defunt = $data['adresse_defunt'];
                $inhumation->montant = $data['montant'];
                $inhumation->lieu_deces = $data['lieu_deces'];
                $inhumation->lieu_inhumation = $data['lieu_inhumation'];
                $inhumation->fonction_id = $data['fonction_id'];
                $inhumation->date_deces = Carbon::createFromFormat('d-m-Y', $data['date_deces']);
                $inhumation->date_inhumation = Carbon::createFromFormat('d-m-Y H:i', $data['date_inhumation']);
                $inhumation->date_obseque = isset($data['date_obseque']) && !empty($data['date_obseque']) ? Carbon::createFromFormat('d-m-Y H:i', $data['date_obseque']): Null;
                $inhumation->numero_piece_defunt = isset($data['numero_piece_defunt']) && !empty($data['numero_piece_defunt']) ? $data['numero_piece_defunt'] : Null;
                $inhumation->numero_acte_naissance_defunt = isset($data['numero_acte_naissance_defunt']) && !empty($data['numero_acte_naissance_defunt']) ? $data['numero_acte_naissance_defunt'] : Null;
                $inhumation->deces_id = isset($data['deces_id']) && !empty($data['deces_id']) ? $data['deces_id'] : Null;
                $inhumation->inhumer_chez_lui = isset($data['inhumer_chez_lui']) && !empty($data['inhumer_chez_lui']) ? TRUE : FALSE;
                //Ajout du scanne du PV ou du certificat de décès
                if(isset($data['scanne_pv_ou_certificat_deces']) && !empty($data['scanne_pv_ou_certificat_deces'])){
                    $scanne_pv_ou_certificat_deces = request()->file('scanne_pv_ou_certificat_deces');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$scanne_pv_ou_certificat_deces->getClientOriginalName()));
                    $path = public_path().'/documents/pv_medecin/';
                    $scanne_pv_ou_certificat_deces->move($path,$file_name);
                    $inhumation->scanne_pv_ou_certificat_deces = 'documents/pv_medecin/'.$file_name;
                }
                
                $inhumation->updated_by = Auth::user()->id;
                $inhumation->save();
                $jsonData["data"] = json_decode($inhumation);
                return response()->json($jsonData);
            }catch(Exception $exc) {
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
     * @param  \App\Inhumation  $inhumation
     * @return Response
     */
    public function destroy(Inhumation $inhumation)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($inhumation){
                try {
                    $inhumation->update(['deleted_by' => Auth::user()->id]);
                    $inhumation->delete();
                    $jsonData["data"] = json_decode($inhumation);
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
