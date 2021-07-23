<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Caisse;
use App\Models\Taxes\CaisseOuverte;
use App\Models\Taxes\PayementTaxe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayementTaxeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $caisses = DB::table('caisses')->Where('deleted_at', NULL)->orderBy('libelle_caisse', 'asc')->get();
       
       $menuPrincipal = "Taxe";
       $titleControlleur = "Caisses";
       $btnModalAjout ="FALSE";
       return view('taxe.payement-taxe.caisses',compact('caisses', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    public function pointCaisseCaissier(Request $request){
        $caisse_ouverte = null; $auth_user = Auth::user(); $caisse = null;
       
        $contribuables = DB::table('contribuables')->Where('deleted_at', NULL)->orderBy('nom_complet', 'asc')->get();
       
        //Recupértion de la caisse dans la session
        if($request->session()->has('session_caisse_ouverte')){
            $caisse_ouverte_id = $request->session()->get('session_caisse_ouverte');
            $caisse_ouverte = CaisseOuverte::where([['id',$caisse_ouverte_id],['date_fermeture',null]])->first();
        }
            
        //Si la caisse n'est pas fermée et que l'user s'est déconnecté
        $caisse_ouverte_non_fermee = CaisseOuverte::where([['user_id',$auth_user->id],['date_fermeture',null]])->first();
        if($caisse_ouverte_non_fermee!=null){
            $request->session()->put('session_caisse_ouverte',$caisse_ouverte_non_fermee->id);
            $caisse_ouverte = CaisseOuverte::find($caisse_ouverte_non_fermee->id);
        }
            
        if($caisse_ouverte != null){
            $caisse = Caisse::find($caisse_ouverte->caisse_id);
            $titleControlleur = $caisse->libelle_caisse." ouverte";
        }else{
            $titleControlleur = "Caisse fermée";
        }
          
       $menuPrincipal = "Taxe";
     
       $btnModalAjout = $caisse_ouverte != null ? "TRUE" : "FALSE";
       return view('taxe.payement-taxe.caisse',compact('contribuables','caisse_ouverte','caisse','btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function pointCaisse(Request $request){
        
       $caisse_ouverte = null; $auth_user = Auth::user(); $caisse = null;
       
       $contribuables = DB::table('contribuables')->Where('deleted_at', NULL)->orderBy('nom_complet', 'asc')->get();
       
       if(Auth::user()->role!='Caissier')
       {
          $caisse_ouverte = CaisseOuverte::where([['caisse_id',$request->caisse_id],['date_fermeture',null]])->first(); 
          $caisse = Caisse::find($request->caisse_id);
          $caisse->ouvert == 1 ? $titleControlleur = $caisse->libelle_caisse." Ouverte" : $titleControlleur = $caisse->libelle_caisse." Fermée";
       
       }
       
       $menuPrincipal = "Taxe";
     
       $btnModalAjout = $caisse_ouverte != null ? "TRUE" : "FALSE";
       return view('taxe.payement-taxe.caisse',compact('contribuables','caisse_ouverte','caisse','btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    
    public function listPayementTaxe($caisse)
    {
        if(Auth::user()->role=='Caissier'){
             $payements = PayementTaxe::with('declaration_activite')
                                    ->join('declaration_activites','declaration_activites.id','=','payement_taxes.declaration_activite_id')
                                    ->join('caisse_ouvertes','caisse_ouvertes.id','=','payement_taxes.caisse_ouverte_id')
                                    ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->select('payement_taxes.*','contribuables.nom_complet as nom_complet_contribuable',DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'),DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                                    ->Where([['payement_taxes.deleted_at', NULL],['caisse_ouvertes.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',null]])
                                    ->orderBy('payement_taxes.date_payement', 'DESC')
                                    ->get();
        }else{
             $payements = PayementTaxe::with('declaration_activite')
                                    ->join('caisse_ouvertes','caisse_ouvertes.id','=','payement_taxes.caisse_ouverte_id')
                                    ->join('declaration_activites','declaration_activites.id','=','payement_taxes.declaration_activite_id')
                                    ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->select('payement_taxes.*','contribuables.nom_complet as nom_complet_contribuable',DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'),DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                                    ->Where([['payement_taxes.deleted_at', NULL],['caisse_ouvertes.caisse_id',$caisse]])
                                    ->orderBy('payement_taxes.date_payement', 'DESC')
                                    ->get();
        }
      
        
       $jsonData["rows"] = $payements->toArray();
       $jsonData["total"] = $payements->count();
       return response()->json($jsonData);
    }
    
    public function listPayementTaxeByFacture($numero,$caisse){
        
        if(Auth::user()->role=='Caissier'){
                $payements = PayementTaxe::with('declaration_activite')
                                    ->join('caisse_ouvertes','caisse_ouvertes.id','=','payement_taxes.caisse_ouverte_id')
                                    ->join('declaration_activites','declaration_activites.id','=','payement_taxes.declaration_activite_id')
                                    ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->select('payement_taxes.*','contribuables.nom_complet as nom_complet_contribuable',DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'),DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                                    ->Where([['payement_taxes.deleted_at', NULL],['caisse_ouvertes.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',null],['payement_taxes.numero_ticket', 'like', '%' . $numero . '%']])
                                    ->orderBy('payement_taxes.date_payement', 'DESC')
                                    ->get();
        }else{
                $payements = PayementTaxe::with('declaration_activite')
                                    ->join('caisse_ouvertes','caisse_ouvertes.id','=','payement_taxes.caisse_ouverte_id')
                                    ->join('declaration_activites','declaration_activites.id','=','payement_taxes.declaration_activite_id')
                                    ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->select('payement_taxes.*','contribuables.nom_complet as nom_complet_contribuable',DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'),DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                                    ->Where([['payement_taxes.deleted_at', NULL],['caisse_ouvertes.caisse_id',$caisse],['payement_taxes.numero_ticket', 'like', '%' . $numero . '%']])
                                    ->orderBy('payement_taxes.date_payement', 'DESC')
                                    ->get();
        }
       $jsonData["rows"] = $payements->toArray();
       $jsonData["total"] = $payements->count();
       return response()->json($jsonData);
    }
    
    public function listePayementTaxeByContribuable($contribuable,$caisse){
        if(Auth::user()->role=='Caissier'){
                $payements = PayementTaxe::with('declaration_activite')
                                    ->join('caisse_ouvertes','caisse_ouvertes.id','=','payement_taxes.caisse_ouverte_id')
                                    ->join('declaration_activites','declaration_activites.id','=','payement_taxes.declaration_activite_id')
                                    ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->select('payement_taxes.*','contribuables.nom_complet as nom_complet_contribuable',DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'),DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                                    ->Where([['payement_taxes.deleted_at', NULL],['caisse_ouvertes.user_id',Auth::user()->id],['caisse_ouvertes.date_fermeture',null],['contribuables.id',$contribuable]])
                                    ->orderBy('payement_taxes.date_payement', 'DESC')
                                    ->get();
        }else{
                $payements = PayementTaxe::with('declaration_activite')
                                            ->join('caisse_ouvertes','caisse_ouvertes.id','=','payement_taxes.caisse_ouverte_id')
                                            ->join('declaration_activites','declaration_activites.id','=','payement_taxes.declaration_activite_id')
                                            ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                            ->select('payement_taxes.*','contribuables.nom_complet as nom_complet_contribuable',DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'),DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                                            ->Where([['payement_taxes.deleted_at', NULL],['caisse_ouvertes.caisse_id',$caisse],['contribuables.id',$contribuable]])
                                            ->orderBy('payement_taxes.date_payement', 'DESC')
                                            ->get();
         }
       $jsonData["rows"] = $payements->toArray();
       $jsonData["total"] = $payements->count();
       return response()->json($jsonData);
    }

    public function listePayementTaxeByPeriode($date1, $date2){
        $debut = Carbon::createFromFormat('d-m-Y', $date1);
        $fin = Carbon::createFromFormat('d-m-Y', $date2);
    }
    
    public function listePayementTaxeByContribuablePeriode($contribuable,$date1, $date2){
        $debut = Carbon::createFromFormat('d-m-Y', $date1);
        $fin = Carbon::createFromFormat('d-m-Y', $date2);
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
        if ($request->isMethod('post') && $request->input('declaration_activite_id')) {

                $data = $request->all(); 
                
            try {
                
                if(Auth::user()->role=='Caissier'){
                    //Recupértion de la caisse dans la session
                    if($request->session()->has('session_caisse_ouverte')){
                            $caisse_ouverte_id = $request->session()->get('session_caisse_ouverte');
                            $caisse_ouverte = CaisseOuverte::find($caisse_ouverte_id);
                    }
                    if(!$caisse_ouverte or $caisse_ouverte->date_fermeture != null){
                            return response()->json(["code" => 0, "msg" => "Cette caisse est fermée", "data" => NULL]);
                    }
                }else{
                    $caisse  = Caisse::find($data['caisse']);
                    
                    if(!$caisse or $caisse->ouvert==0){
                        return response()->json(["code" => 0, "msg" => "Cette caisse est fermée", "data" => NULL]);
                    }
                    
                    $caisse_ouverte = CaisseOuverte::where([['caisse_id',$caisse->id],['date_fermeture',null]])->first();
              
                }
                
                $maxId = DB::table('payement_taxes')->max('id');
                $annee = date("Y");
                $numero_id = sprintf("%06d", ($maxId + 1));
                
                $payementTaxe = new PayementTaxe;
                $payementTaxe->numero_ticket = $annee.$numero_id;
                $payementTaxe->declaration_activite_id = $data['declaration_activite_id'];
                $payementTaxe->payement_effectuer_par = $data['payement_effectuer_par'];
                $payementTaxe->montant = $data['montant'];
                $payementTaxe->date_payement = Carbon::createFromFormat('d-m-Y', $data['date_payement']);
                $payementTaxe->date_prochain_payement = Carbon::createFromFormat('d-m-Y', $data['date_prochain_payement']);
                $payementTaxe->caisse_ouverte_id = $caisse_ouverte->id;
                $payementTaxe->created_by = Auth::user()->id;
                $payementTaxe->save();
                
                if($payementTaxe){
                    $caisse_ouverte->entree = $caisse_ouverte->entree + $payementTaxe->montant;
                    $caisse_ouverte->updated_by = Auth::user()->id;
                    $caisse_ouverte->save();
                }
                
                $jsonData["data"] = json_decode($payementTaxe);
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
     * @param  \App\PayementTaxe  $payementTaxe
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        $payementTaxe = PayementTaxe::find($id);
        if($payementTaxe){
            try {
                
                $data = $request->all();
                
                if(Auth::user()->role=='Caissier'){
                    //Recupértion de la caisse dans la session
                    if($request->session()->has('session_caisse_ouverte')){
                            $caisse_ouverte_id = $request->session()->get('session_caisse_ouverte');
                            $caisse_ouverte = CaisseOuverte::find($caisse_ouverte_id);
                    }
                    if(!$caisse_ouverte or $caisse_ouverte->date_fermeture != null){
                            return response()->json(["code" => 0, "msg" => "Cette caisse est fermée", "data" => NULL]);
                    }
                }else{
                    $caisse  = Caisse::find($data['caisse']);
                    
                    if(!$caisse or $caisse->ouvert==0){
                        return response()->json(["code" => 0, "msg" => "Cette caisse est fermée", "data" => NULL]);
                    }
                    
                    $caisse_ouverte = CaisseOuverte::where([['caisse_id',$caisse->id],['date_fermeture',null]])->first();
              
                }
                
                $caisse_ouverte->entree = $caisse_ouverte->entree - $payementTaxe->montant;
                $caisse_ouverte->save();
       
                $payementTaxe->declaration_activite_id = $data['declaration_activite_id'];
                $payementTaxe->payement_effectuer_par = $data['payement_effectuer_par'];
                $payementTaxe->montant = $data['montant'];
                $payementTaxe->date_payement = Carbon::createFromFormat('d-m-Y', $data['date_payement']);
                $payementTaxe->date_prochain_payement = Carbon::createFromFormat('d-m-Y', $data['date_prochain_payement']);
                $payementTaxe->caisse_ouverte_id = $caisse_ouverte->id;
                $payementTaxe->updated_by = Auth::user()->id;
                $payementTaxe->save();
                
                if($payementTaxe){
                    $caisse_ouverte->entree = $caisse_ouverte->entree + $data['montant'];
                    $caisse_ouverte->updated_by = Auth::user()->id;
                    $caisse_ouverte->save();
                }
       
            $jsonData["data"] = json_decode($payementTaxe);
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
     * @param  \App\PayementTaxe  $payementTaxe
     * @return Response
     */
    public function destroy($id)
    {
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
         
         $payementTaxe = PayementTaxe::find($id);
            if($payementTaxe){
                try {
               
                $caisseOuverte = CaisseOuverte::find($payementTaxe->caisse_ouverte_id);
                $caisseOuverte->entree = $caisseOuverte->entree - $payementTaxe->montant;
                $caisseOuverte->save();
                        
                $payementTaxe->update(['deleted_by' => Auth::user()->id]);
                $payementTaxe->delete();
                
                $jsonData["data"] = json_decode($payementTaxe);
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
    
    //Fonction pour recuperer les infos de Helpers
    public function infosConfig(){
        $get_configuration_infos = \App\Helpers\ConfigurationHelper\Configuration::get_configuration_infos(1);
        return $get_configuration_infos;
    }
     public function premierLetre(){
        $n=substr($this->infosConfig()->commune,0,1); 
        if($n=='A' || $n=='E' || $n=='I' || $n=='O' || $n=='U' || $n=='Y'){ 
            $d = "d'";
            
        }else{
            $d = "de ";
        }
        return $d;
    }
    //Eatat 
    public function facturePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->facture($id));
        $info_payement = PayementTaxe::find($id);
        return $pdf->stream('facture_'.$info_payement->numero_ticket.'.pdf');
    }
    
    public function facture($id){
        
       $info_facture= PayementTaxe::where([['payement_taxes.deleted_at', NULL],['payement_taxes.id',$id]])
                                    ->join('declaration_activites','declaration_activites.id','=','payement_taxes.declaration_activite_id')
                                    ->join('type_taxes','type_taxes.id','=','declaration_activites.type_taxe_id')
                                    ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->join('caisse_ouvertes','caisse_ouvertes.id','=','payement_taxes.caisse_ouverte_id')
                                    ->join('users','users.id','=','caisse_ouvertes.user_id')
                                    ->join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                                    ->select('payement_taxes.*','type_taxes.libelle_type_taxe','declaration_activites.nom_structure','users.full_name','caisses.libelle_caisse','contribuables.nom_complet as nom_complet_contribuable',DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                                    ->orderBy('payement_taxes.date_payement', 'DESC')
                                    ->first();
   
        $outPut = $this->header();
        $outPut .= '<div class="container-table" font-size:12px;><h3 align="center"><u>Payement de taxe</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <td cellspacing="0" border="2" width="50%" align="letf">
                                Date : <b>'.$info_facture->date_payements.'</b><br/>
                                Caisse : <b>'.$info_facture->libelle_caisse.'</b><br/>
                                Caissier(e) <b>: '.$info_facture->full_name.' </b>
                            </td>
                            <td cellspacing="0" border="2" width="50%" align="letf">
                                Facture N° : <b>'.$info_facture->numero_ticket.'</b><br/>
                                Contribuable : <b>'.$info_facture->nom_complet_contribuable.'</b><br/>
                                Structure <b>: <b>'.$info_facture->nom_structure.'</b>
                            </td>
                        </tr>
                        <tr>
                            <th cellspacing="0" border="2" width="70%" align="center">Description</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Montant</th>
                        </tr>
                        <tr>
                            <td cellspacing="0" border="2" align="letf">
                                &nbsp;&nbsp;Payement de '.$info_facture->libelle_type_taxe.'
                            </td>
                            <td cellspacing="0" border="2" align="letf">
                                 '.number_format($info_facture->montant, 0, ',', ' ').'
                            </td>
                        </tr>';
        
        $outPut .='</table></div>';
        $outPut.='Montant total : <b> '.number_format($info_facture->montant, 0, ',', ' ').' F CFA</b><br/>';
       
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Header and footer des pdf pour les listes dans tableau
    public function header(){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
     
        $header = '<html>
                    <head>
                        <style>
                          @page{
                                margin: 70px 25px;
                                }
                            header{
                                    position: absolute;
                                    top: -60px;
                                    left: 0px;
                                    right: 0px;
                                    height:40px;
                                }
                                .fixed-header-left{
                                            width: 35%;
                                            height:30%;
                                            position: absolute; 
                                            top: 0;
                                            padding: 10px 0;
                                            text-align:center;
                                        }
                                .fixed-header-right{
                                            width: 35%;
                                            height:7%;
                                            float: right;
                                            position: absolute;
                                            top: 0;
                                            padding: 10px 0;
                                            text-align:center;
                                        }
                            .container-table{        
                                            margin:125px 0;
                                            width: 100%;
                                        }
                            .fixed-footer{.
                                width : 100%;
                                position: fixed; 
                                bottom: -28; 
                                left: 0px; 
                                right: 0px;
                                height: 30px; 
                                text-align:center;
                            }
                            .fixed-footer-right{
                                position: absolute; 
                                bottom: -125; 
                                height: 0; 
                                font-size:13px;
                                float : right;
                            }
                            .page-number:before {
                                            
                            }
                        </style>
                    </head>
                    .<script type="text/php">
                    if (isset($pdf)){
                        $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                        $size = 10;
                        $font = $fontMetrics->getFont("Verdana");
                        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                        $x = ($pdf->get_width() - $width) / 2;
                        $y = $pdf->get_height() - 35;
                        $pdf->page_text($x, $y, $text, $font, $size);
                    }
                </script>
        <body>
        <header>
            <div class="fixed-header-left">
             <b>COMMUNE '.strtoupper($this->premierLetre().''.$commune).'</b><br/>
                   <img src='.$this->infosConfig()->logo.' width="100" height="100"><br/> 
                    <b> Mairie '.$this->premierLetre().''.$this->infosConfig()->commune.'</b><br/>';
                    if($this->infosConfig()->adresse_marie != null) {
                        $header .= 'Adresse: '.$this->infosConfig()->adresse_marie.'<br/>';
                    }
                    if ($this->infosConfig()->telephone_mairie != null) {
                        $header .='Tel : '.$this->infosConfig()->telephone_mairie.'<br/>';
                    }
                    if ($this->infosConfig()->fax_mairie != null) {
                        $header .='Fax : '.$this->infosConfig()->fax_mairie.'<br/>';
                    }
                    if ($this->infosConfig()->site_web_mairie != null) {
                        $header.=''.$this->infosConfig()->site_web_mairie.'<br/> ';
                    }
                $header.='</div>
                    <div class="fixed-header-right">
                       <b> REPUBLIQUE DE COTE D\'IVOIRE<br/> 
                        Union-Discipline-Travail<hr width="50"/></b>
                    </div>
        </header>';   
        return $header;
    }
    
    
    public function footer(){
        $footer ="<div class='fixed-footer'>
                        <div class='page-number'></div>
                    </div>
                    <div class='fixed-footer-right'>
                     <i> Editer le ".date('d-m-Y')."</i>
                    </div>
            </body>
        </html>";
        return $footer;
    }
}
