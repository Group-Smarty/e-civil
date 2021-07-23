@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="dateDebut" placeholder="Date du début">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="dateFin" placeholder="Date de fin">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <select id="searchByRegime"  class="form-control">
            <option value="tous">--- Tous les r&eacute;gimes ---</option>
            @foreach($regimes as $regime)
            <option value="{{$regime->id}}"> {{$regime->libelle_regime}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-2">
    <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
                data-url="{{url('etat',['action'=>'liste-mariages'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-formatter="numeroActeMariageFormatter">N° de l'acte  </th>
            <th data-field="date_mariages">Date de mariage </th>
            <th data-field="nom_complet_homme">Epoux </th>
            <th data-field="nom_complet_femme">Epouse </th>
            <th data-field="libelle_regime" data-sortable="true">R&eacute;gime</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var ajout = false;
    var $table = jQuery("#table"), rows = [];

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $('#dateDebut, #dateFin').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 

        $("#dateDebut, #dateFin").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            var regime = $("#searchByRegime").val();
            if(dateDebut == "" && dateFin == "" && regime=="tous"){
                $table.bootstrapTable('refreshOptions', {url: "{{url('etat', ['action' => 'liste-mariages'])}}"});
            }
            if(dateDebut != "" && dateFin != "" && regime=="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-mariages-by-periode/' + dateDebut + '/' + dateFin});
            }
            if(dateDebut == "" && dateFin == "" && regime!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-mariages-by-regime/' + regime});
            }
            if(dateDebut != "" && dateFin != "" && regime!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-mariages-by-regime-periode/' + dateDebut + '/' + dateFin + '/' + regime});
            }
        });
        
        $("#searchByRegime").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            var regime = $("#searchByRegime").val();
            
            if(dateDebut == "" && dateFin == "" && regime=="tous"){
                $table.bootstrapTable('refreshOptions', {url: "{{url('etat', ['action' => 'liste-mariages'])}}"});
            }
            if(dateDebut != "" && dateFin != "" && regime=="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-mariages-by-periode/' + dateDebut + '/' + dateFin});
            }
            if(dateDebut == "" && dateFin == "" && regime!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-mariages-by-regime/' + regime});
            }
            if(dateDebut != "" && dateFin != "" && regime!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-mariages-by-regime-periode/' + dateDebut + '/' + dateFin + '/' + regime});
            }
        });
    });
    
    function numeroActeMariageFormatter(id,row){
        return row.numero_acte_mariage + ' DU ' + row.date_dressers;
    }
    
    function imprimePdf(){
        var dateDebut = $("#dateDebut").val();
        var dateFin = $("#dateFin").val();
        var regime = $("#searchByRegime").val();
        if(dateDebut == "" && dateFin == "" && regime=="tous"){
            window.open("../etat/liste-mariages-pdf/" ,'_blank');
        }
        if(dateDebut != "" && dateFin != "" && regime=="tous"){
            window.open("../etat/liste-mariages-by-periode-pdf/" + dateDebut + '/' + dateFin,'_blank');  
        }
        if(dateDebut == "" && dateFin == "" && regime!="tous"){
            window.open("../etat/liste-mariages-by-regime-pdf/" + regime,'_blank');  
        }
        if(dateDebut != "" && dateFin != "" && regime!="tous"){
            window.open("../etat/liste-mariages-by-regime-periode-pdf/" + dateDebut + '/' + dateFin + '/' + regime,'_blank');  
        }
    }
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection