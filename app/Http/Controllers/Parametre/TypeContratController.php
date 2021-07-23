<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\TypeContrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TypeContratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Type de contrat de travail";
       $btnModalAjout = "FALSE";
       return view('parametre.type-contrat.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeTypeContrat()
    {
       $typeContrats = DB::table('type_contrats')
                ->select('type_contrats.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_type_contrat', 'ASC')
                ->get();
       $jsonData["rows"] = $typeContrats->toArray();
       $jsonData["total"] = $typeContrats->count();
       return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($request->isMethod('post') && $request->input('libelle_type_contrat')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_type_contrat' => 'required',
                ]);
                $TypeContrat = TypeContrat::where('libelle_type_contrat', $data['libelle_type_contrat'])->first();
                if($TypeContrat!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeContrat = new TypeContrat;
                $typeContrat->libelle_type_contrat = $data['libelle_type_contrat'];
                $typeContrat->created_by = Auth::user()->id;
                $typeContrat->save();
                $jsonData["data"] = json_decode($typeContrat);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TypeContrat  $typeContrat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeContrat $typeContrat)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($typeContrat){
            try {

                $request->validate([
                    'libelle_type_contrat' => 'required',
                ]);
                $TypeContrat = TypeContrat::where('libelle_type_contrat', $request->get('libelle_type_contrat'))->first();
                
                if($TypeContrat!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeContrat->update([
                    'libelle_type_contrat' => $request->get('libelle_type_contrat'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($typeContrat);
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
     * @param  \App\TypeContrat  $typeContrat
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeContrat $typeContrat)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($typeContrat){
                try {
               
                $typeContrat->update(['deleted_by' => Auth::user()->id]);
                $typeContrat->delete();
                $jsonData["data"] = json_decode($typeContrat);
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
