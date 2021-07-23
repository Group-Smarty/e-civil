@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<div class="row">
    <div class="col-md-3">
        <label>Voir pour une autre ann&eacute;e</label>
        <select id="annee" class="form-control">
            <option value="0">-- L'ann&eacute;e en cours ( <?=date("Y");?> ) --</option>
            @foreach($anneesObt as $annee)
            <option value="{{$annee}}">{{$annee}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-9"><br/>
        <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
    </div>
</div><br/>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-naissance-by-secteur'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="lieu_naissance_enfant">Lieu </th>
            <th data-field="nombre">Nombre</th>
        </tr>
    </thead>
</table>


<script type="text/javascript">
    var $table = jQuery("#table"), rows = [];
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
         $("#annee").change(function (e) {
            var annee = $("#annee").val();
            if(annee == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-naissance-by-secteur'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-naissance-by-secteur-annee/' + annee});
            }
        });
    });

    function imprimePdf(){
        var annee = $("#annee").val();
        if(annee==0){
            window.open("../e-civil/fiche-naissance-par-secteur/" ,'_blank');
        }else{
            window.open("../e-civil/fiche-naissance-par-secteur-annnee/" + annee ,'_blank');  
        }
    }
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


