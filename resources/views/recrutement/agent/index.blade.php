@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur')
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
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom">
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
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByFonction"  class="form-control">
            <option value="0">--- Toutes les fonctions ---</option>
            @foreach($fonctions as $fonction)
            <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
        <select id="searchBySexe"  class="form-control">
            <option value="tous">--- Tous les sexes ---</option>
            <option value="Masculin">Masculin</option>
            <option value="Feminin">Feminin</option>
        </select>
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('recrutement',['action'=>'liste-agents'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-formatter="nameFormatter" data-sortable="true">Nom complet  </th>
            <th data-field="phone1">Contact </th>
            <th data-field="service.libelle_service">Service</th>
            <th data-field="fonction.libelle_fonction">Fonction</th>
            <th data-field="commune.libelle_commune" data-align="center">Commune </th>
            <th data-field="adresse">Adresse</th>
            <th data-field="phone2" data-visible="false">Contact 2</th>
            <th data-field="email" data-formatter="mailFormatter" data-visible="true">E-mail </th>
            <th data-field="sexe" data-visible="false">Sexe</th>
            <th data-field="numero_piece_identite" data-visible="false">N° Pi&egrave;ce </th>
            <th data-field="situation_matrimoniale" data-visible="false">Situation M.</th>
            <th data-field="date_naissances" data-visible="false">Date de naissance </th>
            <th data-field="lieu_naissance" data-visible="false">Lieu de naissance </th>
            <th data-field="numero_securite" data-visible="false">N° de s&eacute;curit&eacute; </th>
            <th data-field="type_piece.libelle_type_piece" data-visible="false">Type pi&egrave;ce </th>
            @if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur')
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
            @endif
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 70%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-users fa-2x"></i>
                        Gestion des agents
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idAgentModifier" ng-hide="true" ng-model="agent.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom complet *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="agent.full_name_agent" id="full_name" name="full_name" placeholder="Nom et prénom(s)" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sexe *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-genderless"></i>
                                    </div>
                                    <select name="sexe" id="sexe" ng-model="agent.sexe" ng-init="agent.sexe='Masculin'" class="form-control" required>
                                        <option value="Masculin">Masculin</option>
                                        <option value="Feminin">Feminin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Situation Matrimoniale *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-double"></i>
                                    </div>
                                    <select name="situation_matrimoniale" id="situation_matrimoniale" ng-model="agent.situation_matrimoniale" ng-init="agent.situation_matrimoniale='Célibataire'" class="form-control" required>
                                        <option value="Célibataire">C&eacute;libataire</option>
                                        <option value="Marié(e)">Mari&eacute;(e)</option>
                                        <option value="Autres">Autres</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact 1 *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="agent.phone1" id="phone1" name="phone1" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact 1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact 2</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="agent.phone2" id="phone2" name="phone2" data-format="(dd) dd-dd-dd-dd" placeholder="Contact 2">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>E-mail </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="agent.email" id="email" name="email" placeholder="Adresse mail">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Commune de r&eacute;sidence *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map"></i>
                                    </div>
                                    <select name="commune_id" id="commune_id" class="form-control select2" required>
                                        <option value="" ng-show="false">-- Selectionner la commune --</option>
                                        @foreach($communes as $commune)
                                        <option value="{{$commune->id}}"> {{$commune->libelle_commune}}</option>
                                        @endforeach
                                    </select>
                                       </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Adresse de r&eacute;sidence / Quartier *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="agent.adresse" id="adresse" name="adresse" placeholder="Adresse de résidence" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type de pi&egrave;ce d'identit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bars"></i>
                                    </div>
                                    <select name="type_piece_id" id="type_piece_id" ng-model="agent.type_piece_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Selectionner le type de pi&egrave;ce --</option>
                                        @foreach($typePieces as $typePiece)
                                        <option value="{{$typePiece->id}}"> {{$typePiece->libelle_type_piece}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° de la pi&egrave;ce d'identit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="agent.numero_piece_identite" id="numero_piece_identite" name="numero_piece_identite" placeholder="Numéro de la pièce d'identité" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date de naissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="agent.date_naissances" id="date_naissance" name="date_naissance" placeholder="Ex: 01-01-1994" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lieu de naissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="agent.lieu_naissance" id="lieu_naissance" name="lieu_naissance" placeholder="Lieu de naissance" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Service *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-server"></i>
                                    </div>
                                    <select name="service_id" id="service_id" ng-model="agent.service_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Selectionner le service --</option>
                                        @foreach($services as $service)
                                        <option value="{{$service->id}}"> {{$service->libelle_service}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fonction *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <select name="fonction_id" id="fonction_id" ng-model="agent.fonction_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Selectionner la fonction --</option>
                                        @foreach($fonctions as $fonction)
                                        <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° de s&eacute;curit&eacute; sociale (CNPS ou autres)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-ambulance"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="agent.numero_securite" id="numero_securite" name="numero_securite" placeholder="Numéro de sécurité sociale">
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
                    <input type="text" class="hidden" id="idAgentSupprimer"  ng-model="agent.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer l'agent <br/><b>@{{agent.full_name_agent}}</b></div>
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
        $scope.populateForm = function (agent) {
        $scope.agent = agent;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.agent = {};
        };
    });

    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (agent) {
        $scope.agent = agent;
        };
        $scope.initForm = function () {
        $scope.agent = {};
        };
    });

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });

       $('#date_naissance').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 
        $("#commune_id").select2({width: '100%', allowClear: true});
        $("#btnModalAjout").on("click", function () {
           $("#commune_id").select2("val", "");
        });
        
        $("#searchByName").keyup(function (e) {
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-agents'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-agents-by-name/' + name});
            }
        });

        $("#searchByService").change(function (e) {
            var service = $("#searchByService").val();
            if(service == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-agents'])}}"});
            }else{
                if($("#searchByService").val()!=0 && $("#searchByFonction").val()!=0){
                    var service = $("#searchByService").val(); var fonction = $("#searchByFonction").val();
                    $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-agents-by-service-fonction/' + service + '/' + fonction});
                }else{
                    $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-agents-by-service/' + service});
                }
            }
        });
        $("#searchByFonction").change(function (e) {
            var fonction = $("#searchByFonction").val();
            if(fonction == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-agents'])}}"});
            }else{
                if($("#searchByService").val()!=0 && $("#searchByFonction").val()!=0){
                    var service = $("#searchByService").val(); var fonction = $("#searchByFonction").val();
                    $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-agents-by-service-fonction/' + service + '/' + fonction});
                }else{
                    $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-agents-by-fonction/' + fonction});
                }
            }
        });
        $("#searchBySexe").change(function (e) {
            var sexe = $("#searchBySexe").val();
            if(sexe == 'tous'){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-agents'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-agents-by-sexe/' + sexe});
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
                var url = "{{route('recrutement.agents.store')}}";
             }else{
                var id = $("#idAgentModifier").val();
                var methode = 'PUT';
                var url = 'agents/' + id;
             }
            editerAgentAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idAgentSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('agents/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });

    function updateRow(idAgent) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var agent =_.findWhere(rows, {id: idAgent});
         $scope.$apply(function () {
            $scope.populateForm(agent);
        });
        $('#commune_id').select2("val", agent.commune_id);
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idAgent) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var agent =_.findWhere(rows, {id: idAgent});
           $scope.$apply(function () {
              $scope.populateForm(agent);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function nameFormatter(id, row){
        var name = 'M. '+row.full_name_agent;
        if(row.sexe == 'Feminin' && row.situation_matrimoniale == 'Marié(e)'){
            name = 'Mme. '+row.full_name_agent;
        }
        if(row.sexe == 'Feminin' && row.situation_matrimoniale != 'Marié(e)'){
            name = 'Mlle. '+row.full_name_agent;
        }
        return name;
    }
    
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function mailFormatter(mail){
        return mail ? '<a href="mailto:' + mail + '">' + mail + '</a>' : "";
    }
    
    function editerAgentAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
    jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        success:function (reponse, textStatus, xhr){
            if (reponse.code === 1) {
                var $scope = angular.element($formObject).scope();
                $scope.$apply(function () {
                    $scope.initForm();
                });
                if (ajout) { //creation
                    $table.bootstrapTable('refresh');
                    $("#commune_id").select2("val", "");
                } else { //Modification
                    $table.bootstrapTable('updateByUniqueId', {
                        id: reponse.data.id,
                        row: reponse.data
                    });
                    $table.bootstrapTable('refresh');
                    $(".bs-modal-ajout").modal("hide");
                }
                $formObject.trigger('eventAjouter', [reponse.data]);
            }
            $.gritter.add({
                // heading of the notification
                title: "E-Civil",
                // the text inside the notification
                text: reponse.msg,
                sticky: false,
                image: basePath + "/assets/img/gritter/confirm.png",
            });
         },
          error: function (err) {
            var res = eval('('+err.responseText+')');
            var messageErreur = res.message;
            
            $.gritter.add({
                // heading of the notification
                title: "E-Civil",
                // the text inside the notification
                text: messageErreur,
                sticky: false,
                image: basePath + "/assets/img/gritter/confirm.png",
            });
            $formObject.removeAttr("disabled");
            $ajoutLoader.hide();
        },
         beforeSend: function () {
            $formObject.attr("disabled", true);
            $ajoutLoader.show();
        },
        complete: function () {
            $ajoutLoader.hide();
        },
    });
	};
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection