<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Billetage;
use App\Models\Taxes\CaisseOuverte;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BilletageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $caisses = DB::table('caisses')->Where('deleted_at', NULL)->orderBy('libelle_caisse', 'asc')->get();
       
       $caissiers = CaisseOuverte::where([['caisse_ouvertes.deleted_at', NULL],['caisse_ouvertes.date_fermeture','!=',null]])
                                                    ->join('users','users.id','=','caisse_ouvertes.user_id')
                                                    ->select('users.full_name','users.id')
                                                   ->groupBy('users.id')
                                                    ->get();
               
       $menuPrincipal = "Taxe";
       $titleControlleur = "Historique des caisses";
       $btnModalAjout ="FALSE";
       return view('taxe.payement-taxe.histotique-caisses',compact('caisses','caissiers', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function listBilletage()
    {
        $caisses = CaisseOuverte::where([['caisse_ouvertes.deleted_at', NULL],['caisse_ouvertes.date_fermeture','!=',null]])
                                    ->join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                                    ->join('users','users.id','=','caisse_ouvertes.user_id')
                                    ->select('caisse_ouvertes.*','users.full_name','caisses.libelle_caisse',DB::raw('DATE_FORMAT(caisse_ouvertes.date_ouverture, "%d-%m-%Y à %H:%i") as date_ouvertures'),DB::raw('DATE_FORMAT(caisse_ouvertes.date_fermeture, "%d-%m-%Y à %H:%i") as date_fermetures'))
                                    ->get();
        
       $jsonData["rows"] = $caisses->toArray();
       $jsonData["total"] = $caisses->count();
       return response()->json($jsonData);
    }
    
    public function listeBilletageByCaisse($caisse){
        $caisses = CaisseOuverte::where([['caisse_ouvertes.deleted_at', NULL],['caisse_ouvertes.caisse_id',$caisse],['caisse_ouvertes.date_fermeture','!=',null]])
                                    ->join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                                    ->join('users','users.id','=','caisse_ouvertes.user_id')
                                    ->select('caisse_ouvertes.*','users.full_name','caisses.libelle_caisse',DB::raw('DATE_FORMAT(caisse_ouvertes.date_ouverture, "%d-%m-%Y à %H:%i") as date_ouvertures'),DB::raw('DATE_FORMAT(caisse_ouvertes.date_fermeture, "%d-%m-%Y à %H:%i") as date_fermetures'))
                                    ->get();
        
       $jsonData["rows"] = $caisses->toArray();
       $jsonData["total"] = $caisses->count();
       return response()->json($jsonData);
    }
    
    public function listeBilletageByCaissier($caissier){
        $caisses = CaisseOuverte::where([['caisse_ouvertes.deleted_at', NULL],['caisse_ouvertes.user_id',$caissier],['caisse_ouvertes.date_fermeture','!=',null]])
                                    ->join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                                    ->join('users','users.id','=','caisse_ouvertes.user_id')
                                    ->select('caisse_ouvertes.*','users.full_name','caisses.libelle_caisse',DB::raw('DATE_FORMAT(caisse_ouvertes.date_ouverture, "%d-%m-%Y à %H:%i") as date_ouvertures'),DB::raw('DATE_FORMAT(caisse_ouvertes.date_fermeture, "%d-%m-%Y à %H:%i") as date_fermetures'))
                                    ->get();
        
       $jsonData["rows"] = $caisses->toArray();
       $jsonData["total"] = $caisses->count();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Billetage  $billetage
     * @return Response
     */
    public function show(Billetage $billetage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billetage  $billetage
     * @return Response
     */
    public function edit(Billetage $billetage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Billetage  $billetage
     * @return Response
     */
    public function update(Request $request, Billetage $billetage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billetage  $billetage
     * @return Response
     */
    public function destroy(Billetage $billetage)
    {
        //
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
    public function billetagePdf($caisse_ouverte){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->billetageContent($caisse_ouverte));
        return $pdf->stream('billetage_'.date('d-m-y').'.pdf');
    }
    
    public function billetageContent($caisse_ouverte){
       $info_caisse_ouverte = DB::table('caisse_ouvertes')
                            ->join('caisses','caisses.id','=','caisse_ouvertes.caisse_id')
                            ->join('users','users.id','=','caisse_ouvertes.user_id')
                            ->select('caisse_ouvertes.*','caisses.libelle_caisse','users.full_name')
                            ->Where([['caisse_ouvertes.deleted_at', NULL],['caisse_ouvertes.id',$caisse_ouverte]])
                            ->first();
        $totalCaisse = 0; 
      
        $datas = Billetage::where('caisse_ouverte_id',$caisse_ouverte)->orderBy('billet','desc')->get();
        
        $outPut = $this->header();
        
        $outPut .= '<div class="container-table" font-size:12px;><h3 align="center"><u>Resumé de caisse après vente</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <td cellspacing="0" border="2" width="40%" align="letf">
                                Date : <b>'.date("d-m-Y").'</b><br/>
                                Caisse : <b>'.$info_caisse_ouverte->libelle_caisse.'</b><br/>
                                Caissier(e) <b>: <b>'.$info_caisse_ouverte->full_name.'</b>
                            </td>
                            <td cellspacing="0" border="2" width="35%" align="letf">
                                Ouverture : <b>'.date('d-m-Y H:i', strtotime($info_caisse_ouverte->date_ouverture)).'</b><br/>
                                Fermeture : <b>'.date('d-m-Y H:i', strtotime($info_caisse_ouverte->date_fermeture)).'</b><br/>
                                Solde <b>: <b>'.number_format($info_caisse_ouverte->solde_fermeture, 0, ',', ' ').'</b>
                            </td>
                            <td cellspacing="0" border="2" width="25%" align="left">
                                Ouverture : <b>'.number_format($info_caisse_ouverte->montant_ouverture, 0, ',', ' ').'</b><br/>
                                Entrée : <b>'.number_format($info_caisse_ouverte->entree, 0, ',', ' ').'</b><br/>
                                Sortie : <b>'.number_format($info_caisse_ouverte->sortie, 0, ',', ' ').'</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" align="center"> <b>Billetage</b> </td>
                        </tr>
                        <tr>
                            <th cellspacing="0" border="2" width="20%" align="center">Nombre</th>
                            <th cellspacing="0" border="2" width="50%" align="center">Billet</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Montant</th>
                        </tr>
                    </div>';
       $montantTotal = 0;
       foreach ($datas as $data){
           $montantTotal = $montantTotal + $data->billet*$data->quantite;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="center">'.$data->quantite.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->billet.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->billet*$data->quantite, 0, ',', ' ').'</td>
                        </tr>';
       }
       $info_caisse_ouverte->motif_non_conformite !=null?$motif_non_conformite='Motif : '.$info_caisse_ouverte->motif_non_conformite : $motif_non_conformite = null;
        $outPut .='</table>';
        $outPut.='<br/> Montant total : <b> '.number_format($montantTotal, 0, ',', ' ').' F CFA</b><br/>'.$motif_non_conformite;
       
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
