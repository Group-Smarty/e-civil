<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Rubrique;
use App\Models\Transit\Dossier;
use App\Models\Tresorerie\Facture;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function response;
use function view;


class RestaurageDataController extends Controller
{
    public function index(){
       
       $menuPrincipal = "Datas bases";
       $titleControlleur = "Liste de toutes les tables de la base";
       $btnModalAjout = "FALSE";
       return view('auth.restaurage_data_vues.all_tables',compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
    
    public function oneTable($table){
       $menuPrincipal = ucwords(str_replace('_', ' ', substr($table, 0,-1)));
       $titleControlleur = "Liste des enregistrements supprimées dans cette table";
       $btnModalAjout = "FALSE";
        return view('auth.restaurage_data_vues.one_table',compact('table', 'menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }

    public function listeAllTable(){
        $allTables = DB::select(DB::raw("SHOW TABLES"));
        return $allTables;
    }
    
    public function listeContentOneTable($table){  
        
        $name_colone_table = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'ecivil' AND TABLE_NAME = '$table' AND ORDINAL_POSITION = 2");

        $array = json_decode(json_encode($name_colone_table), true);

        $libelle = $array[0]["COLUMN_NAME"]; 
        if($table=="users"){
          $tableConcernee = DB::table($table)
                        ->select('users.full_name','users.role','users.email','users.contact',$table.'.id',$table.'.deleted_at',DB::raw($table.".$libelle as libelle"))
                        ->Where($table.'.deleted_at', '<>' ,NULL)
                        ->orderBy($table.'.deleted_at', 'desc')->get();
        }else{
          $tableConcernee = DB::table($table)
                        ->join('users','users.id','=', $table.'.deleted_by')
                        ->select('users.full_name','users.role','users.email','users.contact',$table.'.id',$table.'.deleted_at',DB::raw($table.".$libelle as libelle"))
                        ->Where($table.'.deleted_at', '<>' ,NULL)
                        ->orderBy($table.'.deleted_at', 'desc')->get();
        }
        
        
       $jsonData["rows"] = $tableConcernee->toArray();
       $jsonData["total"] = $tableConcernee->count();
        return response()->json($jsonData);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function restaurage(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement restauré avec succès."];
        $table = $request->get('table');
        $id = $request->get('id');
        
        if(!empty($table) && !empty($id)){
            
            try {
                $tableConcerne = DB::table($table)->where('id', $id)->get();
                        DB::table($table)->where('id', $id)
                                    ->update([
                                        'deleted_at' => NULL,
                                        'updated_by' => Auth::user()->id,
                                    ]);
                
                $jsonData["data"] = json_decode($tableConcerne);
                
            return response()->json($jsonData);

            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }

        }
        return response()->json(["code" => 0, "msg" => "Echec de restauration de l'enregistrement", "data" => NULL]);        
    }
}
