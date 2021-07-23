<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Contribuable;
use App\Models\Taxes\DeclarationActivite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeclarationActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $typeSocietes = DB::table('type_societes')->Where('deleted_at', NULL)->orderBy('libelle_type_societe', 'asc')->get();
       $secteurs = DB::table('secteurs')->Where('deleted_at', NULL)->orderBy('libelle_secteur', 'asc')->get();
       $typeTaxes = DB::table('type_taxes')->orderBy('libelle_type_taxe', 'asc')->get();
       $localites = DB::table('localites')->orderBy('libelle_localite', 'asc')->get();
       $contribuables = DB::table('contribuables')->Where('deleted_at', NULL)->orderBy('nom_complet', 'asc')->get();
       
       $menuPrincipal = "Taxe";
       $titleControlleur = "Déclaration des activités";
       $btnModalAjout ="TRUE";
       return view('taxe.declaration-activite.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','contribuables', 'typeSocietes', 'localites','typeTaxes', 'secteurs')); 
    }

    public function listeDeclarationActivite()
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where('deleted_at', NULL)
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByContribuable($contribuable)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['declaration_activites.contribuable_id',$contribuable]])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByLocalite($localite)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['declaration_activites.localite_id',$localite]])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByDate($date)
    {
        $dates = Carbon::createFromFormat('d-m-Y', $date);
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where('deleted_at', NULL)
                        ->WhereDate('declaration_activites.date_declaration',$dates)
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByNumero($numero)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['numero_cc','like','%'.$numero.'%']])
                        ->orWhere([['deleted_at', NULL],['numero_registre','like','%'.$numero.'%']])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByLocaliteContribuable($localite, $contribuable)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['declaration_activites.localite_id',$localite],['declaration_activites.contribuable_id',$contribuable]])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteById($id){
        $activite = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*')
                        ->Where([['deleted_at', NULL],['declaration_activites.id',$id]])
                        ->get();
        $jsonData["rows"] = $activite->toArray();
        $jsonData["total"] = $activite->count();
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
        if ($request->isMethod('post') && $request->input('nom_activite')) {

                $data = $request->all(); 

            try {
                
                $DeclarationActivite = DeclarationActivite::where('numero_cc', $data['numero_cc'])
                                                ->orWhere('numero_registre', $data['numero_registre'])
                                                ->first();
                if($DeclarationActivite!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement est déjà enregistré, vérifier le numéro contribuable ou du registre", "data" => NULL]);
                }
                
                $contribuable = Contribuable::find($data['contribuable_id']);
                
                if(!$contribuable){
                    return response()->json(["code" => 0, "msg" => "Ce contribuable est introuvable", "data" => NULL]);
                }
                
                $declarationActivite = new DeclarationActivite;
                $declarationActivite->nom_activite = $data['nom_activite'];
                $declarationActivite->nom_structure = isset($data['nom_structure']) && !empty($data['nom_structure']) ? $data['nom_structure'] : $contribuable->nom_complet;
                $declarationActivite->numero_cc = $data['numero_cc'];
                $declarationActivite->numero_registre = $data['numero_registre'];
                $declarationActivite->contact = isset($data['contact']) && !empty($data['contact']) ? $data['contact'] : $contribuable->contact;
                $declarationActivite->situation_geographique = $data['situation_geographique'];
                $declarationActivite->contribuable_id = $data['contribuable_id'];
                $declarationActivite->type_societe_id = $data['type_societe_id'];
                $declarationActivite->secteur_id = $data['secteur_id'];
                $declarationActivite->type_taxe_id =  $data['type_taxe_id'];
                $declarationActivite->localite_id =  $data['localite_id'];
                $declarationActivite->montant_taxe =  $data['montant_taxe'];
                $declarationActivite->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $declarationActivite->longitude = isset($data['longitude']) && !empty($data['longitude']) ? $data['longitude']: Null;
                $declarationActivite->latitude = isset($data['latitude']) && !empty($data['latitude']) ? $data['latitude']: Null;
                $declarationActivite->adresse_postale = isset($data['adresse_postale']) && !empty($data['adresse_postale']) ? $data['adresse_postale']: Null;
                $declarationActivite->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $declarationActivite->created_by = Auth::user()->id;
                $declarationActivite->save();
                $jsonData["data"] = json_decode($declarationActivite);
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
     * @param  \App\DeclarationActivite  $declarationActivite
     * @return Response
     */
    public function update(Request $request, DeclarationActivite $declarationActivite)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($declarationActivite){
            try {
                
                $data = $request->all();
                
                $contribuable = Contribuable::find($data['contribuable_id']);
                
                if(!$contribuable){
                    return response()->json(["code" => 0, "msg" => "Ce contribuable est introuvable", "data" => NULL]);
                }
       
                $declarationActivite->nom_activite = $data['nom_activite'];
                $declarationActivite->nom_structure = isset($data['nom_structure']) && !empty($data['nom_structure']) ? $data['nom_structure'] : $contribuable->nom_complet;
                $declarationActivite->numero_cc = $data['numero_cc'];
                $declarationActivite->numero_registre = $data['numero_registre'];
                $declarationActivite->contact = isset($data['contact']) && !empty($data['contact']) ? $data['contact'] : $contribuable->contact;
                $declarationActivite->situation_geographique = $data['situation_geographique'];
                $declarationActivite->contribuable_id = $data['contribuable_id'];
                $declarationActivite->type_societe_id = $data['type_societe_id'];
                $declarationActivite->secteur_id = $data['secteur_id'];
                $declarationActivite->type_taxe_id =  $data['type_taxe_id'];
                $declarationActivite->localite_id =  $data['localite_id'];
                $declarationActivite->montant_taxe =  $data['montant_taxe'];
                $declarationActivite->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $declarationActivite->longitude = isset($data['longitude']) && !empty($data['longitude']) ? $data['longitude']: Null;
                $declarationActivite->latitude = isset($data['latitude']) && !empty($data['latitude']) ? $data['latitude']: Null;
                $declarationActivite->adresse_postale = isset($data['adresse_postale']) && !empty($data['adresse_postale']) ? $data['adresse_postale']: Null;
                $declarationActivite->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $declarationActivite->updated_by = Auth::user()->id;
                $declarationActivite->save();
       
            $jsonData["data"] = json_decode($declarationActivite);
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
     * @param  \App\DeclarationActivite  $declarationActivite
     * @return Response
     */
    public function destroy(DeclarationActivite $declarationActivite)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($declarationActivite){
                try {
               
                $declarationActivite->update(['deleted_by' => Auth::user()->id]);
                $declarationActivite->delete();
                
                $jsonData["data"] = json_decode($declarationActivite);
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
