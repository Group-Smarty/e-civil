@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Courrier')
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
               data-url="{{url('courrier',['action'=>'liste-courriers-recus'])}}"
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
            <th data-field="service.libelle_service">Service </th>
            <th data-field="traiter" data-formatter="etatFormatter" data-align="center">Retirer</th>
            <th data-field="commentaire" data-visible="false">Commentaire</th>
            <th data-formatter="imageFormatter" data-visible="true">Document</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 65%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-mail-forward fa-2x"></i>
                        Gestion des courriers entrant
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" name="idCourrier" ng-hide="true" ng-model="courrier.id"/>
                    <input type="text" class="hidden" name="emmettre_recu" ng-hide="true" value="Recus"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-bold text-green">
                                <label>
                                    <input type="checkbox" id="particulier" name="particulier" ng-model="courrier.particulier" ng-checked="courrier.particulier">&nbsp; Cochez cette case si le courrier provient d'un particulier
                                </label>
                            </h5>
                        </div>
                    </div>
                    <div class="row" id="row_particulier">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom et pr&eacute;nom(s) de la personne *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="courrier.full_nam_particulier" id="full_nam_particulier" name="full_nam_particulier" placeholder="Nom et prénom(s) de la personne destinataire">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact de la personne *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="courrier.contact_particulier" id="contact_particulier" name="contact_particulier" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact de la personne destinataire">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="row_annuaire">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Emetteur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bank"></i>
                                    </div>
                                    <select id="annuaire_id" name="annuaire_id"  class="form-control select2">
                                        <option value="" ng-show="false">-- Sectionner la soci&eacute;t&eacute; --</option>
                                        @foreach($annuaires as $annuaire)
                                        <option value="{{$annuaire->id}}"> {{$annuaire->raison_sociale}}</option>
                                        @endforeach 
                                    </select>
                                    <span class="input-group-btn">
                                        <button title="Ajouter s'il n'y a pas dans la liste" type="button" class="btn btn-info btn-flat addAnnuaire"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Personne &agrave; contacter *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="name_personne_annuaire"  placeholder="Personne à contacter" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact de la personne *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="contact_personne_annuaire"  placeholder="Contact de la personne" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date de reception du courrier *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="courrier.date_courriers" id="date_courrier" name="date_courrier" placeholder="Ex : 01-01-1994 14:00" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Objet *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="courrier.objet" id="objet" name="objet" placeholder="Objet du colis" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type de courrier *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <select name="type_courrier_id" id="type_courrier_id" ng-model="courrier.type_courrier_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Sectionner le type de colis --</option>
                                        @foreach($typeCourriers as $type)
                                        <option value="{{$type->id}}"> {{$type->libelle_type_courrier}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Service concern&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="service_id" id="service_id" ng-model="courrier.service_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Sectionner le service --</option>
                                        @foreach($services as $service)
                                        <option value="{{$service->id}}"> {{$service->libelle_service}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Document scanner si disponible (png, jpeg, jpg)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file-archive-o"></i>
                                    </div>
                                    <input type="file" class="form-control" name="document_scanner">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Commentaire (Facultatif)</label>
                                <textarea class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" name="commentaire" id="commentaire" ng-model="courrier.commentaire" rows="5" placeholder="Taper votre texte..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-bold text-green">
                            <label>
                                <input type="checkbox" id="traiter" name="traiter" ng-model="courrier.traiter" ng-checked="courrier.traiter">&nbsp; Retirer par le service ?
                            </label>
                        </h5>
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

<!-- Modal ajout d'annuaire --> 
<div class="modal fade bs-modal-ajout-annuaire" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 60%">
        <form id="formAjoutAnnuaire" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-book fa-2x"></i>
                        Gestion des annuaires
                    </span>
                </div>
                <div class="modal-body ">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Raison sociale de la soci&eacute;t&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bank"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="raison_sociale" placeholder="Nom de la société" required>
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
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="adresse_siege" placeholder="Adresse de la société" required>
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
                                    <select name="type_societe_id" class="form-control" required>
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
                                    <select name="secteur_id" class="form-control" required>
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
                                    <select name="civilite_personne_contacter" ng-init="annuaire.civilite_personne_contacter='M'" class="form-control" required>
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
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="full_name_personne_contacter" placeholder="Nom et prénom(s)" required>
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
                                    <input type="text" class="form-control bfh-phone" name="contact1" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact 1" required>
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
                                    <input type="text" class="form-control bfh-phone" name="contact2" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact 2">
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
                                    <input type="email" class="form-control" name="email" placeholder="Adresse mail de la personne" required>
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
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="post_occupe" placeholder="Poste occupé dans la société" required>
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

<!-- Modal document -->
<div class="modal fade bs-modal-image" id="image" ng-controller="ImageCtrl" category="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 75%">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <span style="font-size: 16px;">
                        <i class="fa fa-file-archive-o fa-2x"></i>
                       Courrier du <b>@{{courrier.date_courriers + ' de ' + (courrier.particulier == 1 ? courrier.full_nam_particulier : courrier.annuaire.raison_sociale)}}</b>
                    </span>
                </div>
                <div class="modal-body ">
                    <div class="box-body">
                        <img class="img-responsive pad" src="{{asset('')}}@{{courrier.document_scanner}}" alt="Contrat">
                    </div>
                </div>
            </div>
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
                    <input type="text" class="hidden" id="idCourrierSupprimer"  ng-model="courrier.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le courrier de <br/><b>@{{courrier.particulier == 1 ? courrier.full_nam_particulier : courrier.annuaire.raison_sociale}}</b></div>
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
        $scope.populateForm = function (courrier) {
        $scope.courrier = courrier;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.courrier = {};
        };
    });

    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (courrier) {
        $scope.courrier = courrier;
        };
        $scope.initForm = function () {
        $scope.courrier = {};
        };
    });
    
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
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers-recus'])}}"});
            }else{
              $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-date/' + date + '/recus'});
            }
        });
        $("#searchByObjet").keyup(function (e) {
            var resultat = $("#searchByObjet").val();
            if(resultat == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers-recus'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-objet/' + resultat + '/recus'});
            }
        });
        $("#searchBySociete").change(function (e) {
            var societe = $("#searchBySociete").val();
            if(societe == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers-recus'])}}"});
            }else{
              $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-societe/' + societe + '/recus'});
            }
        });
        $("#searchByService").change(function (e) {
            var service = $("#searchByService").val();
            if(service == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('courrier', ['action' => 'liste-courriers-recus'])}}"});
            }else{
              $table.bootstrapTable('refreshOptions', {url: '../courrier/liste-courriers-by-service/' + service});
            }
        });
        
        $('#date_courrier').datetimepicker({
            allowTimes: [
                '07:00', '08:00', '9:00','10:00','11:00',
                '13:00', '14:00', '15:00', '16:00',
            ],
            timepicker: true,
            formatDate: 'd-m-Y',
            formatTime: 'H:i',
            format: 'd-m-Y H:i',
            local : 'fr'
        });
        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 
        $("#row_particulier").hide();
        $("#row_annuaire").show();
        $("#annuaire_id").select2({width: '100%', allowClear: true});
        $(".addAnnuaire").on("click", function () {
            document.forms["formAjoutAnnuaire"].reset();
            $(".bs-modal-ajout-annuaire").modal("show");
        });
        $("#btnModalAjout").on("click", function () {
            $("#row_particulier").hide();
            $("#row_annuaire").show();
            $("#annuaire_id").select2("val", "");
            $('#full_nam_particulier').prop('required', false);
            $('#contact_particulier').prop('required', false);
            $('#full_nam_particulier').val("");
            $('#contact_particulier').val("");
            $('#name_personne_annuaire').val("");
            $('#contact_personne_annuaire').val("");
        });
        $('#particulier').click(function(){
            if(document.querySelector('#particulier:checked') !== null)
            {
                document.getElementById("particulier").checked = true;
                $("#row_particulier").show();
                $("#row_annuaire").hide();
                $('#name_personne_annuaire').val("");
                $('#contact_personne_annuaire').val("");
                $("#annuaire_id").select2("val", "");
                $('#full_nam_particulier').prop('required', true);
                $('#contact_particulier').prop('required', true);
                $('#full_nam_particulier').val("");
                $('#contact_particulier').val("");
            }else{
                document.getElementById("particulier").checked = false;
                $("#row_particulier").hide();
                $("#annuaire_id").select2("val", "");
                $("#row_annuaire").show();
                $('#full_nam_particulier').prop('required', false);
                $('#contact_particulier').prop('required', false);
                $('#full_nam_particulier').val("");
                $('#contact_particulier').val("");
                $('#name_personne_annuaire').val("");
                $('#contact_personne_annuaire').val("");
            }
        });
        
         $("#annuaire_id").change(function (e) {
            var annuaire_id = $("#annuaire_id").val();
            $.getJSON("../courrier/find-annuaire-by-id/" + annuaire_id, function (reponse) {
                if(reponse.total>0){
                    $.each(reponse.rows, function (index, annuaire) { 
                        $('#name_personne_annuaire').val("");
                        $('#contact_personne_annuaire').val("");
                        $('#name_personne_annuaire').val(annuaire.full_name_personne_contacter);
                        $('#contact_personne_annuaire').val(annuaire.contact1);
                    });
                }else{
                   $('#name_personne_annuaire').val("");
                   $('#contact_personne_annuaire').val("");
                }
           })  
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
                var url = "{{route('courrier.courriers.store')}}";
             }else{
                var methode = 'POST';
                var url = "{{route('courrier.update-courrier')}}";
             }
             var formData = new FormData($(this)[0]);
            editerCourrierAction(methode, url, $(this), formData, $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCourrierSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('courriers/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
        
        $("#formAjoutAnnuaire").submit(function (e) {
            e.preventDefault();
            var $valid = $(this).valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            var $ajaxLoader = $("#formAjoutAnnuaire .loader-overlay");
            var methode = 'POST';
            var url = "{{route('courrier.annuaires.store')}}";
            editerAnnuaire(methode, url, $(this), $(this).serialize(), $ajaxLoader);
        });
    });

    function updateRow(idCourrier) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var courrier =_.findWhere(rows, {id: idCourrier});
         $scope.$apply(function () {
            $scope.populateForm(courrier);
        });
         if(courrier.particulier==1){
                $("#row_particulier").show();
                $("#row_annuaire").hide();
                $('#name_personne_annuaire').val("");
                $('#contact_personne_annuaire').val("");
                $("#annuaire_id").select2("val", "");
                $('#full_nam_particulier').prop('required', true);
                $('#contact_particulier').prop('required', true);
         }else{
                $("#row_particulier").hide();
                $("#annuaire_id").select2("val", courrier.annuaire_id);
                $("#row_annuaire").show();
                $('#full_nam_particulier').prop('required', false);
                $('#contact_particulier').prop('required', false);
                $('#full_nam_particulier').val("");
                $('#contact_particulier').val("");
         }
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idCourrier) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var courrier =_.findWhere(rows, {id: idCourrier});
           $scope.$apply(function () {
              $scope.populateForm(courrier);
          });
       $(".bs-modal-suppression").modal("show");
    }
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
    
    function contactFormatter(id, row){
        return row.annuaire_id ? row.annuaire.contact1 : row.contact_particulier;
    }
     function imageFormatter(id, row) { 
//        return row.document_scanner ? '<img width=50 height=50 style="cursor: pointer;" title="Voir le document" onClick="javascript:voirImg(' + row.id + ');" src="{{asset('')}}' + row.document_scanner+'"/>' : "";
          return row.document_scanner ? "<a target='_blank' href='" + basePath + '/' + row.document_scanner + "'>Voir le document</a>" : "";
    }
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    function etatFormatter(etat){
        return etat==1 ? "<span class='label label-success'>OUI</span>":"<span class='label label-danger'>NON</span>";
    }
    function editerCourrierAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
    jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        contentType: false,
        processData: false,
        success:function (reponse, textStatus, xhr){
            if (reponse.code === 1) {
                var $scope = angular.element($formObject).scope();
                $scope.$apply(function () {
                    $scope.initForm();
                });
                if (ajout) { //creation
                    $table.bootstrapTable('refresh');
                    $("#annuaire_id").select2("val", "");
                    $('#name_personne_annuaire').val("");
                    $('#contact_personne_annuaire').val("");
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
        
    function editerAnnuaire(methode, url, $formObject, formData, $ajoutLoader) {
        jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        success:function (reponse, textStatus, xhr){
            if (reponse.code === 1) {
                $.getJSON("../courrier/liste-annuaires-last/", function (reponse) {
                    $('#annuaire_id').html("<option>--Aucun--</option>");
                   $.each(reponse.rows, function (index, annuaire) { 
                       $('#annuaire_id').append("<option selected='selected' value=" + annuaire.id + ">" + annuaire.raison_sociale + "</option>")
                       $("#annuaire_id").select2("val", annuaire.id);
                    });
              });
            $(".bs-modal-ajout-annuaire").modal("hide");
            }
            $.gritter.add({
                // heading of the notification
                title: "E-Civil",
                // the text inside the notification
                text: reponse.msg,
                sticky: false,
                image: "../assets/img/gritter/confirm.png",
            });
         },
          error: function (err) {
            var res = eval('('+err.responseText+')');
            var messageErreur = res.message;
            if(res.message == "The given data was invalid."){
               // messageErreur = "Cet enregistrement existe dèjà";
                messageErreur = "Erreur survenue lors de l'enregistrement.";
            }
            $.gritter.add({
                // heading of the notification
                title: "E-Civil",
                // the text inside the notification
                text: messageErreur,
                sticky: false,
                image: "../assets/img/gritter/confirm.png",
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
    }
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection
