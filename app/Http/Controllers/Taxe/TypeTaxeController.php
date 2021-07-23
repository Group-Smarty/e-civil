<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\TypeTaxe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TypeTaxeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Taxe";
       $titleControlleur = "Type de taxe";
       $btnModalAjout = "FALSE";
       return view('taxe.type-taxe.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
 
    }
    
    public function listeTypeTaxe()
    {
        $type_taxes = DB::table('type_taxes')
                ->select('type_taxes.*')
                ->orderBy('libelle_type_taxe', 'ASC')
                ->get();
       $jsonData["rows"] = $type_taxes->toArray();
       $jsonData["total"] = $type_taxes->count();
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
        if ($request->isMethod('post') && $request->input('libelle_type_taxe')) {

                $data = $request->all(); 

            try {

                $TypeTaxe = TypeTaxe::where('libelle_type_taxe', $data['libelle_type_taxe'])->first();
                if($TypeTaxe!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeTaxe = new TypeTaxe;
                $typeTaxe->libelle_type_taxe = $data['libelle_type_taxe'];
//                $typeTaxe->montant = $data['montant'];
                $typeTaxe->save();
                $jsonData["data"] = json_decode($typeTaxe);
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
     * @param  \App\TypeTaxe  $typeTaxe
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $typeTaxe = TypeTaxe::find($id);
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($typeTaxe){
            try {

                $typeTaxe->update([
                    'libelle_type_taxe' => $request->get('libelle_type_taxe'),
//                    'montant' => $request->get('montant'),
                ]);
                $jsonData["data"] = json_decode($typeTaxe);
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
     * @param  \App\TypeTaxe  $typeTaxe
     * @return Response
     */
    public function destroy($id)
    {
        $typeTaxe = TypeTaxe::find($id);
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($typeTaxe){
                try {
               
                $typeTaxe->delete();
                $jsonData["data"] = json_decode($typeTaxe);
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
