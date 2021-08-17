@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Taxe' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<div class="col-md-4">
    <div class="form-group">
        <select id="searchByCaisse"  class="form-control">
            <option value="0">--- Toutes les caisses ---</option>
            @foreach($caisses as $caisse)
            <option value="{{$caisse->id}}"> {{$caisse->libelle_caisse}}</option>
            @endforeach
        </select>
    </div>
</div> 
<div class="col-md-4">
    <div class="form-group">
        <select id="searchByCaissier"  class="form-control">
            <option value="0">--- Tous les caissiers ---</option>
            @foreach($caissiers as $caissier)
            <option value="{{$caissier->id}}"> {{$caissier->full_name}}</option>
            @endforeach
        </select>
    </div>
</div>

<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('taxe',['action'=>'liste-billetages'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="id" data-formatter="billetageFormatter" data-width="60px" data-align="center">Billetage</th>
            <th data-field="libelle_caisse">Caisse </th>
            <th data-field="full_name">Caissier(e) </th>
            <th data-field="date_ouvertures">Ouverture </th>
            <th data-field="montant_ouverture" data-formatter="montantFormatter">Montant Ouverture </th>
            <th data-field="entree" data-formatter="montantFormatter">Entr&eacute;e </th>
            <th data-field="sortie" data-formatter="montantFormatter">Sortie</th>
            <th data-field="date_fermetures">Fermeture </th>
            <th data-formatter="soldeFormatter">Solde fermeture</th>
        </tr>
    </thead>
</table>
<script type="text/javascript">
    var $table = jQuery("#table"), rows = [];
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $("#searchByCaisse").change(function (e) {
            var caisse = $("#searchByCaisse").val();
            if(caisse==0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-billetages'])}}"}); 
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-billetages-by-caisse/' + caisse});
            }
        });
        
       $("#searchByCaissier").change(function (e) {
            var caissier = $("#searchByCaissier").val();
            if(caissier==0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-billetages'])}}"}); 
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-billetages-by-caissier/' + caissier});
            }
        });
    });
    
    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    
    function soldeFormatter(id, row){
        var montant = 0;
        montant = (row.montant_ouverture + row.entree)-row.sortie;
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
 
    function billetPrintRow(idCaisseOuverte){
        window.open("../taxe/billetage-pdf/" +idCaisseOuverte ,'_blank');
    }
    
    function billetageFormatter(id, row){
        return '<button type="button" class="btn btn-xs btn-info" data-placement="left" data-toggle="tooltip" title="Ticket" onClick="javascript:billetPrintRow(' + row.id + ');"><i class="fa fa-file-pdf-o"></i></button>';
    }
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


