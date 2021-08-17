@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
<script src="{{asset('assets/plugins/jQuery/jquery-3.1.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
<link href="{{asset('assets/plugins/iCheck/square/green.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<script src="{{asset('assets/plugins/chartjs/Chart.min.js')}}"></script>
<script src="{{asset('assets/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<link href="{{asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet">
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>


<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <a style="text-decoration: none; color: #000000;" href="{{url('e-civil/naissances')}}">
                <span class="info-box-icon bg-aqua">
                    <i class="fa fa-child"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Naissances <b><?= date('Y'); ?></b></span>
                    <span class="info-box-number">{{$nassancesAnnee->count()}}</span>
                </div>
            </a>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('e-civil/mariages')}}">
        <div class="info-box">
            <span class="info-box-icon bg-red">
                <i class="fa fa-venus-double"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Mariages <b><?= date('Y');?></b></span>
                <span class="info-box-number">{{$mariagesAnnee->count()}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
       </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('e-civil/decedes')}}">
        <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-times"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Deces <b><?= date('Y');?></b></span>
                <span class="info-box-number">{{$decesAnnee->count()}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
         </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
         <a style="text-decoration: none; color: #000000;" href="{{url('recrutement/contrats')}}">
        <div class="info-box">
            <span class="info-box-icon bg-yellow">
                <i class="fa fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Agents sous contrat</span>
                <span class="info-box-number">{{$contrat->count()}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">  
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Total des naissances par sexe</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="170" width="205" style="width: 205px; height: 170px;"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4"><br/><br/>
                        <ul class="chart-legend clearfix">
                            <li><i class="fa fa-circle-o text-primary"></i><b>&nbsp;&nbsp;Masculin</b></li>
                            <li><i class="fa fa-circle-o text-orange"></i><b>&nbsp;&nbsp;F&eacute;minin</b></li>
                        </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a><b>Masculin</b>
                            <span class="pull-right text-primary">{{$nassances->count()>0 ? round(($naissanceHomme->count()/$nassances->count())*100) : 0}}%</span>
                        </a>
                    </li>
                    <li>
                        <a><b>F&eacute;minin</b>
                            <span class="pull-right text-orange">{{$nassances->count()>0 ? round(($naissanceFemme->count()/$nassances->count())*100) : 0}}%</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.footer -->
        </div>
    </div>  
    <div class="col-md-6">  
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Total des d&eacute;c&egrave;s par sexe</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChart2" height="170" width="205" style="width: 205px; height: 170px;"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4"><br/><br/>
                        <ul class="chart-legend clearfix">
                            <li><i class="fa fa-circle-o text-red"></i><b>&nbsp;&nbsp;Masculin</b></li>
                            <li><i class="fa fa-circle-o text-green"></i><b>&nbsp;&nbsp;F&eacute;minin</b></li>
                        </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a><b>Masculin</b>
                            <span class="pull-right text-red">{{$deces->count() > 0 ? round(($decesHomme->count()/$deces->count())*100) : 0}}%</span>
                        </a>
                    </li>
                    <li>
                        <a><b>F&eacute;minin</b>
                            <span class="pull-right text-green">{{$deces->count() > 0 ? round(($decesFemme->count()/$deces->count())*100) : 0}}%</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.footer -->
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Total des naissances d&eacute;clar&eacute;es par tranche d'&acirc;ge</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height: 300px; width: 100%;"></canvas>
              </div>
            </div>
          </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Liste des prochains mariages</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Date du mariage</th>
                                    <th>Epoux</th>
                                    <th>Epouse</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($prochainsMariages as $mariage)
                                <tr>
                                    <td>{{date("d-m-Y à H:i", strtotime($mariage->date_mariage))}}</td>
                                    <td>{{$mariage->nom_complet_homme}}</td>
                                    <td>{{$mariage->nom_complet_femme}}</td>
                                    <td>{{$mariage->contact_declarant}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                        <a href="{{route('e-civil.prochains-mariages')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
</div>

<div class="box collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title">D'autres informations de naissance</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-plus"></i></button>
          </div>
        </div>
        <div class="box-body" style="display: none;">
            <div class="row">
            <div class="col-md-4">  
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Naissances par ann&eacute;e </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="progress-group">
                                    @foreach($nataliteByAn as $natAn)
                                    <span class="progress-text">{{$natAn->year}}</span>
                                    <span class="progress-number"><b>{{$natAn->total}}</b></span>
                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-green" style="width:{{$natAn->total}}%"></div>
                                    </div>
                                    <!--</a>-->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{route('e-civil.fiche-naissance-par-annnee')}}" target="_blank" class="btn btn-xs btn-success pull-right">Voir plus</a>
                    </div>
                </div>
            </div> 
            <div class="col-md-4">  
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Naissances par mois en <b><?= date("Y");?></b></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="progress-group">
                                    @foreach($nataliteByMois as $natMois)
                                    <span class="progress-text">{{$moisFr[$natMois->month]}}</span>
                                    <span class="progress-number"><b>{{$natMois->total}}</b></span>
                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-green" style="width:{{$natMois->total}}%"></div>
                                    </div>
                                    <!--</a>-->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                            <a href="{{route('e-civil.naissance-by-mois')}}" class="btn btn-xs btn-success pull-right">Voir plus </a>
                    </div>
                </div>
            </div> 
            <div class="col-md-4">  
                <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Naissances par lieu en <b><?= date("Y");?></b></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="progress-group">
                                @foreach($nataliteByQrt as $natQrt)
                                <span class="progress-text">{{$natQrt->lieu_naissance_enfant}}</span>
                                <span class="progress-number"><b>{{$natQrt->total}}</b></span>
                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-green" style="width:{{$natQrt->total}}%"></div>
                                </div>
                                <!--</a>-->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                        <a href="{{route('e-civil.naissance-by-secteur')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
                </div>
            </div>
            </div> 
            </div>
            <div class="row">
             <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Liste des nouveaux majeurs <span class="text-bold"> <?= date('Y'); ?></span></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Pr&eacute;nom(s)</th>
                                            <th>Nom</th>
                                            <th>Date de naissance</th>
                                            <th>Lieu de naissance</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @foreach($nouveauxMajeurs as $majeur)
                                        <tr>
                                            <td>{{$majeur->prenom_enfant}}</td>
                                            <td>{{$majeur->nom_enfant}}</td>
                                            <td>{{date('d-m-Y', strtotime($majeur->date_naissance_enfant))}}</td>
                                            <td>{{$majeur->lieu_naissance_enfant}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <a href="{{route('e-civil.nouveaux-majeurs')}}" class="btn btn-xs btn-success pull-right">Voir plus </a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Liste des personnes n'ayant pas fait de demande de copie d'extrait de naissance depuis au moins 1 an</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <thead>
                                            <tr>
                                                <th>Pr&eacute;nom(s)</th>
                                                <th>Nom</th>
                                                <th>Date de naissance</th>
                                                <th>Lieu de naissance</th>
                                                <th>N° Extrait</th>
                                                <th>P&egrave;re</th>
                                                <th>M&egrave;re</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            @foreach($personneSansDemande as $demande)
                                            <tr>
                                                <td>{{$demande->prenom_enfant}}</td>
                                                <td>{{$demande->nom_enfant}}</td>
                                                <td>{{date('d-m-Y', strtotime($demande->date_naissance_enfant))}}</td>
                                                <td>{{$demande->lieu_naissance_enfant}}</td>
                                                <td>{{$demande->numero_acte_naissance.' DU '.date('d-m-Y', strtotime($demande->date_dresser))}}</td>
                                                <td>{{$demande->nom_complet_pere}}</td>
                                                <td>{{$demande->nom_complet_mere}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <a href="{{route('e-civil.fiche-sans-demandes')}}" target="_blank" class="btn btn-xs btn-success pull-right">Voir plus </a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
            </div>
        </div>
      </div>
      <div class="box collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title">D'autres informations de d&eacute;c&egrave;s</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-plus"></i></button>
          </div>
        </div>
        <div class="box-body" style="display: none;"> 
  <div class="row">
    <div class="col-md-6">  
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Liste des 5 lieux o&ugrave; le taux de mortalité est &eacute;lev&eacute; </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress-group">
                            @foreach($listeDecesByLieu as $lieu)
                            <span class="progress-text">{{$lieu->lieu_deces == "" ? "Inconnu" : $lieu->lieu_deces}}</span>
                            <span class="progress-number"><b>{{$lieu->total}}</b></span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-danger" style="width:{{$lieu->total}}%"></div>
                            </div>
                            <!--</a>-->
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="{{route('e-civil.deces-par-lieu')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
            </div>
        </div>
    </div>  
    <div class="col-md-6">  
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Liste des 5 grands motifs des d&eacute;c&egrave;s </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress-group">
                            @foreach($listeDecesByMotif as $motif)
                            <span class="progress-text">{{$motif->motif_deces == "" ? "Inconnu" : $motif->motif_deces}}</span>
                            <span class="progress-number"><b>{{$motif->total}}</b></span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-yellow" style="width:{{$motif->total}}%"></div>
                            </div>
                            <!--</a> fiche-deces-motif-->
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="{{route('e-civil.deces-par-motif')}}" target="_blak" class="btn btn-xs btn-success pull-right">Voir plus</a>
            </div>
        </div>
    </div> 
</div>
<div class="row">
    <div class="col-md-6">  
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">D&eacute;c&egrave;s par an</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress-group">
                            @foreach($listeDecesByAn as $deathByAn)
                            <span class="progress-text">{{$deathByAn->year}}</span>
                            <span class="progress-number"><b>{{$deathByAn->total}}</b></span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-danger" style="width:{{$deathByAn->total}}%"></div>
                            </div>
                            <!--</a>-->
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                    <a href="{{route('e-civil.fiche-deces-par-an')}}" target="_blak" class="btn btn-xs btn-success pull-right">Voir plus</a>
            </div>
        </div>
    </div>  
    <div class="col-md-6">  
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">D&eacute;c&egrave;s par mois en <b><?= date("Y");?></b></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress-group">
                            @foreach($listeDecesByMois  as $deathByMois)
                            <span class="progress-text">{{$moisFr[$deathByMois->month]}}</span>
                            <span class="progress-number"><b>{{$deathByMois->total}}</b></span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-yellow" style="width:{{$deathByMois->total}}%"></div>
                            </div>
                            <!--</a>-->
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                    <a href="{{route('e-civil.deces-par-mois')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
            </div>
        </div>
    </div> 
</div>

        </div>
        </div>        
<div class="box collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title">Courriers</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-plus"></i></button>
          </div>
        </div>
        <div class="box-body" style="display: none;">
        <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <a style="text-decoration: none; color: #000000;" href="{{url('courrier/courriers-recus')}}">
                <span class="info-box-icon bg-yellow">
                    <i class="fa fa-mail-forward"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Courriers entrant du jour</span>
                    <span class="info-box-number">{{$courrierEntr->count()}}</span>
                </div>
            </a>
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('courrier/courriers-emis')}}">
        <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-venus-double"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Courriers sotant du jour</b></span>
                <span class="info-box-number">{{$courrierSort->count()}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
       </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('courrier/annuaires')}}">
        <div class="info-box">
            <span class="info-box-icon bg-red">
                <i class="fa fa-times"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Annuaires</b></span>
                <span class="info-box-number">{{$annuaires->count()}}</span>
            </div>
        </div>
         </a>
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
         <a style="text-decoration: none; color: #000000;" href="{{url('courrier/courriers')}}">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fa fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Tous les courriers du jour</span>
                <span class="info-box-number">{{$courrierSort->count()+$courrierEntr->count()}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        </a>
    </div>
    <!-- /.col -->
</div>
 <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Liste des courriers du jour</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Objet</th>
                                    <th>Emetteur</th>
                                    <th>Contact</th>
                                    <th>Service</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($courriers as $courrier)
                                <tr>
                                    <td>{{$courrier->objet}}</td>
                                    <td>{{$courrier->annuaire_id != null ? ($courrier->annuaire->raison_sociale) : $courrier->full_nam_particulier}}</td>
                                    <td>{{$courrier->annuaire_id != null ? ($courrier->annuaire->contact1) : $courrier->contact_particulier}}</td>
                                    <td>{{$courrier->service_id != null ? ($courrier->service->libelle_service) : "Courrier sortant"}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <!--                <div class="box-footer clearfix">
                                        <a href="" class="btn btn-xs btn-success pull-right">Voir plus</a>
                                </div>-->
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
        </div>
      </div>
@endif
@if(Auth::user()->role == 'Caissier')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-10">
        <img class="img-responsive text-center" src="{{asset($get_configuration_infos->logo)}}" alt="Photo" style="width:70%;">
    </div>
</div>
@endif
<form>
    <input type="text" class="hidden" id="naissanceFemme" value="{{$naissanceFemme->count()}}">
    <input type="text" class="hidden" id="naissanceHomme" value="{{$naissanceHomme->count()}}">
    <input type="text" class="hidden" id="decesFemme" value="{{$decesFemme->count()}}">
    <input type="text" class="hidden" id="decesHomme" value="{{$decesHomme->count()}}">
  
</form>
<script src="{{asset('adminLte/dist/js/pages/dashboard2.js')}}"></script>
<script>
 var $agesHommes = {!! json_encode($ageHommes) !!};
 var $agesFemmes = {!! json_encode($ageFemmes) !!};
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */
 
    //-------------
    //- BAR CHART -
    //-------------
     var areaChartData = {
      labels: ["0-10 ans", "11-20 ans", "21-30 ans", "31-40 ans", "41-50 ans", "51-60 ans", "61-70 ans"," plus de 70 ans"],
      datasets: [
        {
          label: "Féminin",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data: [$agesFemmes[0],$agesFemmes[1],$agesFemmes[2],$agesFemmes[3],$agesFemmes[4],$agesFemmes[5],$agesFemmes[6],$agesFemmes[7]]
        },
        {
          label: "Masculin",
          fillColor: "rgba(60,141,188,0.9)",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: [$agesHommes[0],$agesHommes[1],$agesHommes[2],$agesHommes[3],$agesHommes[4],$agesHommes[5],$agesHommes[6],$agesHommes[7]]
        }
      ]
    };
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas);
    var barChartData = areaChartData;
    barChartData.datasets[1].fillColor = "#00a65a";
    barChartData.datasets[1].strokeColor = "#00a65a";
    barChartData.datasets[1].pointColor = "#00a65a";
    var barChartOptions = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: true,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - If there is a stroke on each bar
      barShowStroke: true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth: 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing: 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing: 1,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to make the chart responsive
      responsive: true,
      maintainAspectRatio: true
    };

    barChartOptions.datasetFill = false;
    barChart.Bar(barChartData, barChartOptions);
  });
</script>
@endsection
