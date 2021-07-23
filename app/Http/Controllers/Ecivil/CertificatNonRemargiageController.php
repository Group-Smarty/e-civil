<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatNonRemargiage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;

class CertificatNonRemargiageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat de non rémariage";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-non-remariage.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeCertificatNonRemargiage()
    {
       $certificats = CertificatNonRemargiage::where('certificat_non_remargiages.deleted_at', NULL) 
                ->select('certificat_non_remargiages.*',DB::raw('DATE_FORMAT(certificat_non_remargiages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_remargiages.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    public function listeCertificatNonRemargiageByName($name)
    {
       $certificats = CertificatNonRemargiage::where([['certificat_non_remargiages.deleted_at', NULL],['certificat_non_remargiages.interrese','like','%'.$name.'%']]) 
                ->select('certificat_non_remargiages.*',DB::raw('DATE_FORMAT(certificat_non_remargiages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_remargiages.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatNonRemargiageByPiece($piece){
        $certificats = CertificatNonRemargiage::where([['certificat_non_remargiages.deleted_at', NULL],['certificat_non_remargiages.numero_piece_demandeur','like','%'.$piece.'%']]) 
                ->select('certificat_non_remargiages.*',DB::raw('DATE_FORMAT(certificat_non_remargiages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_remargiages.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatNonRemargiageByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = CertificatNonRemargiage::where('certificat_non_remargiages.deleted_at', NULL) 
                ->whereDate('certificat_non_remargiages.date_demande_certificat',$date)
                ->select('certificat_non_remargiages.*',DB::raw('DATE_FORMAT(certificat_non_remargiages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_remargiages.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
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
        if ($request->isMethod('post') && $request->input('interrese')) {
            $data = $request->all(); 
        try{
                
                //Enregistrement 
                $certificatNonRemargiage = new CertificatNonRemargiage;
                $certificatNonRemargiage->interrese = $data['interrese'];
                $certificatNonRemargiage->sexe = $data['sexe'];
                $certificatNonRemargiage->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonRemargiage->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']:null;
                $certificatNonRemargiage->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur']) ? $data['numero_piece_demandeur']:null;
                $certificatNonRemargiage->nom_complet_temoin1 = $data['nom_complet_temoin1'];
                $certificatNonRemargiage->nom_complet_temoin2 = $data['nom_complet_temoin2'];
                $certificatNonRemargiage->date_demande_certificat = now();
                $certificatNonRemargiage->montant = $data['montant'];
                $certificatNonRemargiage->created_by = Auth::user()->id;
                $certificatNonRemargiage->save();
                
                $jsonData["data"] = json_decode($certificatNonRemargiage);
                return response()->json($jsonData);
            }catch (Exception $exc) {
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
     * @param  \App\CertificatNonRemargiage  $certificatNonRemargiage
     * @return Response
     */
    public function update(Request $request, CertificatNonRemargiage $certificatNonRemargiage)
    {
         $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatNonRemargiage){
            try{
                $data = $request->all(); 
                $certificatNonRemargiage->interrese = $data['interrese'];
                $certificatNonRemargiage->sexe = $data['sexe'];
                $certificatNonRemargiage->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonRemargiage->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']:null;
                $certificatNonRemargiage->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur']) ? $data['numero_piece_demandeur']:null;
                $certificatNonRemargiage->nom_complet_temoin1 = $data['nom_complet_temoin1'];
                $certificatNonRemargiage->nom_complet_temoin2 = $data['nom_complet_temoin2'];
                $certificatNonRemargiage->date_demande_certificat = now();
                $certificatNonRemargiage->montant = $data['montant'];
                $certificatNonRemargiage->updated_by = Auth::user()->id;
                $certificatNonRemargiage->save();
                $jsonData["data"] = json_decode($certificatNonRemargiage);
                return response()->json($jsonData);
            }catch(Exception $exc){
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
     * @param  \App\CertificatNonRemargiage  $certificatNonRemargiage
     * @return Response
     */
    public function destroy(CertificatNonRemargiage $certificatNonRemargiage)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatNonRemargiage){
                try {
                    $certificatNonRemargiage->update(['deleted_by' => Auth::user()->id]);
                    $certificatNonRemargiage->delete();
                    $jsonData["data"] = json_decode($certificatNonRemargiage);
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
    
    //Etat
    public function certificatNonRemariagePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->certificatNonRemariage($id));
        return $pdf->stream('certificat_non_remariage.pdf');
    }
    
    public function certificatNonRemariage($id){
        $outPut = $this->header();
        $outPut.= $this->content($id);
        return $outPut;
    }
    
    public function header(){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);

       $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               
                .fixed-header-left{
                    width: 35%;
                    height:30%;
                    text-align:center;
                    position: absolute; 
                    float: left;
                }
                .fixed-header-right{
                    width: 45%;
                    height:30%;
                    text-align:center;
                    position: absolute;
                    float: right;
                }
               
                .fixed-content{
                    margin:320px 0;
                     position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -20; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 100px; 
                }  
            </style>
            <body>
                <div class='fixed-header-left'>
                    COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
                             if($this->infosConfig()->adresse_marie != null) {
                    $header .= "Adresse: " . $this->infosConfig()->adresse_marie . "<br/>";
                }
        if ($this->infosConfig()->telephone_mairie != null) {
            $header .= "Tel : " . $this->infosConfig()->telephone_mairie . "<br/>";
        }
        if ($this->infosConfig()->fax_mairie != null) {
            $header .= "Fax : " . $this->infosConfig()->fax_mairie . "<br/>";
        }
        if ($this->infosConfig()->site_web_mairie != null) {
            $header .= "" . $this->infosConfig()->site_web_mairie . "<br/> ";
        } 
        $header .= "<br/> 
                    N°....................CHF/SG/SA
                </div>
                <div class='fixed-header-right'>
                   <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b>
                </div>";
        return $header;
    }
    
    public function content($id){
        $infos = CertificatNonRemargiage::where([['certificat_non_remargiages.deleted_at', NULL],['certificat_non_remargiages.id',$id]]) 
                ->select('certificat_non_remargiages.*',DB::raw('DATE_FORMAT(certificat_non_remargiages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->first();
        $infos->sexe=='Masculin' ? $prhase = "rémarié depuis le décès de sa femme" : $prhase = "rémariée depuis le décès de son mari";
        $infos->sexe=='Masculin' ? $que = "qu'il" : $que = "qu'elle";
        $infos->sexe=='Masculin' ? $interesse = "l'intéressé" : $interesse = "l'intéressée";
        $content = "<div class='fixed-content'> 
                         <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE NON REMARIAGE</u></b></p>
                      <p>Le maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b> sur la déclaration de ".$interesse." et l'attestaion des Sieurs</p>  
                      <p>1. <b>".$infos->nom_complet_temoin1."</b></p>  
                      <p>2. <b>".$infos->nom_complet_temoin2."</b></p>  
                      <p>Certifient que <b>".$infos->interrese."</b></p> 
                      <p>Ne s'est jamais ".$prhase." et ".$que." est en possession de ses droits civils.</p>  
                      <p>En fois de quoi, le présent certificat est délivré pour servir et valoir ce que de droit.</p><br/> 
                      <span style='text-align:left;'><b>Les témoins</b></span><span style='float:right;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."</span>
                      <p>1er</p><br/> 
                      <p>2e</p>
                   </div>";
        return $content;
    }
}
