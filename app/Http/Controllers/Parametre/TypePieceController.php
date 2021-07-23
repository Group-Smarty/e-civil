<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\TypePiece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TypePieceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Type de pièce";
       $btnModalAjout = "FALSE";
       return view('parametre.type-piece.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeTypePiece()
    {
       $typePieces = DB::table('type_pieces')
                ->select('type_pieces.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_type_piece', 'ASC')
                ->get();
       $jsonData["rows"] = $typePieces->toArray();
       $jsonData["total"] = $typePieces->count();
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
        if ($request->isMethod('post') && $request->input('libelle_type_piece')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_type_piece' => 'required',
                ]);
                $TypePiece = TypePiece::where('libelle_type_piece', $data['libelle_type_piece'])->first();
                if($TypePiece!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typePiece = new TypePiece;
                $typePiece->libelle_type_piece = $data['libelle_type_piece'];
                $typePiece->created_by = Auth::user()->id;
                $typePiece->save();
                $jsonData["data"] = json_decode($typePiece);
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
     * @param  \App\TypePiece  $typePiece
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypePiece $typePiece)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($typePiece){
            try {

                $request->validate([
                    'libelle_type_piece' => 'required',
                ]);
                $TypePiece = TypePiece::where('libelle_type_piece', $request->get('libelle_type_piece'))->first();
                
                if($TypePiece!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typePiece->update([
                    'libelle_type_piece' => $request->get('libelle_type_piece'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($typePiece);
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
     * @param  \App\TypePiece  $typePiece
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypePiece $typePiece)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($typePiece){
                try {
               
                $typePiece->update(['deleted_by' => Auth::user()->id]);
                $typePiece->delete();
                $jsonData["data"] = json_decode($typePiece);
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
