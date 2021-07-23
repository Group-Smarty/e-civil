<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\TypeCourrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TypeCourrierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Type de courrier";
       $btnModalAjout = "FALSE";
       return view('parametre.type-courrier.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeTypeCourrier()
    {
       $typeCourriers = DB::table('type_courriers')
                ->select('type_courriers.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_type_courrier', 'ASC')
                ->get();
       $jsonData["rows"] = $typeCourriers->toArray();
       $jsonData["total"] = $typeCourriers->count();
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
        if ($request->isMethod('post') && $request->input('libelle_type_courrier')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_type_courrier' => 'required',
                ]);
                $TypeCourrier = TypeCourrier::where('libelle_type_courrier', $data['libelle_type_courrier'])->first();
                if($TypeCourrier!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeCourrier = new TypeCourrier;
                $typeCourrier->libelle_type_courrier = $data['libelle_type_courrier'];
                $typeCourrier->created_by = Auth::user()->id;
                $typeCourrier->save();
                $jsonData["data"] = json_decode($typeCourrier);
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
     * @param  \App\TypeCourrier  $typeCourrier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeCourrier $typeCourrier)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($typeCourrier){
            try {

                $request->validate([
                    'libelle_type_courrier' => 'required',
                ]);
                $TypeCourrier = TypeCourrier::where('libelle_type_courrier', $request->get('libelle_type_courrier'))->first();
                
                if($TypeCourrier!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeCourrier->update([
                    'libelle_type_courrier' => $request->get('libelle_type_courrier'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($typeCourrier);
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
     * @param  \App\TypeCourrier  $typeCourrier
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeCourrier $typeCourrier)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($typeCourrier){
                try {
               
                $typeCourrier->update(['deleted_by' => Auth::user()->id]);
                $typeCourrier->delete();
                $jsonData["data"] = json_decode($typeCourrier);
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
