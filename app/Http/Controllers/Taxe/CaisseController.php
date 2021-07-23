<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Caisse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CaisseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $menuPrincipal = "Taxe";
       $titleControlleur = "Caisse";
       $btnModalAjout = "FALSE";
       return view('taxe.caisse.index',compact('menuPrincipal', 'titleControlleur', 'btnModalAjout')); 
    }

    public function listeCaisse() {
        $caisses = Caisse::select('caisses.*')
                ->Where('caisses.deleted_at', NULL)
                ->orderBy('caisses.libelle_caisse', 'ASC')
                ->get();

        $jsonData["rows"] = $caisses->toArray();
        $jsonData["total"] = $caisses->count();
        return response()->json($jsonData);
    }
    
    public function listeCaissesFermees(){
        $caisses = Caisse::select('caisses.*')
                ->Where([['caisses.deleted_at', NULL],['ouvert',0]])
                ->orderBy('caisses.libelle_caisse', 'ASC')
                ->get();

        $jsonData["rows"] = $caisses->toArray();
        $jsonData["total"] = $caisses->count();
        return response()->json($jsonData);
    }
    
    public function findCaisseById($id){
        $caisses = Caisse::select('caisses.*')
                    ->Where([['caisses.deleted_at', NULL],['caisses.id',$id]])
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
        if ($request->isMethod('post') && $request->input('libelle_caisse')) {

                $data = $request->all(); 

            try {

                $Caisse = Caisse::where('libelle_caisse', $data['libelle_caisse'])->first();
                if($Caisse!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }
                $caisse = new Caisse;
                $caisse->libelle_caisse = $data['libelle_caisse'];
                $caisse->created_by = Auth::user()->id;
                $caisse->save();
                
                $jsonData["data"] = json_decode($caisse);
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
     * @param  \App\Caisse  $caisse
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        $caisse = Caisse::find($id);

        if($caisse){
            try {

                $caisse->update([
                    'libelle_caisse' => $request->get('libelle_caisse'),
                    'updated_by' => Auth::user()->id,
                ]);
                
                $jsonData["data"] = json_decode($caisse);
                
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
     * @param  \App\Caisse  $caisse
     * @return Response
     */
    public function destroy($id)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
        $caisse = Caisse::find($id);
        if($caisse){
            try {
                
                $caisse->update(['deleted_by' => Auth::user()->id]);
                $caisse->delete();
                
                $jsonData["data"] = json_decode($caisse);
                
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
