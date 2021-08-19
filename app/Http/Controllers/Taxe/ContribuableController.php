<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Contribuable;
use App\Models\Taxes\DeclarationActivite;
use App\Models\Taxes\PayementTaxe;
use App\Models\Parametre\Nation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContribuableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $nations = DB::table('nations')->Where('deleted_at', NULL)->orderBy('libelle_nation', 'asc')->get();
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $communes = DB::table('communes')->Where('deleted_at', NULL)->orderBy('libelle_commune', 'asc')->get();
       $typePieces = DB::table('type_pieces')->Where('deleted_at', NULL)->orderBy('libelle_type_piece', 'asc')->get();
       
       $menuPrincipal = "Taxe";
       $titleControlleur = "Contribuables";
       $btnModalAjout ="TRUE";
       return view('taxe.contribuable.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','nations', 'typePieces','communes', 'fonctions')); 
    
    }
    
    public function vueDetail($id){
        $contribuable = Contribuable::find($id);
        $menuPrincipal = "Contribuables";
        $titleControlleur = "Fiche du contribuable : ".$contribuable->nom_complet;
        $btnModalAjout ="FALSE";
        return view('taxe.contribuable.details',compact('btnModalAjout','contribuable', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeContribuable(){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where('deleted_at', NULL)
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableByName($name){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['nom_complet','like','%'.$name.'%']])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableByNumero($numero){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['numero_identifiant','like','%'.$numero.'%']])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableByNation($nation){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['nation_id',$nation]])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function listeContribuableBySexe($sexe){
        $contribuables = Contribuable::with('fonction','nation','type_piece','commune')
                        ->select('contribuables.*',DB::raw('DATE_FORMAT(contribuables.date_naissance, "%d-%m-%Y") as date_naissances'))
                        ->Where([['deleted_at', NULL],['sexe',$sexe]])
                        ->orderBy('nom_complet', 'ASC')
                        ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function getContribuableByActivite($activite){
        $contribuables = DeclarationActivite::join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                                    ->select('contribuables.*')
                                    ->Where([['contribuables.deleted_at', NULL],['declaration_activites.id',$activite]])
                                    ->get();
       $jsonData["rows"] = $contribuables->toArray();
       $jsonData["total"] = $contribuables->count();
       return response()->json($jsonData);
    }
    
    public function getAllPayementTaxe($contribuable){

        $payements = PayementTaxe::with('declaration_activite')
                            ->join('declaration_activites', 'declaration_activites.id', '=', 'payement_taxes.declaration_activite_id')
                            ->join('contribuables', 'contribuables.id', '=', 'declaration_activites.contribuable_id')
                            ->select('payement_taxes.*', 'contribuables.nom_complet as nom_complet_contribuable', DB::raw('DATE_FORMAT(payement_taxes.date_prochain_payement, "%d-%m-%Y") as date_prochain_payements'), DB::raw('DATE_FORMAT(payement_taxes.date_payement, "%d-%m-%Y") as date_payements'))
                            ->Where([['payement_taxes.deleted_at', NULL],['contribuables.id', $contribuable]])
                            ->orderBy('payement_taxes.date_payement', 'DESC')
                            ->get();

        $jsonData["rows"] = $payements->toArray();
       $jsonData["total"] = $payements->count();
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
        if ($request->isMethod('post') && $request->input('nom_complet')) {

                $data = $request->all(); 

            try {
                
                $Contribuable = Contribuable::where('numero_piece', $data['numero_piece'])->first();
                if($Contribuable!=null){
                    return response()->json(["code" => 0, "msg" => "Cet contribuable est déjà enregistré, vérifier le numéro de la pièce d'identifiant", "data" => NULL]);
                }
                
                $year = date("Y");
                $maxId = DB::table('contribuables')->max('id');
                $numero = sprintf("%06d", ($maxId + 1));
                        
                $contribuable = new Contribuable;
                $contribuable->numero_identifiant = $numero.'-'.$year;
                $contribuable->nom_complet = $data['nom_complet'];
                $contribuable->sexe = $data['sexe'];
                $contribuable->contact = $data['contact'];
                $contribuable->numero_piece = $data['numero_piece'];
                $contribuable->situation_matrimoniale = $data['situation_matrimoniale'];
                $contribuable->commune_id = $data['commune_id'];
                $contribuable->type_piece_id = $data['type_piece_id'];
                $contribuable->nation_id = $data['nation_id'];
                $contribuable->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id']:null;
                $contribuable->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $contribuable->contact2 = isset($data['contact2']) && !empty($data['contact2']) ? $data['contact2']: Null;
                $contribuable->adresse = isset($data['adresse']) && !empty($data['adresse']) ? $data['adresse']: Null;
                $contribuable->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $contribuable->created_by = Auth::user()->id;
                $contribuable->save();
                $jsonData["data"] = json_decode($contribuable);
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
     * @param  \App\Contribuable  $contribuable
     * @return Response
     */
    public function update(Request $request, Contribuable $contribuable)
    {
         $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($contribuable){
            try {
                
                $data = $request->all();
       
                $contribuable->nom_complet = $data['nom_complet'];
                $contribuable->sexe = $data['sexe'];
                $contribuable->contact = $data['contact'];
                $contribuable->numero_piece = $data['numero_piece'];
                $contribuable->situation_matrimoniale = $data['situation_matrimoniale'];
                $contribuable->commune_id = $data['commune_id'];
                $contribuable->type_piece_id = $data['type_piece_id'];
                $contribuable->nation_id = $data['nation_id'];
                $contribuable->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id']:null;
                $contribuable->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $contribuable->contact2 = isset($data['contact2']) && !empty($data['contact2']) ? $data['contact2']: Null;
                $contribuable->adresse = isset($data['adresse']) && !empty($data['adresse']) ? $data['adresse']: Null;
                $contribuable->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $contribuable->updated_by = Auth::user()->id;
                $contribuable->save();
       
            $jsonData["data"] = json_decode($contribuable);
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
     * @param  \App\Contribuable  $contribuable
     * @return Response
     */
    public function destroy(Contribuable $contribuable)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($contribuable){
                try {
               
                $contribuable->update(['deleted_by' => Auth::user()->id]);
                $contribuable->delete();
                
                $jsonData["data"] = json_decode($contribuable);
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

    //Liste des contribuables
    public function listeContribuablePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeContribuables());
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_contribuables.pdf');
    }
    public function listeContribuables(){
        $outPut = $this->headerFiche();
        $outPut.= $this->footerFiche();
        return $outPut;
    }


    //Liste des contribuables par nations
    public function listeContribuableByNationPdf($nation){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeContribuableByNations($nation));
        $pdf->setPaper('A4', 'landscape');
        $infosNation = Nation::find($nation);
        return $pdf->stream('liste_contribuables_de_'.$infosNation->libelle_nation.'.pdf');
    }
    public function listeContribuableByNations($nation){
        $infosNation = Nation::find($nation);
        $outPut = $this->headerFiche();
        $outPut.= "contribuables ".$infosNation->libelle_nation;
        $outPut.= $this->footerFiche();
        return $outPut;
    }

    //Liste des contribuables par sexe
    public function listeContribuableBySexePdf($sexe){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeContribuableBySexes($sexe));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_contribuables_de_sexe_'.$sexe.'.pdf');
    }
    public function listeContribuableBySexes($sexe){
        $outPut = $this->headerFiche();
        $outPut.= "contribuables de sexe".$sexe;
        $outPut.= $this->footerFiche();
        return $outPut;
    }


    //Header and footer des pdf pour les listes dans tableau
    public function headerFiche(){
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
    
    public function footerFiche(){
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
