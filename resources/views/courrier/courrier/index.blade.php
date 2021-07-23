@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByObjet" placeholder="Rechercher par objet">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchBySociete"  class="form-control">
            <option value="0">--- Toutes les soci&eacute;t&eacute;s ---</option>
            @foreach($annuaires as $annuaire)
            <option value="{{$annuaire->id}}"> {{$annuaire->raison_sociale}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByService"  class="form-control">
            <option value="0">--- Tous les services ---</option>
            @foreach($services as $service)
            <option value="{{$service->id}}"> {{$service->libelle_service}}</option>
            @endforeach
        </select>
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('courrier',['action'=>'liste-courriers'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="date_courriers">Date </th>
            <th data-field="objet">Objet </th>
            <th data-field="type_courrier.libelle_type_courrier">Type de courrier </th>
            <th data-formatter="emmeteurFormatter">Emetteur</th>
            <th data-formatter="contactFormatter">Contact </th>
            <th data-formatter="serviceFormatter">Service </th>
            <th data-field="commentaire" data-visible="true">Commentaire</th>
            <th data-formatter="imageFormatter" data-visible="false">Document</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var ajout = false;
    var $table = jQuery("#table"), rows = [];
    
    appSmarty.controller('ImageCtrl', function ($scope) {
        $scope.populateForm = function (courrier) {
            $scope.courrier = courrier;
        };
    });

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers'])}}"});
            }else{
              $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-date/' + date + '/tous'});
            }
        });
        $("#searchByObjet").keyup(function (e) {
            var resultat = $("#searchByObjet").val();
            if(resultat == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-objet/' + resultat + '/tous'});
            }
        });
        $("#searchBySociete").change(function (e) {
            var societe = $("#searchBySociete").val();
            if(societe == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers'])}}"});
            }else{
              $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-societe/' + societe + '/tous'});
            }
        });
        $("#searchByService").change(function (e) {
            var service = $("#searchByService").val();
            if(service == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers'])}}"});
            }else{
              $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-service/' + service});
            }
        });
        
        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 
    });

    function voirImg(idImage) {
        var $scope = angular.element($("#image")).scope();
        var courrier =_.findWhere(rows, {id: idImage});
         $scope.$apply(function () {
            $scope.populateForm(courrier);
        });
        $(".bs-modal-image").modal("show");
    }
    function emmeteurFormatter(id, row){
        return row.annuaire_id ? row.annuaire.raison_sociale : row.full_nam_particulier;
    }
    
    function serviceFormatter(id, row){
        return row.service_id ? row.service.libelle_service : '<span class="text-bold text-red">Sortant</span>';
    }
    
    function contactFormatter(id, row){
        return row.annuaire_id ? row.annuaire.contact1 : row.contact_particulier;
    }
    function imageFormatter(id, row) { 
        return row.document_scanner ? '<img width=50 height=50 style="cursor: pointer;" title="Voir le document" onClick="javascript:voirImg(' + row.id + ');" src="{{asset('')}}' + row.document_scanner+'"/>' : "";
    }

</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection