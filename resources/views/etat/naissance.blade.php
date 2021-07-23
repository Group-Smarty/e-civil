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
               data-url="{{url('etat',['action'=>'liste-naissances'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-formatter="numeroActeNaissanceFormatter">N° de l'acte  </th>
            <th data-formatter="fullNameFormatter" data-sortable="true">Nom complet  </th>
            <th data-formatter="dateNaissanceFormatter">Date de naissance</th>
            <th data-field="lieu_naissance_enfant">Lieu de naissance</th>
            <th data-field="sexe">Sexe </th>
            <th data-field="nom_complet_pere">P&egrave;re </th>
            <th data-field="nom_complet_mere">M&egrave;re </th>
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
                $table.bootstrapTable('refreshOptions', {url: "{{url('etat', ['action' => 'liste-naissances'])}}"});
            }
            if(dateDebut != "" && dateFin != "" && sexe=="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-naissances-by-periode/' + dateDebut + '/' + dateFin});
            }
            if(dateDebut == "" && dateFin == "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-naissances-by-sexe/' + sexe});
            }
            if(dateDebut != "" && dateFin != "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-naissances-by-sexe-periode/' + dateDebut + '/' + dateFin + '/' + sexe});
            }
        });
        $("#searchBySexe").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            var sexe = $("#searchBySexe").val();
            if(dateDebut == "" && dateFin == "" && sexe=="tous"){
                $table.bootstrapTable('refreshOptions', {url: "{{url('etat', ['action' => 'liste-naissances'])}}"});
            }
            if(dateDebut != "" && dateFin != "" && sexe=="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-naissances-by-periode/' + dateDebut + '/' + dateFin});
            }
            if(dateDebut == "" && dateFin == "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-naissances-by-sexe/' + sexe});
            }
            if(dateDebut != "" && dateFin != "" && sexe!="tous"){
               $table.bootstrapTable('refreshOptions', {url: '../etat/liste-naissances-by-sexe-periode/' + dateDebut + '/' + dateFin + '/' + sexe});
            }
        });
    });
    function numeroActeNaissanceFormatter(id,row){
        return row.numero_acte_naissance + ' du ' + row.date_dressers;
    }
    function fullNameFormatter(id, row){
        return row.nom_enfant + ' ' + row.prenom_enfant;
    }
    function dateNaissanceFormatter(id, row){
        if(row.heure_naissance_enfant!=null){
            return row.date_naissance + ' à ' + row.heure_naissance_enfant;
        }else{
            return row.date_naissance;
        }
    }
    function imprimePdf(){
        var dateDebut = $("#dateDebut").val();
        var dateFin = $("#dateFin").val();
        var sexe = $("#searchBySexe").val();
        if(dateDebut == "" && dateFin == "" && sexe=="tous"){
            window.open("../etat/liste-naissances-pdf/" ,'_blank');
        }
        if(dateDebut != "" && dateFin != "" && sexe=="tous"){
            window.open("../etat/liste-naissances-by-periode-pdf/" + dateDebut + '/' + dateFin,'_blank');  
        }
        if(dateDebut == "" && dateFin == "" && sexe!="tous"){
            window.open("../etat/liste-naissances-by-sexe-pdf/" + sexe,'_blank');  
        }
        if(dateDebut != "" && dateFin != "" && sexe!="tous"){
            window.open("../etat/liste-naissances-by-sexe-periode-pdf/" + dateDebut + '/' + dateFin + '/' + sexe,'_blank');  
        }
    }
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection