<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Contribuable;
use App\Models\Taxes\Localite;
use App\Models\Taxes\DeclarationActivite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeclarationActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $typeSocietes = DB::table('type_societes')->Where('deleted_at', NULL)->orderBy('libelle_type_societe', 'asc')->get();
       $secteurs = DB::table('secteurs')->Where('deleted_at', NULL)->orderBy('libelle_secteur', 'asc')->get();
       $typeTaxes = DB::table('type_taxes')->orderBy('libelle_type_taxe', 'asc')->get();
       $localites = DB::table('localites')->orderBy('libelle_localite', 'asc')->get();
       $contribuables = DB::table('contribuables')->Where('deleted_at', NULL)->orderBy('nom_complet', 'asc')->get();
       
       $menuPrincipal = "Taxe";
       $titleControlleur = "Déclaration des activités";
       $btnModalAjout ="TRUE";
       return view('taxe.declaration-activite.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','contribuables', 'typeSocietes', 'localites','typeTaxes', 'secteurs')); 
    }

    public function listeDeclarationActivite()
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where('deleted_at', NULL)
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByContribuable($contribuable)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['declaration_activites.contribuable_id',$contribuable]])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByLocalite($localite)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['declaration_activites.localite_id',$localite]])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByDate($date)
    {
        $dates = Carbon::createFromFormat('d-m-Y', $date);
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where('deleted_at', NULL)
                        ->WhereDate('declaration_activites.date_declaration',$dates)
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByNumero($numero)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['numero_cc','like','%'.$numero.'%']])
                        ->orWhere([['deleted_at', NULL],['numero_registre','like','%'.$numero.'%']])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteByLocaliteContribuable($localite, $contribuable)
    {
        $activites = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->Where([['deleted_at', NULL],['declaration_activites.localite_id',$localite],['declaration_activites.contribuable_id',$contribuable]])
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();
       $jsonData["rows"] = $activites->toArray();
       $jsonData["total"] = $activites->count();
       return response()->json($jsonData);
    }
    
    public function listeDeclarationActiviteById($id){
        $activite = DeclarationActivite::with('contribuable', 'type_societe','secteur','localite','type_taxe')
                        ->select('declaration_activites.*')
                        ->Where([['deleted_at', NULL],['declaration_activites.id',$id]])
                        ->get();
        $jsonData["rows"] = $activite->toArray();
        $jsonData["total"] = $activite->count();
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
        if ($request->isMethod('post') && $request->input('nom_activite')) {

                $data = $request->all(); 

            try {
                
                $DeclarationActivite = DeclarationActivite::where('numero_cc', $data['numero_cc'])
                                                ->orWhere('numero_registre', $data['numero_registre'])
                                                ->first();
                if($DeclarationActivite!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement est déjà enregistré, vérifier le numéro contribuable ou du registre", "data" => NULL]);
                }
                
                $contribuable = Contribuable::find($data['contribuable_id']);
                
                if(!$contribuable){
                    return response()->json(["code" => 0, "msg" => "Ce contribuable est introuvable", "data" => NULL]);
                }
                
                $declarationActivite = new DeclarationActivite;
                $declarationActivite->nom_activite = $data['nom_activite'];
                $declarationActivite->nom_structure = isset($data['nom_structure']) && !empty($data['nom_structure']) ? $data['nom_structure'] : $contribuable->nom_complet;
                $declarationActivite->numero_cc = $data['numero_cc'];
                $declarationActivite->numero_registre = $data['numero_registre'];
                $declarationActivite->contact = isset($data['contact']) && !empty($data['contact']) ? $data['contact'] : $contribuable->contact;
                $declarationActivite->situation_geographique = $data['situation_geographique'];
                $declarationActivite->contribuable_id = $data['contribuable_id'];
                $declarationActivite->type_societe_id = $data['type_societe_id'];
                $declarationActivite->secteur_id = $data['secteur_id'];
                $declarationActivite->type_taxe_id =  $data['type_taxe_id'];
                $declarationActivite->localite_id =  $data['localite_id'];
                $declarationActivite->montant_taxe =  $data['montant_taxe'];
                $declarationActivite->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $declarationActivite->longitude = isset($data['longitude']) && !empty($data['longitude']) ? $data['longitude']: Null;
                $declarationActivite->latitude = isset($data['latitude']) && !empty($data['latitude']) ? $data['latitude']: Null;
                $declarationActivite->adresse_postale = isset($data['adresse_postale']) && !empty($data['adresse_postale']) ? $data['adresse_postale']: Null;
                $declarationActivite->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $declarationActivite->created_by = Auth::user()->id;
                $declarationActivite->save();
                $jsonData["data"] = json_decode($declarationActivite);
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
     * @param  \App\DeclarationActivite  $declarationActivite
     * @return Response
     */
    public function update(Request $request, DeclarationActivite $declarationActivite)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($declarationActivite){
            try {
                
                $data = $request->all();
                
                $contribuable = Contribuable::find($data['contribuable_id']);
                
                if(!$contribuable){
                    return response()->json(["code" => 0, "msg" => "Ce contribuable est introuvable", "data" => NULL]);
                }
       
                $declarationActivite->nom_activite = $data['nom_activite'];
                $declarationActivite->nom_structure = isset($data['nom_structure']) && !empty($data['nom_structure']) ? $data['nom_structure'] : $contribuable->nom_complet;
                $declarationActivite->numero_cc = $data['numero_cc'];
                $declarationActivite->numero_registre = $data['numero_registre'];
                $declarationActivite->contact = isset($data['contact']) && !empty($data['contact']) ? $data['contact'] : $contribuable->contact;
                $declarationActivite->situation_geographique = $data['situation_geographique'];
                $declarationActivite->contribuable_id = $data['contribuable_id'];
                $declarationActivite->type_societe_id = $data['type_societe_id'];
                $declarationActivite->secteur_id = $data['secteur_id'];
                $declarationActivite->type_taxe_id =  $data['type_taxe_id'];
                $declarationActivite->localite_id =  $data['localite_id'];
                $declarationActivite->montant_taxe =  $data['montant_taxe'];
                $declarationActivite->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $declarationActivite->longitude = isset($data['longitude']) && !empty($data['longitude']) ? $data['longitude']: Null;
                $declarationActivite->latitude = isset($data['latitude']) && !empty($data['latitude']) ? $data['latitude']: Null;
                $declarationActivite->adresse_postale = isset($data['adresse_postale']) && !empty($data['adresse_postale']) ? $data['adresse_postale']: Null;
                $declarationActivite->email = isset($data['email']) && !empty($data['email']) ? $data['email']: Null;
                $declarationActivite->updated_by = Auth::user()->id;
                $declarationActivite->save();
       
            $jsonData["data"] = json_decode($declarationActivite);
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
     * @param  \App\DeclarationActivite  $declarationActivite
     * @return Response
     */
    public function destroy(DeclarationActivite $declarationActivite)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($declarationActivite){
                try {
               
                $declarationActivite->update(['deleted_by' => Auth::user()->id]);
                $declarationActivite->delete();
                
                $jsonData["data"] = json_decode($declarationActivite);
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

    //Liste des activités
    public function listeActivitePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeActivites());
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_activites.pdf');
    }
    public function listeActivites(){
        $datas = DeclarationActivite::where('declaration_activites.deleted_at', NULL)
                        ->join('type_societes','type_societes.id','=','declaration_activites.type_societe_id')
                        ->join('secteurs','secteurs.id','=','declaration_activites.secteur_id')
                        ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                        ->join('localites','localites.id','=','declaration_activites.localite_id')
                        ->select('localites.libelle_localite','contribuables.nom_complet','secteurs.libelle_secteur','type_societes.libelle_type_societe','declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();

        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des activité déclarées </u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='20%' align='center'>Date décl. </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Structure</th>
                                <th cellspacing='0' border='2' width='20%' align='center'>Type société</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Activité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Secteur d'activité</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Contribuable</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Localité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Adresse</th>
                                <th cellspacing='0' border='2' width='25%' align='center'>N° registre</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                    <tr>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_declarations.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_structure.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_type_societe.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_activite.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_secteur.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_localite.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->situation_geographique.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_registre.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' contribuabl(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }


    //Liste des activités par date
    public function listeActiviteByDatePdf($date){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeActiviteByDates($date));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_activites_declarees_le_'.$date.'.pdf');
    }
    public function listeActiviteByDates($date){
        $dates = Carbon::createFromFormat('d-m-Y', $date);
        $datas = DeclarationActivite::where('declaration_activites.deleted_at', NULL)
                        ->join('type_societes','type_societes.id','=','declaration_activites.type_societe_id')
                        ->join('secteurs','secteurs.id','=','declaration_activites.secteur_id')
                        ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                        ->join('localites','localites.id','=','declaration_activites.localite_id')
                        ->select('localites.libelle_localite','contribuables.nom_complet','secteurs.libelle_secteur','type_societes.libelle_type_societe','declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->whereDate('declaration_activites.date_declaration', $dates)
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();

        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des activité déclarées le ".$date." </u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='40%' align='center'>Structure</th>
                                <th cellspacing='0' border='2' width='20%' align='center'>Type société</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Activité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Secteur d'activité</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Contribuable</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Localité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Adresse</th>
                                <th cellspacing='0' border='2' width='25%' align='center'>N° registre</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                    <tr>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_structure.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_type_societe.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_activite.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_secteur.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_localite.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->situation_geographique.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_registre.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' contribuabl(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }

    //Liste des activités par contribuable
    public function listeActiviteByContribuablePdf($contribuable){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeActiviteByContribuables($contribuable));
        $pdf->setPaper('A4', 'landscape');
        $infosContribuable = Contribuable::find($contribuable);
        return $pdf->stream('liste_activites_declarees_de_'.$infosContribuable->nom_complet.'.pdf');
    }
    public function listeActiviteByContribuables($contribuable){
         $infosContribuable = Contribuable::find($contribuable);
         $datas = DeclarationActivite::where([['declaration_activites.deleted_at', NULL],['declaration_activites.contribuable_id',$contribuable]])
                        ->join('type_societes','type_societes.id','=','declaration_activites.type_societe_id')
                        ->join('secteurs','secteurs.id','=','declaration_activites.secteur_id')
                        ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                        ->join('localites','localites.id','=','declaration_activites.localite_id')
                        ->select('localites.libelle_localite','contribuables.nom_complet','secteurs.libelle_secteur','type_societes.libelle_type_societe','declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();

        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des activité déclarées au nom du contribuable ".$infosContribuable->nom_complet."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='20%' align='center'>Date décl. </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Structure</th>
                                <th cellspacing='0' border='2' width='20%' align='center'>Type société</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Activité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Secteur d'activité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Localité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Adresse</th>
                                <th cellspacing='0' border='2' width='25%' align='center'>N° registre</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                    <tr>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_declarations.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_structure.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_type_societe.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_activite.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_secteur.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_localite.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->situation_geographique.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_registre.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' contribuabl(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }

     //Liste des activités par localités
    public function listeActiviteByLocalitePdf($localite){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeActiviteByLocalites($localite));
        $pdf->setPaper('A4', 'landscape');
        $infosLocalite = Localite::find($localite);
        return $pdf->stream('liste_activites_de_la_localite_'.$infosLocalite->libelle_localite.'.pdf');
    }
    public function listeActiviteByLocalites($localite){
        $infosLocalite = Localite::find($localite);
         $datas = DeclarationActivite::where([['declaration_activites.deleted_at', NULL],['declaration_activites.localite_id',$localite]])
                        ->join('type_societes','type_societes.id','=','declaration_activites.type_societe_id')
                        ->join('secteurs','secteurs.id','=','declaration_activites.secteur_id')
                        ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                        ->select('contribuables.nom_complet','secteurs.libelle_secteur','type_societes.libelle_type_societe','declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();

        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des activité déclarées dans la localité ".$infosLocalite->libelle_localite."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='20%' align='center'>Date décl. </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Structure</th>
                                <th cellspacing='0' border='2' width='20%' align='center'>Type société</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Activité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Secteur d'activité</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Contribuable</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Adresse</th>
                                <th cellspacing='0' border='2' width='25%' align='center'>N° registre</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                    <tr>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_declarations.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_structure.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_type_societe.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_activite.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_secteur.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->situation_geographique.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_registre.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' contribuabl(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }

      //Liste des activités par localités et contribuables
    public function listeActiviteByContribuableLocalitePdf($contribuable,$localite){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeActiviteByContribuableLocalites($contribuable,$localite));
        $pdf->setPaper('A4', 'landscape');
        $infosLocalite = Localite::find($localite);
        $infosContribuable = Contribuable::find($contribuable);
        return $pdf->stream('liste_activites_de_la_localite_'.$infosLocalite->libelle_localite.'_du_contribuable_'.$infosContribuable->nom_complet.'.pdf');
    }
    public function listeActiviteByContribuableLocalites($contribuable,$localite){
          $infosLocalite = Localite::find($localite);
          $infosContribuable = Contribuable::find($contribuable);
         $datas = DeclarationActivite::where([['declaration_activites.deleted_at', NULL],['declaration_activites.localite_id',$localite],['declaration_activites.contribuable_id',$contribuable]])
                        ->join('type_societes','type_societes.id','=','declaration_activites.type_societe_id')
                        ->join('secteurs','secteurs.id','=','declaration_activites.secteur_id')
                        ->join('contribuables','contribuables.id','=','declaration_activites.contribuable_id')
                        ->select('contribuables.nom_complet','secteurs.libelle_secteur','type_societes.libelle_type_societe','declaration_activites.*',DB::raw('DATE_FORMAT(declaration_activites.date_declaration, "%d-%m-%Y") as date_declarations'))
                        ->orderBy('declaration_activites.date_declaration', 'DESC')
                        ->get();

        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des activité déclarées dans la localité ".$infosLocalite->libelle_localite." au nom du contribuable ".$infosContribuable->nom_complet."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='20%' align='center'>Date décl. </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Structure</th>
                                <th cellspacing='0' border='2' width='20%' align='center'>Type société</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Activité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Secteur d'activité</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Adresse</th>
                                <th cellspacing='0' border='2' width='25%' align='center'>N° registre</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                    <tr>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_declarations.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_structure.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_type_societe.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_activite.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_secteur.'</td>
                        <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->situation_geographique.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_registre.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' contribuabl(s)</b>';
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
