<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatVieEntretien;
use App\Models\Ecivil\EnfantEnCharge;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CertificatVieEntretienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $naissances = DB::table('naissances')->select('naissances.numero_acte_naissance','naissances.id',DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))->Where('deleted_at', NULL)->orderBy('id', 'desc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat de vie et d'entretien";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-vie-entretien.index',compact('fonctions','naissances', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeCertificatVieEntretien()
    {
        $certificats = CertificatVieEntretien::with('fonction')
                ->Where('certificat_vie_entretiens.deleted_at', NULL) 
                ->select('certificat_vie_entretiens.*',DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vie_entretiens.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatVieEntretienByName($name){
        $certificats = CertificatVieEntretien::with('fonction')
                ->Where([['certificat_vie_entretiens.deleted_at', NULL],['certificat_vie_entretiens.nom_complet_personne','like','%'.$name.'%']]) 
                ->select('certificat_vie_entretiens.*',DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vie_entretiens.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatVieEntretienByPieceIdentite($numero){
        $certificats = CertificatVieEntretien::with('fonction')
                ->Where([['certificat_vie_entretiens.deleted_at', NULL],['certificat_vie_entretiens.numero_piece_personne','like','%'.$numero.'%']]) 
                 ->select('certificat_vie_entretiens.*',DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vie_entretiens.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatVieEntretienByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
         $certificats = CertificatVieEntretien::with('fonction')
                ->Where('certificat_vie_entretiens.deleted_at', NULL) 
                ->whereDate('certificat_vie_entretiens.date_demande_certificat','=', $date)
                ->orWhereDate('certificat_vie_entretiens.date_naissance','=', $date)
                ->select('certificat_vie_entretiens.*',DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vie_entretiens.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vie_entretiens.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('numero_extrait_enfants')) {
            $data = $request->all(); 
        try{
              //Enregistrement 
                $certificatVieEntretien = new CertificatVieEntretien;
                $certificatVieEntretien->numero_piece_personne = isset($data['numero_piece_personne']) && !empty($data['numero_piece_personne'])?$data['numero_piece_personne']:null;
                $certificatVieEntretien->contact_personne = isset($data['contact_personne']) && !empty($data['contact_personne'])?$data['contact_personne']:null;
                $certificatVieEntretien->adresse_personne = $data['adresse_personne'];
                $certificatVieEntretien->nom_complet_personne = $data['nom_complet_personne'];
                $certificatVieEntretien->date_demande_certificat = now();
                $certificatVieEntretien->montant = isset($data['montant']) && !empty($data['montant'])?$data['montant']:0;
                $certificatVieEntretien->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $certificatVieEntretien->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id'])? $data['fonction_id'] : null; 
                $certificatVieEntretien->lieu_naissance = $data['lieu_naissance'];
                $certificatVieEntretien->naissance_id = isset($data['naissance_id']) && !empty($data['naissance_id']) ? $data['naissance_id'] : Null;
                $certificatVieEntretien->numero_acte_naissance_personne = isset($data['numero_acte_naissance_personne']) && !empty($data['numero_acte_naissance_personne']) ? $data['numero_acte_naissance_personne'] : Null;
                $certificatVieEntretien->etat_civil_naissance = isset($data['etat_civil_naissance']) && !empty($data['etat_civil_naissance']) ? TRUE: FALSE;
                $certificatVieEntretien->created_by = Auth::user()->id;
                $certificatVieEntretien->save();
                
                if($certificatVieEntretien && !empty($data["numero_extrait_enfants"])){
                    //enregistrement des enfants de la personne 
                    $numero_extrait_enfants = $data["numero_extrait_enfants"];
                    $nom_complet_enfants = $data["nom_complet_enfants"];
                    $date_naissance_enfants = $data["date_naissance_enfants"];
                    $lieu_naissance_enfants = $data["lieu_naissance_enfants"];
                    
                    foreach($nom_complet_enfants as $index => $nom_complet_enfant) {
                        $enfantEnCharge = new EnfantEnCharge();
                        $enfantEnCharge->numero_extrait_enfant = $numero_extrait_enfants[$index];
                        $enfantEnCharge->nom_complet_enfant = $nom_complet_enfant;
                        $enfantEnCharge->lieu_naissance_enfant = $lieu_naissance_enfants[$index];
                        $enfantEnCharge->date_naissance = Carbon::createFromFormat('d-m-Y', $date_naissance_enfants[$index]);
                        $enfantEnCharge->certificat_vie_entretien_id = $certificatVieEntretien->id;
                        $enfantEnCharge->created_by = Auth::user()->id;
                        $enfantEnCharge->save();
                    }
                }
                
                $jsonData["data"] = json_decode($certificatVieEntretien);
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
     * @param  \App\CertificatVieEntretien  $certificatVieEntretien
     * @return Response
     */
    public function update(Request $request, CertificatVieEntretien $certificatVieEntretien)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($certificatVieEntretien) {
            $data = $request->all(); 
        try{
                $certificatVieEntretien->numero_piece_personne = isset($data['numero_piece_personne']) && !empty($data['numero_piece_personne'])?$data['numero_piece_personne']:null;
                $certificatVieEntretien->contact_personne = isset($data['contact_personne']) && !empty($data['contact_personne'])?$data['contact_personne']:null;
                $certificatVieEntretien->adresse_personne = $data['adresse_personne'];
                $certificatVieEntretien->nom_complet_personne = $data['nom_complet_personne'];
                $certificatVieEntretien->date_demande_certificat = now();
                $certificatVieEntretien->montant = isset($data['montant']) && !empty($data['montant'])?$data['montant']:0;
                $certificatVieEntretien->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $certificatVieEntretien->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id'])? $data['fonction_id'] : null; 
                $certificatVieEntretien->lieu_naissance = $data['lieu_naissance'];
                $certificatVieEntretien->naissance_id = isset($data['naissance_id']) && !empty($data['naissance_id']) ? $data['naissance_id'] : Null;
                $certificatVieEntretien->numero_acte_naissance_personne = isset($data['numero_acte_naissance_personne']) && !empty($data['numero_acte_naissance_personne']) ? $data['numero_acte_naissance_personne'] : Null;
                $certificatVieEntretien->etat_civil_naissance = isset($data['etat_civil_naissance']) && !empty($data['etat_civil_naissance']) ? TRUE: FALSE;
                $certificatVieEntretien->updated_by = Auth::user()->id;
                $certificatVieEntretien->save();
                
                $jsonData["data"] = json_decode($certificatVieEntretien);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\CertificatVieEntretien  $certificatVieEntretien
     * @return Response
     */
    public function destroy(CertificatVieEntretien $certificatVieEntretien)
    {
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatVieEntretien){
                try {
                    $certificatVieEntretien->update(['deleted_by' => Auth::user()->id]);
                    $certificatVieEntretien->delete();
                    $jsonData["data"] = json_decode($certificatVieEntretien);
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
    public function ficheCertificatVieEntretienPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheCertificatVieEntretien($id));
        return $pdf->stream('certificat_vie_entretien_'.$id.'_'.date('d/m/Y').'.pdf');
    }

    public function ficheCertificatVieEntretien($id){
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
        $infos = CertificatVieEntretien::where([['certificat_vie_entretiens.deleted_at', NULL],['certificat_vie_entretiens.id', $id]])
                ->join('fonctions','fonctions.id','=','certificat_vie_entretiens.fonction_id') 
                ->select('certificat_vie_entretiens.*','fonctions.libelle_fonction')
                ->orderBy('certificat_vie_entretiens.id', 'DESC')
                ->first();
    
        $content = "<div class='fixed-content'> 
                      <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE VIE ET D'ENTRETIEN</u></b></p>
                      <p>Le maire de la commune <b>".$this->premierLetre()."".$this->infosConfig()->commune."</b> soussigné certifie que l'/les enfant(s) ci après désignés de Mme/Mlle/M <b>".$infos->nom_complet_personne."</b></p>  
                   ";
        $enfants = EnfantEnCharge::where([['enfant_en_charges.deleted_at', NULL],['enfant_en_charges.certificat_vie_entretien_id', $infos->id]]) 
                ->select('enfant_en_charges.*',DB::raw('DATE_FORMAT(enfant_en_charges.date_naissance, "%d-%m-%Y") as date_naissances'))
                ->orderBy('enfant_en_charges.date_naissance', 'ASC')
                ->get();
        foreach($enfants as $enfant){
            $content.="<p style='width:100%;'><b>".$enfant->nom_complet_enfant."</b>    Né(e) le   <b>".$enfant->date_naissances." </b>  à ".$enfant->lieu_naissance_enfant."</p>";
        }
        $content.="<br/><br/><p>Sont vivants(es) et entretenu(s) par le père, la mère ou le tuteur.<br/><br/></p>";
        $content.="<p style='float:right;bottom:0;'>Fait à ".$this->infosConfig()->commune.", le ".date("d/m/Y")."</p><br/><br/><br/>";
        $content.="</div>";
        return $content;
    }
}
