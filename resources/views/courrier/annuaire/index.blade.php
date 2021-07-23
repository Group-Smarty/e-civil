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
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rech. par nom de personne ou raison sociale">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchBySecteur"  class="form-control">
            <option value="0">--- Tous les secteurs ---</option>
            @foreach($secteurs as $secteur)
            <option value="{{$secteur->id}}"> {{$secteur->libelle_secteur}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByType"  class="form-control">
            <option value="0">--- Tous les type de soci&eacute;t&eacute;s ---</option>
            @foreach($typeSocietes as $societe)
            <option value="{{$societe->id}}"> {{$societe->libelle_type_societe}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByContact" placeholder="Rechercher par contact">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('courrier',['action'=>'liste-annuaires'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-formatter="nameFormatter">Personne &agrave; contacter </th>
            <th data-field="contact1">Contact </th>
            <th data-field="email" data-formatter="mailFormatter">E-mail </th>
            <th data-field="raison_sociale" data-sortable="true">Soci&eacute;t&eacute;  </th>
            <th data-field="adresse_siege">Adresse </th>
            <th data-field="secteur.libelle_secteur">Secteur</th>
            <th data-field="type_societe.libelle_type_societe" data-visible="false">Type</th>
            <th data-field="post_occupe">Poste occup&eacute;</th>
            <th data-field="contact2" data-visible="false">Contact 2</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 60%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-book fa-2x"></i>
                        Gestion des annuaires
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idAnnuaireModifier" ng-hide="true" ng-model="annuaire.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Raison sociale de la soci&eacute;t&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bank"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="annuaire.raison_sociale" id="raison_sociale" name="raison_sociale" placeholder="Nom de la société" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Adresse *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="annuaire.adresse_siege" id="adresse_siege" name="adresse_siege" placeholder="Adresse de la société" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type de soci&eacute;t&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list-ol"></i>
                                    </div>
                                    <select name="type_societe_id" id="type_societe_id" ng-model="annuaire.type_societe_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Sectionner le type --</option>
                                        @foreach($typeSocietes as $societe)
                                        <option value="{{$societe->id}}"> {{$societe->libelle_type_societe}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Secteur d'activit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-institution"></i>
                                    </div>
                                    <select name="secteur_id" id="secteur_id" class="form-control" ng-model="annuaire.secteur_id" required>
                                        <option value="" ng-show="false">-- Sectionner le secteur --</option>
                                        @foreach($secteurs as $secteur)
                                        <option value="{{$secteur->id}}"> {{$secteur->libelle_secteur}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-bold text-green">Personne &agrave; contacter </h5>
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Civilit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-copyright"></i>
                                    </div>
                                    <select name="civilite_personne_contacter" id="civilite_personne_contacter" ng-model="annuaire.civilite_personne_contacter" ng-init="annuaire.civilite_personne_contacter='M'" class="form-control" required>
                                        <option value="M">Monsieur</option>
                                        <option value="Mme">Madame</option>
                                        <option value="Mlle">Mademoiselle</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                       <div class="col-md-8">
                            <div class="form-group">
                                <label>Nom & pr&eacute;nom(s) de la personne *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="annuaire.full_name_personne_contacter" id="full_name_personne_contacter" name="full_name_personne_contacter" placeholder="Nom et prénom(s)" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact 1 *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="annuaire.contact1" id="contact1" name="contact1" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact 1" required>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact 2 </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="annuaire.contact2" id="contact2" name="contact2" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact 2">
                                </div>
                            </div>
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-mail *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="email" class="form-control" ng-model="annuaire.email" id="email" name="email" placeholder="Adresse mail de la personne" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Poste occup&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="annuaire.post_occupe" id="post_occupe" name="post_occupe" placeholder="Poste occupé dans la société" required>
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
                    <input type="text" class="hidden" id="idAnnuaireSupprimer"  ng-model="annuaire.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer l'annuaire de <br/><b>@{{annuaire.full_name_personne_contacter}}</b></div>
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
        $scope.populateForm = function (annuaire) {
        $scope.annuaire = annuaire;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.annuaire = {};
        };
    });

    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (annuaire) {
        $scope.annuaire = annuaire;
        };
        $scope.initForm = function () {
        $scope.annuaire = {};
        };
    });

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $("#searchByName").keyup(function (e) {
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-annuaires'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-annuaires-by-name/' + name});
            }
        });

        $("#searchBySecteur").change(function (e) {
            var secteur = $("#searchBySecteur").val();
            if(secteur == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-annuaires'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-annuaires-by-secteur/' + secteur});
            }
        });
        $("#searchByType").change(function (e) {
            var type_societe = $("#searchByType").val();
            if(type_societe == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-annuaires'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-annuaires-by-type-societe/' + type_societe});
            }
        });
        $("#searchByContact").keyup(function (e) {
            var contact = $("#searchByContact").val();
            if(contact == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-annuaires'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-annuaires-by-contact/' + contact});
            }
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
                var url = "{{route('courrier.annuaires.store')}}";
             }else{
                var id = $("#idAnnuaireModifier").val();
                var methode = 'PUT';
                var url = 'annuaires/' + id;
             }
            editerAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idAnnuaireSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('annuaires/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });

    function updateRow(idAnnuaire) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var annuaire =_.findWhere(rows, {id: idAnnuaire});
         $scope.$apply(function () {
            $scope.populateForm(annuaire);
        });
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idAnnuaire) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var annuaire =_.findWhere(rows, {id: idAnnuaire});
           $scope.$apply(function () {
              $scope.populateForm(annuaire);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function nameFormatter(id, row){
        return row.civilite_personne_contacter +' ' + row.full_name_personne_contacter;
    }
    
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function mailFormatter(mail){
        return '<a href="mailto:' + mail + '">' + mail + '</a>';
    }
    
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection