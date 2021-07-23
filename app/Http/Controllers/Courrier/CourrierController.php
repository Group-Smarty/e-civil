<?php

namespace App\Http\Controllers\Courrier;

use App\Http\Controllers\Controller;
use App\Models\Courrier\Courrier;
use App\Models\Parametre\Service;
use App\Notifications\RegistredUserNotification;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CourrierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $secteurs = DB::table('secteurs')->Where('deleted_at', NULL)->orderBy('libelle_secteur', 'asc')->get();
       $services = DB::table('services')->Where('deleted_at', NULL)->orderBy('libelle_service', 'asc')->get();
       $typeSocietes = DB::table('type_societes')->Where('deleted_at', NULL)->orderBy('libelle_type_societe', 'asc')->get();
       $annuaires = DB::table('annuaires')->Where('deleted_at', NULL)->orderBy('raison_sociale', 'asc')->get();
       $typeCourriers = DB::table('type_courriers')->Where('deleted_at', NULL)->orderBy('libelle_type_courrier', 'asc')->get();
    
       $menuPrincipal = "Courrier";
       $titleControlleur = "Liste de tous les courriers";
       $btnModalAjout = "FALSE";
       return view('courrier.courrier.index',compact('annuaires','services', 'typeCourriers', 'btnModalAjout', 'menuPrincipal', 'titleControlleur','secteurs','typeSocietes')); 

    }
    
    public function vueCourrierEmis()
    {
       $secteurs = DB::table('secteurs')->Where('deleted_at', NULL)->orderBy('libelle_secteur', 'asc')->get();
       $typeSocietes = DB::table('type_societes')->Where('deleted_at', NULL)->orderBy('libelle_type_societe', 'asc')->get();
       $annuaires = DB::table('annuaires')->Where('deleted_at', NULL)->orderBy('raison_sociale', 'asc')->get();
       $typeCourriers = DB::table('type_courriers')->Where('deleted_at', NULL)->orderBy('libelle_type_courrier', 'asc')->get();
       $menuPrincipal = "Courrier";
       $titleControlleur = "Liste des courriers sortant";
       $btnModalAjout = "TRUE";
       return view('courrier.courrier.courriers-emis',compact('secteurs','typeSocietes','btnModalAjout', 'menuPrincipal', 'titleControlleur','annuaires','typeCourriers')); 

    }
    
    public function vueCourrierRecu()
    {  
       $secteurs = DB::table('secteurs')->Where('deleted_at', NULL)->orderBy('libelle_secteur', 'asc')->get();
       $services = DB::table('services')->Where('deleted_at', NULL)->orderBy('libelle_service', 'asc')->get();
       $typeSocietes = DB::table('type_societes')->Where('deleted_at', NULL)->orderBy('libelle_type_societe', 'asc')->get();
       $annuaires = DB::table('annuaires')->Where('deleted_at', NULL)->orderBy('raison_sociale', 'asc')->get();
       $typeCourriers = DB::table('type_courriers')->Where('deleted_at', NULL)->orderBy('libelle_type_courrier', 'asc')->get();
       $menuPrincipal = "Courrier";
       $titleControlleur = "Liste des courriers entrant";
       $btnModalAjout = "TRUE";
       return view('courrier.courrier.courriers-reucs',compact('annuaires','services', 'typeCourriers', 'btnModalAjout', 'menuPrincipal', 'titleControlleur','secteurs','typeSocietes')); 

    }

    public function listeCourriers()
    {
        $courriers = Courrier::with('type_courrier','annuaire','service')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where('deleted_at', NULL)
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
       $jsonData["rows"] = $courriers->toArray();
       $jsonData["total"] = $courriers->count();
       return response()->json($jsonData);
    }
    
    public function listeCourrierEmis()
    {
        $courriers = Courrier::with('type_courrier','annuaire')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['deleted_at', NULL],['emmettre_recu','=','Emis']])
                 ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
       $jsonData["rows"] = $courriers->toArray();
       $jsonData["total"] = $courriers->count();
       return response()->json($jsonData);
    }
    
    public function listeCourrierRecus()
    {
        $courriers = Courrier::with('type_courrier','annuaire','service')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['deleted_at', NULL],['emmettre_recu','=','Recus']])
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
       $jsonData["rows"] = $courriers->toArray();
       $jsonData["total"] = $courriers->count();
       return response()->json($jsonData);
    }
    
    public function listeCourrierByDate($dates,$ecran){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        if($ecran=='emis'){
            $courriers = Courrier::with('type_courrier','annuaire')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['deleted_at', NULL],['emmettre_recu','=','Emis']])
                ->whereDate('courriers.date_courrier','=', $date)
               ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='recus'){
            $courriers = Courrier::with('type_courrier','annuaire','service')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['deleted_at', NULL],['emmettre_recu','=','Recus']])
                ->whereDate('courriers.date_courrier','=', $date)
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='tous'){
        $courriers = Courrier::with('type_courrier','annuaire','service')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where('deleted_at', NULL)
                ->whereDate('courriers.date_courrier','=', $date)
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        $jsonData["rows"] = $courriers->toArray();
        $jsonData["total"] = $courriers->count();
        return response()->json($jsonData);
    }
    
    public function listeCourrierByObjet($objet,$ecran){
        if($ecran=='emis'){
            $courriers = Courrier::with('type_courrier','annuaire')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['deleted_at', NULL],['courriers.emmettre_recu','=','Emis'],['courriers.objet','like','%'.$objet.'%']])
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='recus'){
            $courriers = Courrier::with('type_courrier','annuaire','service')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['deleted_at', NULL],['courriers.emmettre_recu','=','Recus'],['courriers.objet','like','%'.$objet.'%']])
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='tous'){
        $courriers = Courrier::with('type_courrier','annuaire','service')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['deleted_at', NULL],['courriers.objet','like','%'.$objet.'%']])
               ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        $jsonData["rows"] = $courriers->toArray();
        $jsonData["total"] = $courriers->count();
        return response()->json($jsonData);
    }
    
    public function listeCourrierBySociete($societe,$ecran){
        if($ecran=='emis'){
            $courriers = Courrier::with('type_courrier','annuaire')
                ->join('annuaires','annuaires.id','=','courriers.annuaire_id')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['courriers.deleted_at', NULL],['courriers.emmettre_recu','=','Emis'],['courriers.annuaire_id',$societe]])
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='recus'){
            $courriers = Courrier::with('type_courrier','annuaire','service')
                ->join('annuaires','annuaires.id','=','courriers.annuaire_id')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['courriers.deleted_at', NULL],['courriers.emmettre_recu','=','Recus'],['courriers.annuaire_id',$societe]])
               ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='tous'){
        $courriers = Courrier::with('type_courrier','annuaire','service')
                ->join('annuaires','annuaires.id','=','courriers.annuaire_id')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['courriers.deleted_at', NULL],['courriers.annuaire_id',$societe]])
               ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        $jsonData["rows"] = $courriers->toArray();
        $jsonData["total"] = $courriers->count();
        return response()->json($jsonData);
    }
    
    public function listeCourrierByType($type,$ecran){
        if($ecran=='emis'){
            $courriers = Courrier::with('type_courrier','annuaire')
                ->join('type_courriers','type_courriers.id','=','courriers.type_courrier_id')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['courriers.deleted_at', NULL],['courriers.emmettre_recu','=','Emis'],['courriers.type_courrier_id',$type]])
               ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='recus'){
            $courriers = Courrier::with('type_courrier','annuaire','service')
                ->join('type_courriers','type_courriers.id','=','courriers.type_courrier_id')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['courriers.deleted_at', NULL],['courriers.emmettre_recu','=','Recus'],['courriers.type_courrier_id',$type]])
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        if($ecran=='tous'){
        $courriers = Courrier::with('type_courrier','annuaire','service')
                ->join('type_courriers','type_courriers.id','=','courriers.type_courrier_id')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['courriers.deleted_at', NULL],['courriers.type_courrier_id',$type]])
               ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        }
        $jsonData["rows"] = $courriers->toArray();
        $jsonData["total"] = $courriers->count();
        return response()->json($jsonData);
    }
    
    public function listeCourrierByService($service){
        $courriers = Courrier::with('type_courrier','annuaire','service')
                ->join('services','services.id','=','courriers.service_id')
                ->select('courriers.*',DB::raw('DATE_FORMAT(courriers.date_courrier, "%d-%m-%Y %H:%i") as date_courriers'))
                ->Where([['courriers.deleted_at', NULL],['courriers.emmettre_recu','=','Recus'],['courriers.service_id',$service]])
                ->orderBy('courriers.traiter', 'ASC')
                ->orderBy('courriers.date_courrier', 'DESC')
                ->get();
        $jsonData["rows"] = $courriers->toArray();
        $jsonData["total"] = $courriers->count();
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
        if ($request->isMethod('post') && $request->input('objet')) {

                $data = $request->all(); 

            try {
             
                $courrier = new Courrier;
                $courrier->objet = $data['objet'];
                $courrier->date_courrier = Carbon::createFromFormat('d-m-Y H:i', $data['date_courrier']);
                $courrier->type_courrier_id = $data['type_courrier_id'];
                $courrier->emmettre_recu = $data['emmettre_recu'];
                $courrier->annuaire_id = isset($data['annuaire_id']) && !empty($data['annuaire_id']) ? $data['annuaire_id']: Null;
                $courrier->full_nam_particulier = isset($data['full_nam_particulier']) && !empty($data['full_nam_particulier']) ? $data['full_nam_particulier']: Null;
                $courrier->contact_particulier = isset($data['contact_particulier']) && !empty($data['contact_particulier']) ? $data['contact_particulier']: Null;
                $courrier->commentaire = isset($data['commentaire']) && !empty($data['commentaire']) ? $data['commentaire']: Null;
                $courrier->service_id = isset($data['service_id']) && !empty($data['service_id']) ? $data['service_id']: Null;
                $courrier->particulier = isset($data['particulier']) ? TRUE : FALSE;
                 $courrier->traiter = isset($data['traiter']) ? TRUE : FALSE;
                //Si le courrier est entrant alors on envoi un mail au service concerné
                if($data['emmettre_recu']=='Recus'){
                    $service = Service::find($data['service_id']);
                    $user = User::where([['service', strtoupper($service->libelle_service)],['chef_service',1]])->first();
                    if($user!=null){
                        $to_name = $user->service;
                        $to_email = $user->email;
                        $data = array("name"=>$user->service, "body" => "Votre service vient de recevoir un courrier qui est disponible au service courrier..");
  
                        Mail::send('auth/user/mail', $data, function($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)
                        ->subject('Réception de courrier pour votre service');
                        $message->from('tranxpert@smartyacademy.com','E-Civil');
                        });
                    }else{
                       return response()->json(["code" => 0, "msg" => "Vous n'avaez pas enregistré de chef pour ce service. Veuillez contacter l'administrateur SVP.", "data" => NULL]); 
                    }
                }
                //Insertion du document scanner du courrier s'il y a en  
                if(isset($data['document_scanner']) && !empty($data['document_scanner'])){
                    $document_scanner = request()->file('document_scanner');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$document_scanner->getClientOriginalName()));
                    //Vérification du format de fichier
//                    $extensions = array('.png','.jpg', '.jpeg');
//                    $extension = strrchr($file_name, '.');
//                    //Début des vérifications de sécurité...
//                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
//                    {
//                        return response()->json(["code" => 0, "msg" => "Vous devez uploader un fichier de type jpeg png, jpg", "data" => NULL]);
//                    }
                    $path = public_path().'/documents/courrier/';
                    $document_scanner->move($path,$file_name);
                    $courrier->document_scanner = 'documents/courrier/'.$file_name;
                }
                $courrier->created_by = Auth::user()->id;
                $courrier->save();
                $jsonData["data"] = json_decode($courrier);
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
     * @param  \App\Courrier  $courrier
     * @return Response
     */
    public function updateCourrier(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        $courrier = Courrier::find($request->get('idCourrier'));
        

        if ($courrier) {

                $data = $request->all(); 

            try {
             
                $courrier->objet = $data['objet'];
                $courrier->date_courrier = Carbon::createFromFormat('d-m-Y H:i', $data['date_courrier']);
                $courrier->type_courrier_id = $data['type_courrier_id'];
                $courrier->annuaire_id = isset($data['annuaire_id']) && !empty($data['annuaire_id']) ? $data['annuaire_id']: Null;
                $courrier->full_nam_particulier = isset($data['full_nam_particulier']) && !empty($data['full_nam_particulier']) ? $data['full_nam_particulier']: Null;
                $courrier->contact_particulier = isset($data['contact_particulier']) && !empty($data['contact_particulier']) ? $data['contact_particulier']: Null;
                $courrier->commentaire = isset($data['commentaire']) && !empty($data['commentaire']) ? $data['commentaire']: Null;
                $courrier->service_id = isset($data['service_id']) && !empty($data['service_id']) ? $data['service_id']: Null;
                $courrier->particulier = isset($data['particulier']) ? TRUE : FALSE;
                $courrier->traiter = isset($data['traiter']) ? TRUE : FALSE;
                //Si le courrier est entrant alors on envoi un mail au service concerné
                if($data['emmettre_recu']=='Recus' && $courrier->service_id!=$data['service_id']){
                    $service = Service::find($data['service_id']);
                    $user = User::where([['service', strtoupper($service->libelle_service)],['chef_service',1]])->first();
                    if($user!=null){
                        $to_name = $user->service;
                        $to_email = $user->email;
                        $data = array("name"=>$user->service, "body" => "Votre service vient de recevoir un courrier qui est disponible au service courrier..");
  
                        Mail::send('auth/user/mail', $data, function($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)
                        ->subject('Réception de courrier pour votre service');
                        $message->from('tranxpert@smartyacademy.com','E-Civil');
                        });
                    }else{
                       return response()->json(["code" => 0, "msg" => "Vous n'avaez pas enregistré de chef pour ce service. Veuillez contacter l'administrateur SVP.", "data" => NULL]); 
                    }
                }
                //Insertion du document scanner du courrier s'il y a en 
                if(isset($data['document_scanner']) && !empty($data['document_scanner'])){
                    $document_scanner = request()->file('document_scanner');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$document_scanner->getClientOriginalName()));
                    //Vérification du format de fichier
//                    $extensions = array('.png','.jpg', '.jpeg');
//                    $extension = strrchr($file_name, '.');
//                    //Début des vérifications de sécurité...
//                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
//                    {
//                        return response()->json(["code" => 0, "msg" => "Vous devez uploader un fichier de type jpeg png, jpg", "data" => NULL]);
//                    }
                    $path = public_path().'/documents/courrier/';
                    $document_scanner->move($path,$file_name);
                    $courrier->document_scanner = 'documents/courrier/'.$file_name;
                }
                $courrier->updated_by = Auth::user()->id;
                $courrier->save();
                $jsonData["data"] = json_decode($courrier);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Courrier  $courrier
     * @return Response
     */
    public function destroy(Courrier $courrier)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($courrier){
                try {
               
                $courrier->update(['deleted_by' => Auth::user()->id]);
                $courrier->delete();
                
                $jsonData["data"] = json_decode($courrier);
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
