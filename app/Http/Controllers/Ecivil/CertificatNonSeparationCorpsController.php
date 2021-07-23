<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatNonSeparationCorps;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificatNonSeparationCorpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat de non séparation de corps";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-non-separation-corsp.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

   public function listeCertificatNonSeparationCorps()
    {
       $certificats = CertificatNonSeparationCorps::where('certificat_non_separation_corps.deleted_at', NULL) 
                ->select('certificat_non_separation_corps.*',DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_deces, "%d-%m-%Y") as date_decess'))
                ->orderBy('certificat_non_separation_corps.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    public function listeCertificatNonSeparationCorpsByName($name)
    {
       $certificats = CertificatNonSeparationCorps::where([['certificat_non_separation_corps.deleted_at', NULL],['certificat_non_separation_corps.nom_complet_demandeur','like','%'.$name.'%']]) 
                ->select('certificat_non_separation_corps.*',DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_deces, "%d-%m-%Y") as date_decess'))
                ->orderBy('certificat_non_separation_corps.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatNonSeparationCorpsByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = CertificatNonSeparationCorps::where('certificat_non_separation_corps.deleted_at', NULL) 
                ->whereDate('certificat_non_separation_corps.date_demande_certificat',$date)
                ->select('certificat_non_separation_corps.*',DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_deces, "%d-%m-%Y") as date_decess'))
                ->orderBy('certificat_non_separation_corps.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('nom_complet_concerne')) {
            $data = $request->all(); 
        try{
                
                //Enregistrement 
                $certificatNonSeparationCorps = new CertificatNonSeparationCorps;
                $certificatNonSeparationCorps->nom_complet_concerne = $data['nom_complet_concerne'];
                $certificatNonSeparationCorps->sexe = $data['sexe'];
                $certificatNonSeparationCorps->date_mariage = Carbon::createFromFormat('d-m-Y', $data['date_mariage']);
                $certificatNonSeparationCorps->lieu_mariage = $data['lieu_mariage'];
                $certificatNonSeparationCorps->date_deces = isset($data['date_deces']) && !empty($data['date_deces']) ? Carbon::createFromFormat('d-m-Y', $data['date_deces']):null;
                $certificatNonSeparationCorps->lieu_deces = isset($data['lieu_deces']) && !empty($data['lieu_deces']) ? $data['lieu_deces']:null;
                $certificatNonSeparationCorps->nom_complet_conjoint = $data['nom_complet_conjoint'];
                $certificatNonSeparationCorps->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatNonSeparationCorps->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonSeparationCorps->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']:null;
                $certificatNonSeparationCorps->date_demande_certificat = now();
                $certificatNonSeparationCorps->montant = $data['montant'];
                $certificatNonSeparationCorps->created_by = Auth::user()->id;
                $certificatNonSeparationCorps->save();
                
                $jsonData["data"] = json_decode($certificatNonSeparationCorps);
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
     * @param  \App\CertificatNonSeparationCorps  $certificatNonSeparationCorps
     * @return Response
     */
     public function update(Request $request,$id)
    {
         $certificatNonSeparationCorps = CertificatNonSeparationCorps::find($id);
         
         $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatNonSeparationCorps){
            try{
                $data = $request->all(); 
                $certificatNonSeparationCorps->nom_complet_concerne = $data['nom_complet_concerne'];
                $certificatNonSeparationCorps->sexe = $data['sexe'];
                $certificatNonSeparationCorps->date_mariage = Carbon::createFromFormat('d-m-Y', $data['date_mariage']);
                $certificatNonSeparationCorps->lieu_mariage = $data['lieu_mariage'];
                 $certificatNonSeparationCorps->date_deces = isset($data['date_deces']) && !empty($data['date_deces']) ? Carbon::createFromFormat('d-m-Y', $data['date_deces']):null;
                $certificatNonSeparationCorps->lieu_deces = isset($data['lieu_deces']) && !empty($data['lieu_deces']) ? $data['lieu_deces']:null;
                $certificatNonSeparationCorps->nom_complet_conjoint = $data['nom_complet_conjoint'];
                $certificatNonSeparationCorps->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatNonSeparationCorps->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonSeparationCorps->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']:null;
                $certificatNonSeparationCorps->date_demande_certificat = now();
                $certificatNonSeparationCorps->montant = $data['montant'];
                $certificatNonSeparationCorps->updated_by = Auth::user()->id;
                $certificatNonSeparationCorps->save();
                $jsonData["data"] = json_decode($certificatNonSeparationCorps);
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
     * @param  \App\CertificatNonSeparationCorps  $certificatNonSeparationCorps
     * @return Response
     */
    public function destroy($id)
    {
        $certificatNonSeparationCorps = CertificatNonSeparationCorps::find($id);
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatNonSeparationCorps){
                try {
                    $certificatNonSeparationCorps->update(['deleted_by' => Auth::user()->id]);
                    $certificatNonSeparationCorps->delete();
                    $jsonData["data"] = json_decode($certificatNonSeparationCorps);
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
    public function certificatNonSeparationCorpsPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this-> certificatNonSeparationCorps($id));
        return $pdf->stream('certificat_non_separation_corps.pdf');
    }
    
    public function certificatNonSeparationCorps($id){
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
                    height:20%;
                    text-align:center;
                    position: absolute; 
                    float: left;
                }
                .fixed-header-right{
                    width: 45%;
                    height:20%;
                    text-align:center;
                    position: absolute;
                    float: right;
                }
               
                .fixed-content{
                    margin:260px 0;
                     position: absolute;
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
        $header .="</div>
                <div class='fixed-header-right'>
                   <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b>
                </div>";
        return $header;
    }
    
    public function content($id){
        $infos = CertificatNonSeparationCorps::where([['certificat_non_separation_corps.deleted_at', NULL],['certificat_non_separation_corps.id',$id]]) 
                ->select('certificat_non_separation_corps.*',DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_mariage, "%d/%m/%Y") as date_mariages'),DB::raw('DATE_FORMAT(certificat_non_separation_corps.date_deces, "%d/%m/%Y") as date_decess'))
                ->orderBy('certificat_non_separation_corps.id', 'DESC') 
                ->first();
        $infos->sexe=="Masculin" ? $epoux = $infos->nom_complet_concerne : $epoux = $infos->nom_complet_conjoint; 
        $infos->sexe=="Feminin" ?  $epouse = $infos->nom_complet_concerne : $epouse = $infos->nom_complet_conjoint; 
        $infos->sexe=="Feminin" ?  $civilite = "Dame" : $civilite = "Sieur"; 
        $infos->sexe=="Feminin" ?  $decede = "Décédée" : $decede = "Décédé"; 
        
        $content = "<div class='fixed-content'> 
                        <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE NON SEPARATION DE CORPS</u></b></p>
                        <p>Le maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>, Soussigné sur la déclaration de l’intéressée et l’attestation de : </p>  
                        <p>1. <b>".$epoux."</b></p>  
                        <p>2. <b>".$epouse."</b></p>  
                        <p>Certifie qu’aucune séparation de corps n’a été prononcée judiciairement contre</p>
                        <p>".$civilite." <b>".$infos->nom_complet_concerne."</b></p>  
                        <p>Qui a contracté mariage : le <b>".$infos->date_mariages."</b> à <b>".$infos->lieu_mariage."</b></p>  
                        <p>Avec <b>".$infos->nom_complet_conjoint."</b></p> 
                        <p>".$decede." le <b>".$infos->date_decess."</b> à <b>".$infos->lieu_deces."</b></p>  
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;En foi de quoi, le présent certificat lui est délivré pour servir et valoir ce que de droit.</p><br/> 
                        <p style='float:right;'>Fait à ".$this->infosConfig()->commune.", le ".date("d/m/Y")."</p><br/><br/><br/>
                        <span style='float:left;'><b><u>Les Témoins</u></b></span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style='float:right;'><b><u>Le Maire</u></b></span><br/><br/>
                        <span style='float:left;'><b>1er............................................. <br/><br/>
                        <span style='float:left;'><b>2ème............................................. 
                   </div>";
        return $content;
    }
}
