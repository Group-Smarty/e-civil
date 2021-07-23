<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatNonDivorce;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificatNonDivorceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat de concubinage";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-non-divorces.index',compact('fonctions', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    public function listeCertificatNonDivorce(){
        $certificats = CertificatNonDivorce::where('certificat_non_divorces.deleted_at', NULL) 
                ->select('certificat_non_divorces.*',DB::raw('DATE_FORMAT(certificat_non_divorces.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_divorces.date_mariage, "%d-%m-%Y") as date_mariages'))
                ->orderBy('certificat_non_divorces.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }

    public function listeCertificatNonDivorceByName($name)
    {
       $certificats = CertificatNonDivorce::where([['certificat_non_divorces.deleted_at', NULL],['certificat_non_divorces.nom_complet_demandeur','like','%'.$name.'%']]) 
                ->select('certificat_non_divorces.*',DB::raw('DATE_FORMAT(certificat_non_divorces.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_divorces.date_mariage, "%d-%m-%Y") as date_mariages'))
                ->orderBy('certificat_non_divorces.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
     public function listeCertificatNonDivorceByDate($dates)
    {
         $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = CertificatNonDivorce::where('certificat_non_divorces.deleted_at', NULL) 
                ->select('certificat_non_divorces.*',DB::raw('DATE_FORMAT(certificat_non_divorces.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_divorces.date_mariage, "%d-%m-%Y") as date_mariages'))
                ->whereDate('certificat_non_divorces.date_demande_certificat',$date)
                ->orderBy('certificat_non_divorces.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('nom_complet_demandeur')) {
            $data = $request->all(); 
        try{
                //Enregistrement 
                $certificatNonDivorce = new CertificatNonDivorce;
                $certificatNonDivorce->nom_complet_homme = $data['nom_complet_homme'];
                $certificatNonDivorce->nom_complet_femme = $data['nom_complet_femme'];
                $certificatNonDivorce->profession_homme = $data['profession_homme'];
                $certificatNonDivorce->profession_femme = $data['profession_femme'];
                $certificatNonDivorce->numero_acte_mariage = $data['numero_acte_mariage'];
                $certificatNonDivorce->etat_civil_mariage = $data['etat_civil_mariage'];
                $certificatNonDivorce->date_mariage = Carbon::createFromFormat('d-m-Y', $data['date_mariage']);
                $certificatNonDivorce->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatNonDivorce->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonDivorce->date_demande_certificat =now();
                $certificatNonDivorce->pere_homme = isset($data['pere_homme']) && !empty($data['pere_homme'])?$data['pere_homme']:null;
                $certificatNonDivorce->mere_homme = isset($data['mere_homme']) && !empty($data['mere_homme'])?$data['mere_homme']:null;
                $certificatNonDivorce->mere_femme = isset($data['mere_femme']) && !empty($data['mere_femme'])?$data['mere_femme']:null;
                $certificatNonDivorce->pere_femme = isset($data['pere_femme']) && !empty($data['pere_femme'])?$data['pere_femme']:null;
                $certificatNonDivorce->numero_acte_naissance = isset($data['numero_acte_naissance']) && !empty($data['numero_acte_naissance'])?$data['numero_acte_naissance']:null;
                $certificatNonDivorce->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatNonDivorce->montant = $data['montant'];
                $certificatNonDivorce->created_by = Auth::user()->id;
                $certificatNonDivorce->save();
                $jsonData["data"] = json_decode($certificatNonDivorce);
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
     * @param  \App\CertificatNonDivorce  $certificatNonDivorce
     * @return Response
     */
    public function update(Request $request,$id)
    {
        $certificatNonDivorce = CertificatNonDivorce::find($id);
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatNonDivorce){
            try{
                $data = $request->all(); 
                $certificatNonDivorce->nom_complet_homme = $data['nom_complet_homme'];
                $certificatNonDivorce->nom_complet_femme = $data['nom_complet_femme'];
                $certificatNonDivorce->profession_homme = $data['profession_homme'];
                $certificatNonDivorce->profession_femme = $data['profession_femme'];
                $certificatNonDivorce->numero_acte_mariage = $data['numero_acte_mariage'];
                $certificatNonDivorce->etat_civil_mariage = $data['etat_civil_mariage'];
                $certificatNonDivorce->date_mariage = Carbon::createFromFormat('d-m-Y', $data['date_mariage']);
                $certificatNonDivorce->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatNonDivorce->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonDivorce->date_demande_certificat =now();
                $certificatNonDivorce->pere_homme = isset($data['pere_homme']) && !empty($data['pere_homme'])?$data['pere_homme']:null;
                $certificatNonDivorce->mere_homme = isset($data['mere_homme']) && !empty($data['mere_homme'])?$data['mere_homme']:null;
                $certificatNonDivorce->mere_femme = isset($data['mere_femme']) && !empty($data['mere_femme'])?$data['mere_femme']:null;
                $certificatNonDivorce->pere_femme = isset($data['pere_femme']) && !empty($data['pere_femme'])?$data['pere_femme']:null;
                $certificatNonDivorce->numero_acte_naissance = isset($data['numero_acte_naissance']) && !empty($data['numero_acte_naissance'])?$data['numero_acte_naissance']:null;
                $certificatNonDivorce->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatNonDivorce->montant = $data['montant'];
                $certificatNonDivorce->updated_by = Auth::user()->id;
                $certificatNonDivorce->save();
                $jsonData["data"] = json_decode($certificatNonDivorce);
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
     * @param  \App\CertificatNonDivorce  $certificatNonDivorce
     * @return Response
     */
    public function destroy($id)
    {
        $certificatNonDivorce = CertificatNonDivorce::find($id);
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatNonDivorce){
                try {
                    $certificatNonDivorce->update(['deleted_by' => Auth::user()->id]);
                    $certificatNonDivorce->delete();
                    $jsonData["data"] = json_decode($certificatNonDivorce);
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
    public function certificatNonDivorcePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->certificatNonDivorce($id));
        return $pdf->stream('certificat_non_divorce.pdf');
    }
    
    public function certificatNonDivorce($id){
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
               $header.=" </div>
                <div class='fixed-header-right'>
                   <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b>
                </div>";
        return $header;
    }
    
    public function content($id){
        $infos = CertificatNonDivorce::where([['certificat_non_divorces.deleted_at', NULL],['certificat_non_divorces.id',$id]]) 
                ->leftjoin('fonctions as fonctionFemme','fonctionFemme.id','=','certificat_non_divorces.profession_femme')
                ->leftjoin('fonctions as fonctionHomme','fonctionHomme.id','=','certificat_non_divorces.profession_homme')
                ->select('certificat_non_divorces.*','fonctionHomme.libelle_fonction as libelle_fonction_homme','fonctionFemme.libelle_fonction as libelle_fonction_femme',DB::raw('DATE_FORMAT(certificat_non_divorces.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_non_divorces.date_mariage, "%d/%m/%Y") as date_mariages'))
                ->first();

        $content = "<div class='fixed-content'> 
                        <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE NON DIVORCE</u></b></p>
                        <p>Le maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>, Officier de l'Etat-Civil</p>  
                        <p>Vu l’extrait d’acte de Naissance N° <b>".$infos->numero_acte_naissance."</b></p>  
                        <p>Vu l’Acte de Mariage N° <b>".$infos->numero_acte_mariage."</b> du Centre d’Etat-Civil de ".$infos->etat_civil_mariage."</p>
                        <p>Certifie que le Mariage contracté :</p>  
                        <p>Le <b>".$infos->date_mariages."</b> à <b>".$infos->etat_civil_mariage."</b></p>  
                        <p>Entre <b>".$infos->nom_complet_homme."</b></p> 
                        <p>Profession <b>".$infos->libelle_fonction_homme."</b></p>  
                        <p>Fils de <b>".$infos->pere_homme."</b> et de <b>".$infos->mere_homme."</p> 
                        <p>Et : <b>".$infos->nom_complet_femme."</b></p>
                        <p>Profession <b>".$infos->libelle_fonction_femme."</b></p> 
                        <p>Fille de <b>".$infos->pere_femme."</b> et de <b>".$infos->mere_femme."</p>
                        <p>N’a pas été dissous par le divorce.</p>  
                        <p>En foi de quoi le présent certificat est délivré pour servir et valoir ce que de droit.</p><br/> 
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
