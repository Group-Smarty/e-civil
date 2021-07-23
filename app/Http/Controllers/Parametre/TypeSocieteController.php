<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\TypeSociete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TypeSocieteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Type de société";
       $btnModalAjout = "FALSE";
       return view('parametre.type-societe.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeTypeSociete()
    {
       $typeSocietes = DB::table('type_societes')
                ->select('type_societes.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_type_societe', 'ASC')
                ->get();
       $jsonData["rows"] = $typeSocietes->toArray();
       $jsonData["total"] = $typeSocietes->count();
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
        if ($request->isMethod('post') && $request->input('libelle_type_societe')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_type_societe' => 'required',
                ]);
                $TypeSociete = TypeSociete::where('libelle_type_societe', $data['libelle_type_societe'])->first();
                if($TypeSociete!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeSociete = new TypeSociete;
                $typeSociete->libelle_type_societe = $data['libelle_type_societe'];
                $typeSociete->created_by = Auth::user()->id;
                $typeSociete->save();
                $jsonData["data"] = json_decode($typeSociete);
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
     * @param  \App\TypeSociete  $typeSociete
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeSociete $typeSociete)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($typeSociete){
            try {

                $request->validate([
                    'libelle_type_societe' => 'required',
                ]);
                $TypeSociete = TypeSociete::where('libelle_type_societe', $request->get('libelle_type_societe'))->first();
                
                if($TypeSociete!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeSociete->update([
                    'libelle_type_societe' => $request->get('libelle_type_societe'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($typeSociete);
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
     * @param  \App\TypeSociete  $typeSociete
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeSociete $typeSociete)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($typeSociete){
                try {
               
                $typeSociete->update(['deleted_by' => Auth::user()->id]);
                $typeSociete->delete();
                $jsonData["data"] = json_decode($typeSociete);
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
