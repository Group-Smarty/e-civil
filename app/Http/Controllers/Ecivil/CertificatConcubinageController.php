<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatConcubinage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificatConcubinageController extends Controller
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
       return view('ecivil.certificat-concubinage.index',compact('fonctions', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeCertificatConcubinage()
    {
        $certificats = CertificatConcubinage::where('certificat_concubinages.deleted_at', NULL) 
                ->select('certificat_concubinages.*',DB::raw('DATE_FORMAT(certificat_concubinages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_mariage_coutumier, "%d-%m-%Y") as date_mariage_coutumiers'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_1, "%d-%m-%Y") as date_etablisssement_piece_temoins_1s'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_2, "%d-%m-%Y") as date_etablisssement_piece_temoins_2s'))
                ->orderBy('certificat_concubinages.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatConcubinageByName($name)
    {
        $certificats = CertificatConcubinage::where([['certificat_concubinages.deleted_at', NULL],['certificat_concubinages.nom_complet_demandeur','like','%'.$name.'%']]) 
                ->select('certificat_concubinages.*',DB::raw('DATE_FORMAT(certificat_concubinages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_mariage_coutumier, "%d-%m-%Y") as date_mariage_coutumiers'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_1, "%d-%m-%Y") as date_etablisssement_piece_temoins_1s'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_2, "%d-%m-%Y") as date_etablisssement_piece_temoins_2s'))
                ->orderBy('certificat_concubinages.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatConcubinageByDate($dates)
    {
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = CertificatConcubinage::where('certificat_concubinages.deleted_at', NULL) 
                ->select('certificat_concubinages.*',DB::raw('DATE_FORMAT(certificat_concubinages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_mariage_coutumier, "%d-%m-%Y") as date_mariage_coutumiers'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_1, "%d-%m-%Y") as date_etablisssement_piece_temoins_1s'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_2, "%d-%m-%Y") as date_etablisssement_piece_temoins_2s'))
                 ->whereDate('certificat_concubinages.date_demande_certificat',$date)
                ->orderBy('certificat_concubinages.id', 'DESC')
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
                $certificatConcubinage = new CertificatConcubinage;
                $certificatConcubinage->nom_complet_homme = $data['nom_complet_homme'];
                $certificatConcubinage->nom_complet_femme = $data['nom_complet_femme'];
                $certificatConcubinage->profession_homme = $data['profession_homme'];
                $certificatConcubinage->profession_femme = $data['profession_femme'];
                $certificatConcubinage->adresse_homme = $data['adresse_homme'];
                $certificatConcubinage->adresse_femme = $data['adresse_femme'];
                $certificatConcubinage->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatConcubinage->adresse_demandeur = $data['adresse_demandeur'];
                 $certificatConcubinage->lieu_mariage_coutumier = $data['lieu_mariage_coutumier'];
                $certificatConcubinage->adresse_commune = $data['adresse_commune'];
                $certificatConcubinage->date_demande_certificat =now();
                $certificatConcubinage->date_naissance_homme = Carbon::createFromFormat('d-m-Y', $data['date_naissance_homme']);
                $certificatConcubinage->date_naissance_femme = Carbon::createFromFormat('d-m-Y', $data['date_naissance_femme']);
                $certificatConcubinage->date_mariage_coutumier = Carbon::createFromFormat('d-m-Y', $data['date_mariage_coutumier']);
                $certificatConcubinage->nom_complet_temoins_1 = isset($data['nom_complet_temoins_1']) && !empty($data['nom_complet_temoins_1'])?$data['nom_complet_temoins_1']:null;
                $certificatConcubinage->nom_complet_temoins_2 = isset($data['nom_complet_temoins_2']) && !empty($data['nom_complet_temoins_2'])?$data['nom_complet_temoins_2']:null;
                $certificatConcubinage->profession_temoins_1 = isset($data['profession_temoins_1']) && !empty($data['profession_temoins_1'])?$data['profession_temoins_1']:null;
                $certificatConcubinage->profession_temoins_2 = isset($data['profession_temoins_2']) && !empty($data['profession_temoins_2'])?$data['profession_temoins_2']:null;
                $certificatConcubinage->adresse_temoins_1 = isset($data['adresse_temoins_1']) && !empty($data['adresse_temoins_1'])?$data['adresse_temoins_1']:null;
                $certificatConcubinage->adresse_temoins_2 = isset($data['adresse_temoins_2']) && !empty($data['adresse_temoins_2'])?$data['adresse_temoins_2']:null;
                $certificatConcubinage->numero_piece_temoins_1 = isset($data['numero_piece_temoins_1']) && !empty($data['numero_piece_temoins_1'])?$data['numero_piece_temoins_1']:null;
                $certificatConcubinage->numero_piece_temoins_2 = isset($data['numero_piece_temoins_2']) && !empty($data['numero_piece_temoins_2'])?$data['numero_piece_temoins_2']:null;
                $certificatConcubinage->date_etablisssement_piece_temoins_1 = isset($data['date_etablisssement_piece_temoins_1']) && !empty($data['date_etablisssement_piece_temoins_1'])?Carbon::createFromFormat('d-m-Y', $data['date_etablisssement_piece_temoins_2']):null;
                $certificatConcubinage->date_etablisssement_piece_temoins_2 = isset($data['date_etablisssement_piece_temoins_2']) && !empty($data['date_etablisssement_piece_temoins_2'])?Carbon::createFromFormat('d-m-Y', $data['date_etablisssement_piece_temoins_2']):null;
                $certificatConcubinage->lieu_etablisssement_piece_temoins_1 = isset($data['lieu_etablisssement_piece_temoins_1']) && !empty($data['lieu_etablisssement_piece_temoins_1'])?$data['lieu_etablisssement_piece_temoins_1']:null;
                $certificatConcubinage->lieu_etablisssement_piece_temoins_2 = isset($data['lieu_etablisssement_piece_temoins_2']) && !empty($data['lieu_etablisssement_piece_temoins_2'])?$data['lieu_etablisssement_piece_temoins_2']:null;
                $certificatConcubinage->piece_temoins_1_delivre_par = isset($data['piece_temoins_1_delivre_par']) && !empty($data['piece_temoins_1_delivre_par'])?$data['piece_temoins_1_delivre_par']:null;
                $certificatConcubinage->piece_temoins_2_delivre_par = isset($data['piece_temoins_2_delivre_par']) && !empty($data['piece_temoins_2_delivre_par'])?$data['piece_temoins_2_delivre_par']:null;
                $certificatConcubinage->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatConcubinage->montant = $data['montant'];
                $certificatConcubinage->created_by = Auth::user()->id;
                $certificatConcubinage->save();
                $jsonData["data"] = json_decode($certificatConcubinage);
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
     * @param  \App\CertificatConcubinage  $certificatConcubinage
     * @return Response
     */
    public function update(Request $request,$id)
    {
         $certificatConcubinage = CertificatConcubinage::find($id);
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatConcubinage){
            try{
                $data = $request->all(); 
                $certificatConcubinage->nom_complet_homme = $data['nom_complet_homme'];
                $certificatConcubinage->nom_complet_femme = $data['nom_complet_femme'];
                $certificatConcubinage->profession_homme = $data['profession_homme'];
                $certificatConcubinage->profession_femme = $data['profession_femme'];
                $certificatConcubinage->adresse_homme = $data['adresse_homme'];
                $certificatConcubinage->adresse_femme = $data['adresse_femme'];
                $certificatConcubinage->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatConcubinage->adresse_demandeur = $data['adresse_demandeur'];
                 $certificatConcubinage->lieu_mariage_coutumier = $data['lieu_mariage_coutumier'];
                $certificatConcubinage->adresse_commune = $data['adresse_commune'];
                $certificatConcubinage->date_demande_certificat =now();
                $certificatConcubinage->date_naissance_homme = Carbon::createFromFormat('d-m-Y', $data['date_naissance_homme']);
                $certificatConcubinage->date_naissance_femme = Carbon::createFromFormat('d-m-Y', $data['date_naissance_femme']);
                $certificatConcubinage->date_mariage_coutumier = Carbon::createFromFormat('d-m-Y', $data['date_mariage_coutumier']);
                $certificatConcubinage->nom_complet_temoins_1 = isset($data['nom_complet_temoins_1']) && !empty($data['nom_complet_temoins_1'])?$data['nom_complet_temoins_1']:null;
                $certificatConcubinage->nom_complet_temoins_2 = isset($data['nom_complet_temoins_2']) && !empty($data['nom_complet_temoins_2'])?$data['nom_complet_temoins_2']:null;
                $certificatConcubinage->profession_temoins_1 = isset($data['profession_temoins_1']) && !empty($data['profession_temoins_1'])?$data['profession_temoins_1']:null;
                $certificatConcubinage->profession_temoins_2 = isset($data['profession_temoins_2']) && !empty($data['profession_temoins_2'])?$data['profession_temoins_2']:null;
                $certificatConcubinage->adresse_temoins_1 = isset($data['adresse_temoins_1']) && !empty($data['adresse_temoins_1'])?$data['adresse_temoins_1']:null;
                $certificatConcubinage->adresse_temoins_2 = isset($data['adresse_temoins_2']) && !empty($data['adresse_temoins_2'])?$data['adresse_temoins_2']:null;
                $certificatConcubinage->numero_piece_temoins_1 = isset($data['numero_piece_temoins_1']) && !empty($data['numero_piece_temoins_1'])?$data['numero_piece_temoins_1']:null;
                $certificatConcubinage->numero_piece_temoins_2 = isset($data['numero_piece_temoins_2']) && !empty($data['numero_piece_temoins_2'])?$data['numero_piece_temoins_2']:null;
                $certificatConcubinage->date_etablisssement_piece_temoins_1 = isset($data['date_etablisssement_piece_temoins_1']) && !empty($data['date_etablisssement_piece_temoins_1'])?Carbon::createFromFormat('d-m-Y', $data['date_etablisssement_piece_temoins_2']):null;
                $certificatConcubinage->date_etablisssement_piece_temoins_2 = isset($data['date_etablisssement_piece_temoins_2']) && !empty($data['date_etablisssement_piece_temoins_2'])?Carbon::createFromFormat('d-m-Y', $data['date_etablisssement_piece_temoins_2']):null;
                $certificatConcubinage->lieu_etablisssement_piece_temoins_1 = isset($data['lieu_etablisssement_piece_temoins_1']) && !empty($data['lieu_etablisssement_piece_temoins_1'])?$data['lieu_etablisssement_piece_temoins_1']:null;
                $certificatConcubinage->lieu_etablisssement_piece_temoins_2 = isset($data['lieu_etablisssement_piece_temoins_2']) && !empty($data['lieu_etablisssement_piece_temoins_2'])?$data['lieu_etablisssement_piece_temoins_2']:null;
                $certificatConcubinage->piece_temoins_1_delivre_par = isset($data['piece_temoins_1_delivre_par']) && !empty($data['piece_temoins_1_delivre_par'])?$data['piece_temoins_1_delivre_par']:null;
                $certificatConcubinage->piece_temoins_2_delivre_par = isset($data['piece_temoins_2_delivre_par']) && !empty($data['piece_temoins_2_delivre_par'])?$data['piece_temoins_2_delivre_par']:null;
                $certificatConcubinage->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatConcubinage->montant = $data['montant'];
                $certificatConcubinage->updated_by = Auth::user()->id;
                $certificatConcubinage->save();
                $jsonData["data"] = json_decode($certificatConcubinage);
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
     * @param  \App\CertificatConcubinage  $certificatConcubinage
     * @return Response
     */
    public function destroy($id)
    {
        $certificatConcubinage = CertificatConcubinage::find($id);
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatConcubinage){
                try {
                    $certificatConcubinage->update(['deleted_by' => Auth::user()->id]);
                    $certificatConcubinage->delete();
                    $jsonData["data"] = json_decode($certificatConcubinage);
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
    public function certificatConcubinagePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->certificatConcubinage($id));
        return $pdf->stream('certificat_concubinage.pdf');
    }
    
    public function certificatConcubinage($id){
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
        $infos = CertificatConcubinage::where([['certificat_concubinages.deleted_at', NULL],['certificat_concubinages.id',$id]]) 
                ->leftjoin('fonctions as fonctionFemme','fonctionFemme.id','=','certificat_concubinages.profession_femme')
                ->leftjoin('fonctions as fonctionHomme','fonctionHomme.id','=','certificat_concubinages.profession_homme')
                ->leftjoin('fonctions as professionTemoin1','professionTemoin1.id','=','certificat_concubinages.profession_temoins_1')
                ->leftjoin('fonctions as professionTemoin2','professionTemoin2.id','=','certificat_concubinages.profession_temoins_2')
                ->select('certificat_concubinages.*','fonctionFemme.libelle_fonction as libelle_fonction_femme','fonctionHomme.libelle_fonction as libelle_fonction_homme','professionTemoin2.libelle_fonction as libelle_fonction_temoins_2','professionTemoin1.libelle_fonction as libelle_fonction_temoins_1',DB::raw('DATE_FORMAT(certificat_concubinages.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(certificat_concubinages.date_mariage_coutumier, "%d-%m-%Y") as date_mariage_coutumiers'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_1, "%d-%m-%Y") as date_etablisssement_piece_temoins_1s'),DB::raw('DATE_FORMAT(certificat_concubinages.date_etablisssement_piece_temoins_2, "%d-%m-%Y") as date_etablisssement_piece_temoins_2s'))
                ->first();

        $content = "<div class='fixed-content'> 
                        <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE CONCUBINAGE</u></b></p>
                        <p>Le maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b> certifie que Mr <b>".$infos->nom_complet_homme."</b></p>  
                        <p>Profession <b>".$infos->libelle_fonction_homme."</b> Domicilié à <b>".$infos->adresse_homme."</b> et </p>  
                        <p>Mademoiselle <b>".$infos->nom_complet_femme."</b> née le <b>".$infos->date_naissance_femmes."</b></p>  
                        <p>Profession <b>".$infos->libelle_fonction_femme."</b> Domicilié à <b>".$infos->adresse_femme."</b></p>  
                        <p>sont mariés coutumièrement à <b>".$infos->lieu_mariage_coutumier."</b> le <b>".$infos->date_mariage_coutumiers."</b></p> 
                        <p>En présence de <b>".$infos->nom_complet_temoins_1."</b> de profession <b>".$infos->libelle_fonction_temoins_1."</b> Domicilié(e) à <b>".$infos->adresse_temoins_1."</b></p>  
                        <p>CNI, Passeport ou Permis de conduire N° <b>".$infos->numero_piece_temoins_1."</b> Du <b>".$infos->date_etablisssement_piece_temoins_1s."</b> délivré(e) par <b>".$infos->piece_temoins_1_delivre_par."</b></p>  
                        <p>Et <b>".$infos->nom_complet_temoins_2."</b> de profession <b>".$infos->libelle_fonction_temoins_2."</b> Domicilié(e) à <b>".$infos->adresse_temoins_2."</b></p>  
                        <p>CNI, Passeport ou Permis de conduire N° <b>".$infos->numero_piece_temoins_2."</b> Du <b>".$infos->date_etablisssement_piece_temoins_2s."</b> délivré(e) par <b>".$infos->piece_temoins_2_delivre_par."</b></p>  
                        <p>Résident ensemble depuis le <b>".$infos->date_mariage_coutumiers."</b> à <b>".$infos->adresse_commune."</b> République de Côte d’Ivoire</p>  
                        <p>En foi de quoi, il est délivré ce certificat pour servir et valoir ce que de droit.</p><br/> 
                        <p style='float:right;'>Fait à ".$this->infosConfig()->commune.", le ".date("d/m/Y")."</p><br/><br/><br/>
                        <span style='float:left;'><b><u>Les Déclarants</u></b></span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span><b><u>Les Témoins</u></b></span><span style='float:right;'><b><u>L’Officier de l’état-civil</u></b></span><br/><br/>
                         <span style='float:left;'><b>............................................. 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         .............................................</b></span><br/>
                          <span style='float:left;'><b>............................................. 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         .............................................</b></span>
                   </div>";
        return $content;
    }
}
