<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Contribuable;
use App\Models\Taxes\DeclarationActivite;
use App\Models\Taxes\PayementTaxe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContribuableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $nations = DB::table('nations')->Where('deleted_at', NULL)->orderBy('libelle_nation', 'asc')->get();
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $communes = DB::table('communes')->Where('deleted_at', NULL)->orderBy('libelle_commune', 'asc')->get();
       $typePieces = DB::table('type_pieces')->Where('deleted_at', NULL)->orderBy('libelle_type_piece', 'asc')->get();
       
       $menuPrincipal = "Taxe";
       $titleControlleur = "Contribuables";
       $btnModalAjout ="TRUE";
       return view('taxe.contribuable.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','nations', 'typePieces','communes', 'fonctions')); 
    
    }
    
    public function vueDetail($id){
        $contribuable = Contribuable::find($id);
        $menuPrincipal = "Contribuables";
        $titleControlleur = "Fiche du contribuable : ".$contribuable->nom_complet;
        $btnModalAjout ="FALSE";
        return view('taxe.contribuable.details',compact('btnModalAjout','contribuable', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeContribuable(){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where('deleted_at', NULL)
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableByName($name){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['nom_complet','like','%'.$name.'%']])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableByNumero($numero){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['numero_identifiant','like','%'.$numero.'%']])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableByNation($nation){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['nation_id',$nation]])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableBySexe($sexe){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['sexe',$sexe]])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function getContribuableByActivite($activite){
        $contribuables = DeclarationActivite::join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->select('contribuables.*')
                                    ->Where([['contribuables.deleted_at', NULL],['declaration_activites.id',$activite]])
                                    ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function getAllPayementTaxe($contribuable){

        $payements = PayementTaxe::with('declaration_activite')
                            ->join('declaration_activites', 'declaration_activites.id', '=', 'payement_taxes.declaration_activite_id')
                            ->join('contribuables', 'contribuables.id', '=', 'declaration_activites.contribuable_id')
                            ->select('payement_taxes.*', 'contribuables.nom_complet as nom_complet_contribuable', DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'), DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                            ->Where([['payement_taxes.deleted_at', NULL],['contribuables.id', $contribuable]])
                            ->orderBy('payement_taxes.date_payement', 'DESC')
                            ->get();

        $jsonData["rows"] = $payements->toArray();
       $jsonData["total"] = $payements->count();
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
        if ($request->isMethod('post') && $request->input('nom_complet')) {

                $data = $request->all(); 

            try {
                
                $Contribuable = Contribuable::where('numero_piece', $data['numero_piece'])->first();
                if($Contribuable!=null){
                    return response()->json(["code" => 0, "msg" => "Cet contribuable est déjà enregistré, vérifier le numéro de la pièce d'identifiant", "data" => NULL]);
                }
                
                $year = date("Y");
                $maxId = DB::table('contribuables')->max('id');
                $numero = sprintf("%06d", ($maxId + 1));
                        
                $contribuable = new Contribuable;
                $contribuable->numero_identifiant = $numero.'-'.$year;
                $contribuable->nom_complet = $data['nom_complet'];
                $contribuable->sexe = $data['sexe'];
                $contribuable->contact = $data['contact'];
                $contribuable->numero_piece = $data['numero_piece'];
                $contribuable->situation_matrimoniale = $data['situation_matrimoniale'];
                $contribuable->commune_id = $data['commune_id'];
                $contribuable->type_piece_id = $data['type_piece_id'];
                $contribuable->nation_id = $data['nation_id'];
                $contribuable->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id']:null;
                $contribuable->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $contribuable->contact2 = isset($data['contact2']) && !empty($data['contact2']) ? $data['contact2']: Null;
                $contribuable->adresse = isset($data['adresse']) && !empty($data['adresse']) ? $data['adresse']: Null;
                $contribuable->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $contribuable->created_by = Auth::user()->id;
                $contribuable->save();
                $jsonData["data"] = json_decode($contribuable);
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
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Contribuable  $contribuable
     * @return Response
     */
    public function update(Request $request, Contribuable $contribuable)
    {
         $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($contribuable){
            try {
                
                $data = $request->all();
       
                $contribuable->nom_complet = $data['nom_complet'];
                $contribuable->sexe = $data['sexe'];
                $contribuable->contact = $data['contact'];
                $contribuable->numero_piece = $data['numero_piece'];
                $contribuable->situation_matrimoniale = $data['situation_matrimoniale'];
                $contribuable->commune_id = $data['commune_id'];
                $contribuable->type_piece_id = $data['type_piece_id'];
                $contribuable->nation_id = $data['nation_id'];
                $contribuable->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id']:null;
                $contribuable->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $contribuable->contact2 = isset($data['contact2']) && !empty($data['contact2']) ? $data['contact2']: Null;
                $contribuable->adresse = isset($data['adresse']) && !empty($data['adresse']) ? $data['adresse']: Null;
                $contribuable->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $contribuable->updated_by = Auth::user()->id;
                $contribuable->save();
       
            $jsonData["data"] = json_decode($contribuable);
            return response()->json($jsonData);
            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Echec de modification", "data" => NULL]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contribuable  $contribuable
     * @return Response
     */
    public function destroy(Contribuable $contribuable)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($contribuable){
                try {
               
                $contribuable->update(['deleted_by' => Auth::user()->id]);
                $contribuable->delete();
                
                $jsonData["data"] = json_decode($contribuable);
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
