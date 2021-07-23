<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatNonNaissance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificatNonNaissanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat de non inscription sur les registres de naissance";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-non-naissance.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeCertificatNonNaissance()
    {
       $certificats = CertificatNonNaissance::where('certificat_non_naissances.deleted_at', NULL) 
                ->select('certificat_non_naissances.*',DB::raw('DATE_FORMAT(certificat_non_naissances.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_naissances.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
     public function listeCertificatNonNaissanceByName($name)
    {
       $certificats = CertificatNonNaissance::where([['certificat_non_naissances.deleted_at', NULL],['certificat_non_naissances.nom_complet_demandeur','like','%'.$name.'%']])
                ->orWhere([['certificat_non_naissances.deleted_at', NULL],['certificat_non_naissances.nom_complet_enfant','like','%'.$name.'%']])
                ->select('certificat_non_naissances.*',DB::raw('DATE_FORMAT(certificat_non_naissances.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_naissances.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
     public function listeCertificatNonNaissanceByDate($dates)
    {
       $date = Carbon::createFromFormat('d-m-Y', $dates);
       $certificats = CertificatNonNaissance::where('certificat_non_naissances.deleted_at', NULL) 
                ->select('certificat_non_naissances.*',DB::raw('DATE_FORMAT(certificat_non_naissances.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->whereDate('certificat_non_naissances.date_demande_certificat',$date)
               ->orderBy('certificat_non_naissances.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('nom_complet_enfant')) {
            $data = $request->all(); 
        try{
                //Enregistrement 
                $certificatNonNaissance = new CertificatNonNaissance;
                $certificatNonNaissance->nom_complet_enfant = $data['nom_complet_enfant'];
                $certificatNonNaissance->sexe = isset($data['sexe']) && !empty($data['sexe'])?$data['sexe']:"Masculin";
                $certificatNonNaissance->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatNonNaissance->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonNaissance->date_demande_certificat =now();
                $certificatNonNaissance->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatNonNaissance->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere'])?$data['nom_complet_pere']:null;
                $certificatNonNaissance->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere'])?$data['nom_complet_mere']:null;
                $certificatNonNaissance->montant = $data['montant'];
                $certificatNonNaissance->created_by = Auth::user()->id;
                $certificatNonNaissance->save();
                $jsonData["data"] = json_decode($certificatNonNaissance);
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
     * @param  \App\CertificatNonNaissance  $certificatNonNaissance
     * @return Response
     */
     public function update(Request $request,$id)
    {
        $certificatNonNaissance = CertificatNonNaissance::find($id);
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatNonNaissance){
            try{
                $data = $request->all(); 
                $certificatNonNaissance->nom_complet_enfant = $data['nom_complet_enfant'];
                $certificatNonNaissance->sexe = isset($data['sexe']) && !empty($data['sexe'])?$data['sexe']:"Masculin";
                $certificatNonNaissance->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatNonNaissance->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonNaissance->date_demande_certificat =now();
                $certificatNonNaissance->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatNonNaissance->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere'])?$data['nom_complet_pere']:null;
                $certificatNonNaissance->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere'])?$data['nom_complet_mere']:null;
                $certificatNonNaissance->montant = $data['montant'];
                $certificatNonNaissance->updated_by = Auth::user()->id;
                $certificatNonNaissance->save();
                $jsonData["data"] = json_decode($certificatNonNaissance);
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
     * @param  \App\CertificatNonNaissance  $certificatNonNaissance
     * @return Response
     */
     public function destroy($id)
    {
        $certificatNonNaissance = CertificatNonNaissance::find($id);
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatNonNaissance){
                try {
                    $certificatNonNaissance->update(['deleted_by' => Auth::user()->id]);
                    $certificatNonNaissance->delete();
                    $jsonData["data"] = json_decode($certificatNonNaissance);
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
    public function certificatNonNaissancePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->certificatNonNaissance($id));
        return $pdf->stream('certificat_non_naissance.pdf');
    }
    
    public function certificatNonNaissance($id){
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
            $header .= "    </div>
                <div class='fixed-header-right'>
                   <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b>
                </div>";
        return $header;
    }
    
    public function content($id){
        $infos = CertificatNonNaissance::where([['certificat_non_naissances.deleted_at', NULL],['certificat_non_naissances.id',$id]]) 
                ->select('certificat_non_naissances.*',DB::raw('DATE_FORMAT(certificat_non_naissances.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->first();
        $infos->sexe=="Masculin"? $sexe="Fils" : $sexe="Fille";
        $content = "<div class='fixed-content'> 
                        <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE NON INSCRIPTION SUR LES REGISTRES DE NAISSANCE</u></b></p>
                        <p>Le maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>, Officier de l'Etat-Civil</p>  
                        <p>certifie que la naissance de <b>".$infos->nom_complet_enfant."</b></p>  
                        <p>".$sexe." de <b>".$infos->nom_complet_pere."</b> et de <b>".$infos->nom_complet_mere."</b></p>
                        <p>n’a pas été inscrit sur les registres de déclaration de naissance de l’Etat-civil de la commune</p>  
                        <p>En foi de quoi, nous lui délivrons le présent certificat pour servir et valoir ce que de droit</p><br/> 
                        <p style='float:right;'>Fait à ".$this->infosConfig()->commune.", le ".date("d/m/Y")."</p><br/><br/><br/>
                        <span style='float:left;'><b><u>Les Témoins</u></b></span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style='float:right;'><b><u>Le Maire</u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/><br/>
                        <span style='float:left;'><b>1er............................................. <br/><br/>
                        <span style='float:left;'><b>2ème............................................. 
                   </div>";
        return $content;
    }
}
