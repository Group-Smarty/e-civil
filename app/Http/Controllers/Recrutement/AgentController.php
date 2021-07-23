<?php

namespace App\Http\Controllers\Recrutement;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Service;
use App\Models\Recrutement\Agent;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $services = DB::table('services')->Where('deleted_at', NULL)->orderBy('libelle_service', 'asc')->get();
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $communes = DB::table('communes')->Where('deleted_at', NULL)->orderBy('libelle_commune', 'asc')->get();
       $typePieces = DB::table('type_pieces')->Where('deleted_at', NULL)->orderBy('libelle_type_piece', 'asc')->get();
       $menuPrincipal = "Recrutement";
       $titleControlleur = "Agents";
       $btnModalAjout = (Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur') ? "TRUE" : "FALSE";
       return view('recrutement.agent.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','typePieces','communes', 'services', 'fonctions')); 
    }

    public function listeAgent()
    {
        $agents = Agent::with('fonction','service','type_piece','commune')
                ->select('agents.*',DB::raw('DATE_FORMAT(agents.date_naissance, "%d-%m-%Y") as date_naissances'))
                ->Where('deleted_at', NULL)
                ->orderBy('full_name_agent', 'ASC')
                ->get();
       $jsonData["rows"] = $agents->toArray();
       $jsonData["total"] = $agents->count();
       return response()->json($jsonData);
    }
    
    public function listeAgentsByName($name){
        $agents = Agent::with('fonction','service','type_piece','commune')
                ->select('agents.*',DB::raw('DATE_FORMAT(agents.date_naissance, "%d-%m-%Y") as date_naissances'))
                ->Where([['agents.deleted_at', NULL],['agents.full_name_agent','like','%'.$name.'%']])
                ->orderBy('full_name_agent', 'ASC')
                ->get();
        $jsonData["rows"] = $agents->toArray();
        $jsonData["total"] = $agents->count();
        return response()->json($jsonData);
    }
    public function listeAgentsByService($service){
        $agents = Agent::with('fonction','service','type_piece','commune')
                ->select('agents.*',DB::raw('DATE_FORMAT(agents.date_naissance, "%d-%m-%Y") as date_naissances'))
                ->Where([['agents.deleted_at', NULL],['agents.service_id',$service]])
                ->orderBy('full_name_agent', 'ASC')
                ->get();
        $jsonData["rows"] = $agents->toArray();
        $jsonData["total"] = $agents->count();
        return response()->json($jsonData);
    }
    public function listeAgentsByFonction($fonction){
        $agents = Agent::with('fonction','service','type_piece','commune')
                ->select('agents.*',DB::raw('DATE_FORMAT(agents.date_naissance, "%d-%m-%Y") as date_naissances'))
                ->Where([['agents.deleted_at', NULL],['agents.fonction_id',$fonction]])
                ->orderBy('full_name_agent', 'ASC')
                ->get();
        $jsonData["rows"] = $agents->toArray();
        $jsonData["total"] = $agents->count();
        return response()->json($jsonData);
    }
    public function listeAgentsBySexe($sexe){
        $agents = Agent::with('fonction','service','type_piece','commune')
                ->select('agents.*',DB::raw('DATE_FORMAT(agents.date_naissance, "%d-%m-%Y") as date_naissances'))
                ->Where([['agents.deleted_at', NULL],['agents.sexe',$sexe]])
                ->orderBy('full_name_agent', 'ASC')
                ->get();
        $jsonData["rows"] = $agents->toArray();
        $jsonData["total"] = $agents->count();
        return response()->json($jsonData);
    }
    
    public function listeAgentsByServiceFonction($service,$fonction){
        $agents = Agent::with('fonction','service','type_piece','commune')
                ->select('agents.*',DB::raw('DATE_FORMAT(agents.date_naissance, "%d-%m-%Y") as date_naissances'))
                ->Where([['agents.deleted_at', NULL],['agents.service_id',$service],['agents.fonction_id',$fonction]])
                ->orderBy('full_name_agent', 'ASC')
                ->get();
        $jsonData["rows"] = $agents->toArray();
        $jsonData["total"] = $agents->count();
        return response()->json($jsonData);
    }
    
    public function findOneAgent($id){
        $agents = Agent::where([['agents.deleted_at', NULL],['agents.id',$id]])
                ->join('services','services.id','=','agents.service_id')
                ->select('agents.full_name_agent','agents.phone1','agents.email','services.libelle_service')
                ->get();
        $jsonData["rows"] = $agents->toArray();
        $jsonData["total"] = $agents->count();
        return response()->json($jsonData);
    }
    
    public function findOneAgentForContrat($id){
        $agents = Agent::where([['agents.deleted_at', NULL],['agents.id',$id]])
                ->join('services','services.id','=','agents.service_id')
                ->join('fonctions','fonctions.id','=','agents.fonction_id')
                ->select('agents.phone1','agents.numero_piece_identite','services.libelle_service','fonctions.libelle_fonction')
                ->get();
        $jsonData["rows"] = $agents->toArray();
        $jsonData["total"] = $agents->count();
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
        if ($request->isMethod('post') && $request->input('full_name')) {

                $data = $request->all(); 

            try {
                
                $AgentNumeroPiece = Agent::where('numero_piece_identite', $data['numero_piece_identite'])->first();
                if($AgentNumeroPiece!=null){
                    return response()->json(["code" => 0, "msg" => "Cet agent est déjà enregistré, vérifier le numéro de la pièce d'identité", "data" => NULL]);
                }
               
                $AgentEmail = Agent::where('email', $data['email'])->first();
                if($AgentEmail!=null){
                    return response()->json(["code" => 0, "msg" => "Cet agent est déjà enregistré, vérifier l'adresse mail", "data" => NULL]);
                }
                
                $agent = new Agent;
                $agent->full_name_agent = $data['full_name'];
                $agent->numero_piece_identite = $data['numero_piece_identite'];
                $agent->situation_matrimoniale = $data['situation_matrimoniale'];
                $agent->sexe = $data['sexe'];
                $agent->lieu_naissance = $data['lieu_naissance'];
                $agent->phone1 = $data['phone1'];
                $agent->email = isset($data['email']) && !empty($data['email']) ? $data['email'] : null;
                $agent->adresse = $data['adresse'];
                $agent->service_id = $data['service_id'];
                $agent->fonction_id = $data['fonction_id'];
                $agent->commune_id = $data['commune_id'];
                $agent->type_piece_id = $data['type_piece_id'];
                $agent->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $agent->numero_securite = isset($data['numero_securite']) && !empty($data['numero_securite']) ? $data['numero_securite']: Null;
                $agent->phone2 = isset($data['phone2']) && !empty($data['phone2']) ? $data['phone2']: Null;
                $agent->created_by = Auth::user()->id;
                $agent->save();
                $jsonData["data"] = json_decode($agent);
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
     * @param  \App\Agent  $agent
     * @return Response
     */
    public function update(Request $request, Agent $agent)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($agent){
            try {
                
                $data = $request->all();
                $AgentNumeroPiece = Agent::where([['numero_piece_identite', $data['numero_piece_identite']],['email','!=', $agent->email],['phone1','!=', $agent->phone1]])->first();
                if($AgentNumeroPiece!=null){
                    return response()->json(["code" => 0, "msg" => "Cet agent est déjà enregistré, vérifié le numéro de pièce d'identité", "data" => NULL]);
                }
                
                $AgentEmail = Agent::where([['email', $data['email']],['phone1','!=', $agent->phone1],['numero_piece_identite','!=', $agent->numero_piece_identite]])->first();
                if($AgentEmail!=null){
                    return response()->json(["code" => 0, "msg" => "Cet agent est déjà enregistré, vérifié l'adresse email", "data" => NULL]);
                }
                
                $agent->full_name_agent = $data['full_name'];
                $agent->numero_piece_identite = $data['numero_piece_identite'];
                $agent->situation_matrimoniale = $data['situation_matrimoniale'];
                $agent->sexe = $data['sexe'];
                $agent->lieu_naissance = $data['lieu_naissance'];
                $agent->phone1 = $data['phone1'];
                $agent->email = isset($data['email']) && !empty($data['email']) ? $data['email'] : null;
                $agent->adresse = $data['adresse'];
                $agent->service_id = $data['service_id'];
                $agent->fonction_id = $data['fonction_id'];
                $agent->commune_id = $data['commune_id'];
                $agent->type_piece_id = $data['type_piece_id'];
                $agent->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $agent->numero_securite = isset($data['numero_securite']) && !empty($data['numero_securite']) ? $data['numero_securite']: Null;
                $agent->phone2 = isset($data['phone2']) && !empty($data['phone2']) ? $data['phone2']: Null;
                $agent->updated_by = Auth::user()->id;
                $agent->save();
                
                //Si l'agent est un utilisateur de l'application alors on modifie ses informations de compte
                    $service = Service::find($data['service_id']);
                    $user = User::where('employe_id',$agent->id)->first();
                    if($user){
                        $user->update([
                            'full_name' => $data['full_name'],
                            'contact' => $data['phone1'],
                            'service' => strtoupper($service->libelle_service),
                            'email' => isset($data['email']) && !epmty($data['email']) ? $data['email'] : null,
                            'updated_by' => Auth::user()->id,  
                        ]);
                    }
               
            $jsonData["data"] = json_decode($agent);
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
     * @param  \App\Agent  $agent
     * @return Response
     */
    public function destroy(Agent $agent)
    {
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($agent){
                try {
               
                $user = User::where('employe_id',$agent->id)->first();
                if($user){
                    $user->update([
                        'statut_compte' => FALSE,
                        'updated_by' => Auth::user()->id,  
                    ]);
                }
                $agent->update(['deleted_by' => Auth::user()->id]);
                $agent->delete();
                
                $jsonData["data"] = json_decode($agent);
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
