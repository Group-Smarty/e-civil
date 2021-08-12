@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="true"
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-demandes-recues'])}}"
               data-unique-id="id"
               data-show-toggle="false">
            <thead>
                <tr>
                    <th data-field="date_demandes">Date d&eacute;mande</th>
                    <th data-field="numero_demande" data-searchable="true">N° d&eacute;mande</th>
                    <th data-field="nom_demandeur" data-searchable="true">Nom du d&eacute;mandeur</th>
                    <th data-field="contact_demandeur">Contact</th>
                    <th data-field="type_demande" data-formatter="typeDemandeFormatter">Type</th>
                    <th data-field="numero_acte" data-searchable="true">N° acte</th>
                    <th data-field="nombre_copie">Copie</th>
                    <th data-field="copie_integrale" data-formatter="copieIntegraleFormatter">Copie int&eacute;gr.</th>
                    <th data-field="etat_demande" data-formatter="etatDemandeFormatter">Etat d&eacute;mandeur</th>
                    <!-- <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th> -->
                </tr>
            </thead>
        </table>
    </div>
</div>


<script type="text/javascript">
    var ajout = true;
    var $table = jQuery("#table"), rows = [];
    
    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (commune) {
            $scope.commune = commune;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.commune = {};
        };
    });
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (commune) {
            $scope.commune = commune;
        };
        $scope.initForm = function () {
            $scope.commune = {};
        };
    });
    
    $(function () {
       $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });

      $("#formAjout").submit(function (e) {
            e.preventDefault();
            var $valid = $(this).valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            var $ajaxLoader = $("#formAjout .loader-overlay");

             if (ajout==true) {
                var methode = 'POST';
                var url = "{{route('parametre.communes.store')}}";
             }else{
                var id = $("#idCommuneModifier").val();
                var methode = 'PUT';
                var url = 'communes/' + id;
             }
            editerAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCommuneSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('communes/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });

    });
    
    function updateRow(idCommune) {
        ajout= false;
        var $scope = angular.element($("#formAjout")).scope();
        var commune =_.findWhere(rows, {id: idCommune});
         $scope.$apply(function () {
            $scope.populateForm(commune);
        });
    }

    function deleteRow(idCommune) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var commune =_.findWhere(rows, {id: idCommune});
           $scope.$apply(function () {
              $scope.populateForm(commune);
          });
       $(".bs-modal-suppression").modal("show");
    }

    function typeDemandeFormatter(type){
        if(type == 'naissance') {
            return "<span class='text-bold'> Acte de naissance </span>";
        }
        if(type == 'mariage') {
            return "<span class='text-bold'> Acte de mariage </span>";
        }
        if(type == 'deces') {
            return "<span class='text-bold'> Acte de décès </span>";
        }
    }

    function copieIntegraleFormatter(copie){
        return copie ? "<span class='text-bold'> Oui </span>" : "<span class='text-bold'> Non </span>";
    }

    function etatDemandeFormatter(etat){
        if(etat == 1) {
            return "<span class='text-bold'> Reçue </span>";
        }
        if(etat == 2) {
            return "<span class='text-bold'> En traitement </span>";
        }
        if(etat == 3) {
            return "<span class='text-bold'> Terminée </span>";
        }
        if(etat == 4) {
            return "<span class='text-bold'> Rejetée </span>";
        }
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


