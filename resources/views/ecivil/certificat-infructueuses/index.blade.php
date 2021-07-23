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
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom du concerné ou du demandeur">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByNumeroPiece" placeholder="Rechercher par N° de piéce d'identité">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de demande">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-certificat-infructueuses'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="date_demande_certificats">Date demande </th>
            <th data-field="nom_complet_concerne">Concern&eacute; </th>
            <th data-field="numero_certificat_medical">N° certificat med.</th>
            <th data-field="lieu_certificat_medical">Lieu certificat med.</th>
            <th data-field="date_certificat_medicals">Date certificat med.</th>
            <th data-field="lieu_evenement">Lieu &eacute;v&eacute;nement</th>
            <th data-field="date_evenements">Date &eacute;v&eacute;nement</th>
            <th data-field="nom_complet_demandeur">Demandeur</th>
            <th data-field="adresse_demandeur">Adresse</th>
            <th data-field="contact_demandeur">Contact</th>
            <th data-field="numero_piece_demandeur" data-visible="false">N° Pi&egrave;ce</th>
            <th data-field="montant" data-formatter="montantFormatter" data-align="center">Montant</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 75%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-search-plus fa-2x"></i>
                        Gestion des demandes de certificat de recherches infructueuse
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idCertificatRechercheInfructueuseModifier" ng-hide="true" ng-model="certificatRechercheInfructueuse.id"/>
                    @csrf
                      <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom du concern&eacute;(e) *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatRechercheInfructueuse.nom_complet_concerne" id="nom_complet_concerne" name="nom_complet_concerne" placeholder="Nom et prénom du concerné(e)" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom du demandeur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatRechercheInfructueuse.nom_complet_demandeur" id="nom_complet_demandeur" name="nom_complet_demandeur" placeholder="Nom et prénom du demandeur" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Adresse du demandeur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatRechercheInfructueuse.adresse_demandeur" id="adresse_demandeur" name="adresse_demandeur" placeholder="Adresse du demandeur" required>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° de la pi&egrave;ce du demandeur </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatRechercheInfructueuse.numero_piece_demandeur" id="numero_piece_demandeur" name="numero_piece_demandeur" placeholder="N° pièce du demandeur">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Contact du demandeur</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="certificatRechercheInfructueuse.contact_demandeur" id="contact_demandeur" name="contact_demandeur" data-format="(dd) dd-dd-dd-dd" placeholder="Contact">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date de la demande *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" ng-model="certificatRechercheInfructueuse.date_demande_certificats" id="date_demande_certificat" name="date_demande_certificat" value="<?=date('d-m-Y');?>" required>
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-2">
                                <div class="form-group">
                                    <label>Montant de la demande *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <input type="number" min="0" class="form-control" ng-model="certificatRechercheInfructueuse.montant" id="montant" name="montant" placeholder="Montant" required>
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Lieu &eacute;v&eacute;nement *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatRechercheInfructueuse.lieu_evenement" id="lieu_evenement" name="lieu_evenement" placeholder="Lieu événement" required>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° certificat m&eacute;dical *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatRechercheInfructueuse.numero_certificat_medical" id="numero_certificat_medical" name="numero_certificat_medical" placeholder="N° du certificat médical" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lieu certificat m&eacute;dical *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatRechercheInfructueuse.lieu_certificat_medical" id="lieu_certificat_medical" name="lieu_certificat_medical" placeholder="Lieu du certificat médical" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date certificat m&eacute;dical *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatRechercheInfructueuse.date_certificat_medicals" id="date_certificat_medical" name="date_certificat_medical" placeholder="Date du cert." required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date &eacute;v&eacute;nement *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatRechercheInfructueuse.date_evenements" id="date_evenement" name="date_evenement" placeholder="Date événement" required>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-send"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span>Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal suppresion -->
<div class="modal fade bs-modal-suppression" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formSupprimer" ng-controller="formSupprimerCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Confimation de la suppression
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idCertificatRechercheInfructueuseSupprimer"  ng-model="certificatRechercheInfructueuse.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande de <br/><b>@{{certificatRechercheInfructueuse.nom_complet_demandeur}}</b></div>
                        <div class="text-center vertical processing">Suppression en cours</div>
                        <div class="pull-right">
                            <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Non</button>
                            <button type="submit" class="btn btn-danger btn-sm ">Oui</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var ajout = false;
    var $table = jQuery("#table"), rows = [];
    
    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (certificatRechercheInfructueuse) {
        $scope.certificatRechercheInfructueuse = certificatRechercheInfructueuse;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.certificatRechercheInfructueuse = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (certificatRechercheInfructueuse) {
        $scope.certificatRechercheInfructueuse = certificatRechercheInfructueuse;
        };
        $scope.initForm = function () {
        $scope.certificatRechercheInfructueuse = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-infructueuses'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-infructueuses-by-name/' + name});
            }
        });
        $("#searchByNumeroPiece").keyup(function (e) { 
            var numero = $("#searchByNumeroPiece").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-infructueuses'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-infructueuses-by-piece-identite/' + numero});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-infructueuses'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-infructueuses-by-date/' + date});
            }
        });

        $('#searchByDate, #date_evenement, #date_demande_certificat,#date_certificat_medical').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date()
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
                var url = "{{route('e-civil.certificat-infructueuses.store')}}";
             }else{
                var id = $("#idCertificatRechercheInfructueuseModifier").val();
                var methode = 'PUT';
                var url = 'certificat-infructueuses/' + id;
             }
            editerAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
       
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCertificatRechercheInfructueuseSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('certificat-infructueuses/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idCertificatRechercheInfructueuse) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var certificatRechercheInfructueuse =_.findWhere(rows, {id: idCertificatRechercheInfructueuse});
         $scope.$apply(function () {
            $scope.populateForm(certificatRechercheInfructueuse);
        });
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idCertificatRechercheInfructueuse) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var certificatRechercheInfructueuse =_.findWhere(rows, {id: idCertificatRechercheInfructueuse});
           $scope.$apply(function () {
              $scope.populateForm(certificatRechercheInfructueuse);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function pdfRow(idCertificat){
        window.open("../e-civil/certificat-recherche-infructueuse-pdf/" + idCertificat ,'_blank')
    }

    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:pdfRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
   
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


