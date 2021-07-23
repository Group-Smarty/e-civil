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
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByNumeroDeclaration" placeholder="Rechercher par N° de déclaration">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rech. par date de déclaration ou de retrait">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByNomDeclarant" placeholder="Rechercher par nom du déclarant">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByType"  class="form-control">
            <option value="0">--- Toutes les d&eacute;clarations ---</option>
            @foreach($typeDeclarations as $type)
            <option value="{{$type->type_declaration}}"> {{$type->type_declaration}}</option>
            @endforeach
        </select>
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-declarations'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="numero_declaration" data-sortable="true">N° d&eacute;claration  </th>
            <th data-field="full_name_declarant">D&eacute;clarant </th>
            <th data-field="date_declarations">Date de la d&eacute;claration </th>
            <th data-field="date_retrait_declarations">Date de retrait</th>
            <th data-field="type_declaration">Type d&eacute;claration</th>
            <th data-field="nombre_copie" data-align="center">Copie</th>
            <th data-field="montant" data-formatter="montantFormatter" data-align="center">Montant</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var $table = jQuery("#table"), rows = [];
   
    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });

       $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 
        
        $("#searchByNumeroDeclaration").keyup(function (e) {
            var numero = $("#searchByNumeroDeclaration").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-declarations'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-declarations-by-numero/' + numero});
            }
        });

        $("#searchByNomDeclarant").keyup(function (e) {
            var name = $("#searchByNomDeclarant").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-declarations'])}}"});
            }else{
                
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-declarations-by-name-declarant/' + name});
            }
        });
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-declarations'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-declarations-by-date/' + date});
            }
        });
        $("#searchByType").change(function (e) {
            var type = $("#searchByType").val();
            if(type == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-declarations'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-declarations-by-type/' + type});
            }
        });
    });
    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection