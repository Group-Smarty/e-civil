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
<div class="col-md-3">
    <div class="form-group">
        <select id="searchBySexe"  class="form-control">
            <option value="tous">--- Tous les sexes ---</option>
            <option value="Masculin">Masculin</option>
            <option value="Feminin">Feminin</option>
        </select>
    </div>
</div>
<div class="col-md-3">
    <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('etat',['action'=>'liste-deces'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-formatter="numeroActeDecesFormatter">N° de l'acte  </th>
            <th data-formatter="dateDecesFormatter">Date du d&eacute;c&egrave;s </th>
            <th data-field="nom_complet_decede">Nom complet du d&eacute;funt</th>
            <th data-field="sexe">Sexe</th>
            <th data-field="lieu_deces">Lieu du d&eacute;c&egrave;s </th>
            <th data-field="motif_deces">Motif</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var $table = jQuery("#table"), rows = [];

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
       $('#dateDebut, #dateFin').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date()
        }); 
        
     $("#dateDebut, #dateFin").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            var sexe = $("#searchBySexe").val();
            if(dateDebut == "" && dateFin == "" && sexe=="tous"){
                $table.bootstrapTable('refreshOptions', {url: "{{url('etat', ['action' => 'liste-deces'])}}"});
            }
            if(dateDebut != "" && dateFin != "" && sexe=="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-deces-by-periode/' + dateDebut + '/' + dateFin});
            }
            if(dateDebut == "" && dateFin == "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-deces-by-sexe/' + sexe});
            }
            if(dateDebut != "" && dateFin != "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-deces-by-sexe-periode/' + dateDebut + '/' + dateFin + '/' + sexe});
            }
        });
        $("#searchBySexe").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            var sexe = $("#searchBySexe").val();
            if(dateDebut == "" && dateFin == "" && sexe=="tous"){
                $table.bootstrapTable('refreshOptions', {url: "{{url('etat', ['action' => 'liste-deces'])}}"});
            }
            if(dateDebut != "" && dateFin != "" && sexe=="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-deces-by-periode/' + dateDebut + '/' + dateFin});
            }
            if(dateDebut == "" && dateFin == "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-deces-by-sexe/' + sexe});
            }
            if(dateDebut != "" && dateFin != "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-deces-by-sexe-periode/' + dateDebut + '/' + dateFin + '/' + sexe});
            }
        });
    });
    
    function numeroActeDecesFormatter(id, row){
        return row.numero_acte_deces + ' DU ' + row.date_dressers;
    }
    function dateDecesFormatter(id, row){
        if(row.heure_deces!=null){
           return row.date_decess + ' à ' + row.heure_deces; 
        }else{
            return row.date_decess
        }
    }
    
    function imprimePdf(){
        var dateDebut = $("#dateDebut").val();
        var dateFin = $("#dateFin").val();
        var sexe = $("#searchBySexe").val();
        if(dateDebut == "" && dateFin == "" && sexe=="tous"){
            window.open("../etat/liste-deces-pdf/" ,'_blank');
        }
        if(dateDebut != "" && dateFin != "" && sexe=="tous"){
            window.open("../etat/liste-deces-by-periode-pdf/" + dateDebut + '/' + dateFin,'_blank');  
        }
        if(dateDebut == "" && dateFin == "" && sexe!="tous"){
            window.open("../etat/liste-deces-by-sexe-pdf/" + sexe,'_blank');  
        }
        if(dateDebut != "" && dateFin != "" && sexe!="tous"){
            window.open("../etat/liste-deces-by-sexe-periode-pdf/" + dateDebut + '/' + dateFin + '/' + sexe,'_blank');  
        }
    }
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection