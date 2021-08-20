@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Taxe')
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByFacture" placeholder="Recherche par N° facture">
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="dateDebut" placeholder="Date début">
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="dateFin" placeholder="Date fin">
    </div>
</div>
<div class="col-md-3">
    <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('taxe',['action'=>'liste-taxes-payes'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="id" data-formatter="tiketFormatter" data-width="60px" data-align="center">Ticket</th>
            <th data-field="numero_ticket">N° Facture </th>
            <th data-field="date_payements">Date </th>
            <th data-field="payement_effectuer_par">Payer par </th>
            <th data-field="montant">Montant</th>
            <th data-field="declaration_activite.nom_structure">Structure </th>
            <th data-field="date_prochain_payements">Prochain payement</th>
            <th data-field="nom_complet_contribuable">Contribuable </th>
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
            maxDate : new Date(),
        }); 

        $("#searchByFacture").keyup(function (e) {
            var numero = $("#searchByFacture").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-taxes-payes'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-facture/' + numero});
            }
        });

        $("#dateDebut, #dateFin").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            
            if(dateDebut == '' && dateFin == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-taxes-payes'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-periode/' + dateDebut + '/' + dateFin});
            }
        });

    });
    function ticketPrintRow(idPayement){
        window.open("../taxe/facture-pdf/" + idPayement ,'_blank')
    }
    function tiketFormatter(id, row){
        return '<button type="button" class="btn btn-xs btn-info" data-placement="left" data-toggle="tooltip" title="Ticket" onClick="javascript:ticketPrintRow(' + row.id + ');"><i class="fa fa-file-pdf-o"></i></button>';
    }

    function imprimePdf(){
        var dateDebut = $("#dateDebut").val();
        var dateFin = $("#dateFin").val();

        if(dateDebut == "" && dateFin == ""){
            window.open("../taxe/liste-taxes-payees-pdf/" ,'_blank');
        }else{
            window.open("../taxe/liste-taxes-payees-by-periode-pdf/" + dateDebut + '/' + dateFin,'_blank');
        }
    }
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


