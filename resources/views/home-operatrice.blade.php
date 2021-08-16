@extends('layouts.app')
@section('content')

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

@if(Auth::user()->role == 'Operatrice')
<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
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
    <div class="col-md-4 col-sm-6 col-xs-12">
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
    <div class="col-md-4 col-sm-6 col-xs-12">
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
</div>
@endif
@if(Auth::user()->role == 'Courrier')
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
<!--    <div class="clearfix visible-sm-block"></div>-->
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
            <!-- /.info-box-content -->
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
@endif
@endsection
