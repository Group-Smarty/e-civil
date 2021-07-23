@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Caissier' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur')
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
<div class="col-md-2">
    @if($caisse_ouverte == null)
    <button class="btn btn-sm btn-success pull-left" id="btnOuvrirCaisse"><i class="fa fa-unlock"></i> Ouvrir caisse</button>
    @else
    <button class="btn btn-sm btn-danger pull-left" id="btnFermerCaisse"><i class="fa fa-lock"></i> Fermer caisse</button>
    @endif
</div>
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByFacture" placeholder="Rech. par N° facture">
    </div>
</div>
<!--<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="dateDebut" placeholder="Date début">
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="dateFin" placeholder="Date fin">
    </div>
</div>-->
<div class="col-md-4">
    <div class="form-group">
        <select id="searchByContribuable"  class="form-control">
            <option value="0">--- Tous les contribuables ---</option>
            @foreach($contribuables as $contribuable)
            <option value="{{$contribuable->id}}"> {{$contribuable->nom_complet.' N° id : '.$contribuable->numero_identifiant}}</option>
            @endforeach
        </select>
    </div>
</div>
@if($caisse_ouverte != null)
<!--<div class="col-md-2">
    <button class="btn btn-sm btn-success pull-right" id="btnVenteTimbre">Timbre</button>
</div>-->
@endif
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               
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
            @if(Auth::user()->role!="Caissier")
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
                        <i class="fa fa-fax fa-2x"></i>
                        Gestion des payements de taxe
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idPayementModifier" ng-hide="true" ng-model="payement.id"/>
                        @if(Auth::user()->role!="Caissier")
                            <input type="hidden" name="caisse" value="{{$caisse->id}}"/>
                        @endif
                        @if(Auth::user()->role=="Caissier" && $caisse!=null && $caisse_ouverte != null)
                            <input type="hidden" name="caisse" value="{{$caisse->id}}"/>
                        @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Contribuable *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select id="contribuable" class="form-control" required>
                                        <option value="">Selectionner le contribuable</option>
                                        @foreach($contribuables as $contribuable)
                                        <option value="{{$contribuable->id}}"> {{$contribuable->nom_complet.' N° identif : '.$contribuable->numero_identifiant}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Structure *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bank"></i>
                                    </div>
                                    <select name="declaration_activite_id" id="declaration_activite_id" class="form-control" required>
                                        <option value="">Selectionner la structure</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date de payement *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="payement.date_payements" id="date_payement" name="date_payement" value="{{date('d-m-Y')}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payement effectu&eacute; par *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="payement.payement_effectuer_par" id="payement_effectuer_par" name="payement_effectuer_par" placeholder="Payement effectué par" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Montant *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <input type="text" pattern="[0-9]*" class="form-control" id="montant" name="montant" placeholder="Montant payé" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Prochain payement *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="payement.date_prochain_payements" id="date_prochain_payement" name="date_prochain_payement" placeholder="19-05-1994" required>
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
                    <input type="text" class="hidden" id="idPayementSupprimer"  ng-model="payement.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer ce payement <br/><b>@{{payement.numero_ticket}}</b></div>
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

<!-- Modal ouverture caisse-->
<div class="modal fade bs-modal-ouverture-caisse" role="dialog" data-backdrop="static">
    <form id="formOuvertureCaisse" class="form-horizontal" action="#" method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-green-active">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="circle">
                        Ouverture de caisse
                    </span>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="caisse_id" class="col-sm-4 control-label">Caisse *</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="caisse_id" id="caisse_id" required="required">
                                    <option value="">-- Chosir la caisse --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Montant &agrave; l'ouverture *</label>
                            <div class="col-sm-8">
                                <input type="number" min="0" name="montant_ouverture" class="form-control"  placeholder="Montant à l'ouverture" required="required"/>
                            </div>
                        </div>
                    </div>                            
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span> Valider</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal fermeture caisse-->
<div class="modal fade bs-modal-fermeture-caisse" category="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width:65%">
        <form id="formFermetureCaisse" action="#" method="post">
                <div class="modal-content">
                    <div class="modal-header bg-red">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <span class="circle">
                            Fermeture de caisse
                        </span>
                    </div>
                    @csrf
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block">
                                            <span class="description-text text-black">Montant a l'ouverture</span>
                                            <h5 class="description-header"><p class="text-black" id="montant_ouverture"></p></h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block">
                                            <span class="description-text text-green">Total entree</span>
                                            <h5 class="description-header"><p class="text-green" id="total_entree"></p></h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block">
                                            <span class="description-text text-red">Total sortie</span>
                                            <h5 class="description-header"><p class="text-red" id="total_sortie"></p></h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="description-block">
                                            <span class="description-text text-orange">Solde</span>
                                            <h5 class="description-header"><p class="text-orange" id="solde_caisse"></p></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="h3">Montant &agrave; la fermeture : <span class="h3" id="solde_fermeture_aff"></span></label>
                                            <input type="text" class="hidden" name="solde_fermeture" id="solde_fermeture"/>
                                            <input type="text" class="hidden" name="caisses_fermeture" id="caisses_fermeture"/>
                                        </div>
                                    </div>
                                </div>
                                @if(Auth::user()->role!='Caissier')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="motif_non_conformite" placeholder="Motif de non confirmité de la caisse"/>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <label class="text-center text-red">Assurez-vous du montant r&eacute;el de votre caisse. Contacter l'administrateur en cas d'anomalie.</label><br/>
                            </div> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Billet *</label>
                                            <select class="form-control" id="billet">
                                                <option value="">-- Selcetionner un element --</option>
                                                <option value="10000"> 10000</option>
                                                <option value="5000"> 5000</option>
                                                <option value="2000"> 2000</option>
                                                <option value="1000"> 1000</option>
                                                <option value="500"> 500</option>
                                                <option value="250"> 250</option>
                                                <option value="250"> 250</option>
                                                <option value="200"> 200</option>
                                                <option value="100"> 100</option>
                                                  <option value="50"> 50</option>
                                                  <option value="25"> 25</option>
                                                  <option value="10"> 10</option>
                                                <option value="5"> 5</option>
                                                <option value="0"> 0</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Quantit&eacute; </label>
                                            <input type="number" min="0" class="form-control" id="quantite_billet" placeholder="Quantité">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Montant</label>
                                            <input type="text" class="form-control" id="montant_billet" placeholder="Montant" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group"><br/>
                                            <button type="button" class="btn btn-success btn-sm  add-billetage-row pull-left"><i class="fa fa-plus">Ajouter</i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-xs delete-billetage-row">Supprimer ligne</button><br/><br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="tableBilletage" class="table table-success table-striped box box-warning"
                                               data-toggle="table"
                                               data-id-field="id"
                                               data-unique-id="id"
                                               data-click-to-select="true"
                                               data-show-footer="false">
                                            <thead>
                                                <tr>
                                                    <th data-field="state" data-checkbox="true"></th>
                                                    <th data-field="id">ID</th>
                                                    <th data-field="billet">Billet</th>
                                                    <th data-field="quantite_billet">Quantit&eacute;</th>
                                                    <th data-field="montant_billet">Montant</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-danger"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span> Fermer</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>

<input type="hidden" id="user_role" value="{{Auth::user()->role}}"/>
@if($caisse!=null)
<input type="hidden" id="caisse" value="{{$caisse->id}}"/>
@endif
<script type="text/javascript">
    var ajout = false;
    var $table = jQuery("#table"), rows = [], $tableBilletage = jQuery("#tableBilletage");
    
    var user_role = $('#user_role').val();
    var caisse_id = $("#caisse").val();
    var panierBillet = [];
    var idTablleBillet =  0;
    
    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (payement) {
        $scope.payement = payement;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.payement = {};
        };
    });

    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (payement) {
        $scope.payement = payement;
        };
        $scope.initForm = function () {
        $scope.payement = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes/' + caisse_id});

        $('#date_payement, #dateDebut, #dateFin').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date(),
        }); 
        
        $('#date_prochain_payement').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            minDate : new Date(),
        }); 
        
        $("#searchByFacture").keyup(function (e) {
            var numero = $("#searchByFacture").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes/' + caisse_id});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-facture/' + numero + '/' + caisse_id});
            }
        });
        
        $("#dateDebut, #dateFin").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            var contribuable = $("#searchByContribuable").val();
            
            if(dateDebut == '' && dateFin == '' && contribuable==0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-payements-taxes'])}}"});
            }
            if(dateDebut == '' && dateFin == '' && contribuable!=0){
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-contribuable/' + contribuable});
            }
            if(dateDebut != '' && dateFin != '' && contribuable==0){
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-periode/' + dateDebut + '/' + dateFin});
            }
            if(dateDebut != '' && dateFin != '' && contribuable!=0){
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-contribuable-periode/' + contribuable +'/' + dateDebut + '/' + dateFin});
            }
        });
        
        $("#searchByContribuable").change(function (e) {
//            var dateDebut = $("#dateDebut").val();
//            var dateFin = $("#dateFin").val();
            var contribuable = $("#searchByContribuable").val();
            
            if(contribuable==0){
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes/' + caisse_id});   
            }
            if(contribuable!=0){
                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-contribuable/' + contribuable + '/' + caisse_id});
            }
//            if(dateDebut != '' && dateFin != '' && contribuable==0){
//                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-periode/' + dateDebut + '/' + dateFin});
//            }
//            if(dateDebut != '' && dateFin != '' && contribuable!=0){
//                $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-payements-taxes-by-contribuable-periode/' + contribuable +'/' + dateDebut + '/' + dateFin});
//            }
        });
        
        $("#contribuable, #searchByContribuable").select2({width: '100%', allowClear: true});
        
        $("#btnModalAjout").on("click", function () {
            $("#contribuable, #declaration_activite_id").val('').trigger('change');
        });
       
        $("#contribuable").change(function (e) {
            var contribuable = $("#contribuable").val();
            $('#declaration_activite_id').html("<option value=''> Selectionner la structure </option>");
            $("#declaration_activite_id").val('').trigger('change');
            $.getJSON("../taxe/liste-activites-by-contribuable/" + contribuable, function (reponse) {
                if(reponse.total>0){
                    $.each(reponse.rows, function (index, structure) { 
                        $('#declaration_activite_id').append("<option value=" + structure.id + ">" + structure.nom_structure + "</option>")
                    });
                }else{
                    $("#declaration_activite_id").val('').trigger('change');
                }
            });
        });
        
        $("#declaration_activite_id").change(function (e) {
            var structure = $("#declaration_activite_id").val();
            $("#montant").val('');
          
            $.getJSON("../taxe/get-activite-by-id/" + structure, function (reponse) {
                if(reponse.total>0){
                    $.each(reponse.rows, function (index, structure) { 
                        $("#montant").val(structure.montant_taxe);
                    });
                }else{
                    $("#montant").val('');
                }
            });
        });
        
        $("#btnOuvrirCaisse").on("click", function () {
            if(user_role=="Caissier"){
                $.getJSON("../taxe/liste-caisses-fermees/", function (reponse) {
                    $('#caisse_id').html("<option value=''>-- Selectionner la caisse --</option>");
                    $.each(reponse.rows, function (index, caisse) { 
                        $('#caisse_id').append('<option value=' + caisse.id + '>' + caisse.libelle_caisse + '</option>')
                    });
                });
            }
            if(user_role!="Caissier"){
                $.getJSON("../taxe/find-caisse-by-id/" + caisse_id, function (reponse) {
                    $('#caisse_id').html("<option value=''>-- Selectionner la caisse --</option>");
                    $.each(reponse.rows, function (index, caisse) { 
                        $('#caisse_id').append('<option selected value=' + caisse.id + '>' + caisse.libelle_caisse + '</option>')
                    });
                });
            }
           $(".bs-modal-ouverture-caisse").modal("show");
        });
        
        $("#btnFermerCaisse").on("click", function () {
                $.getJSON("../taxe/get-caisse-ouverte/" + caisse_id, function (reponse) {
                    $.each(reponse.rows, function (index, caisse_ouverte) { 
                        var solde_fermeture = caisse_ouverte.montant_ouverture + caisse_ouverte.entree - caisse_ouverte.sortie;
                        $("#montant_ouverture").html($.number(caisse_ouverte.montant_ouverture));
                        $("#total_entree").html($.number(caisse_ouverte.entree));
                        $("#total_sortie").html($.number(caisse_ouverte.sortie));
                        $("#solde_caisse").html($.number(solde_fermeture));
                        $("#solde_fermeture_aff").html($.number(solde_fermeture));
                        $("#solde_fermeture").val(solde_fermeture); 
                    });
                }); 
            
           $(".bs-modal-fermeture-caisse").modal("show");
        });
        
        $("#quantite_billet").change(function (e) {
           var quantite_billet = $("#quantite_billet").val();
           var billet = $("#billet").val();
           if(billet!="" && quantite_billet!=""){
               var qte = parseInt(billet)*parseInt(quantite_billet);
               $("#montant_billet").val(qte);
           }else{
               $("#montant_billet").val("");
           }
        });
        $("#quantite_billet").keyup(function (e) {
           var quantite_billet = $("#quantite_billet").val();
           var billet = $("#billet").val();
           if(billet!="" && quantite_billet!=""){
               var qte = parseInt(billet)*parseInt(quantite_billet);
               $("#montant_billet").val(qte);
           }else{
               $("#montant_billet").val("");
           }
        });
        
        //Add billet row on table
        $(".add-billetage-row").click(function () {
            if($("#billet").val() != '' && $("#quantite_billet").val() != '' && $("#quantite_billet").val()!=0) {
                var billet = $("#billet").val();
                var quantite_billet = $("#quantite_billet").val();
               
                //Vérification Si la ligne existe déja dans le tableau
                var ligneBilletTrouver = _.findWhere(panierBillet, {billets: billet})
                if(ligneBilletTrouver!=null) {
                        //Si la ligne existe on recupere l'ancienne quantité et l'id de la ligne
                        oldQte = ligneBilletTrouver.quantite_billets;
                        idElementLigne = ligneBilletTrouver.id;
                       
                        //Si la somme des deux quantités depasse la quantité à ajouter en stock alors on block
                        var sommeDeuxQtes = parseInt(oldQte) + parseInt(quantite_billet);
                            //MAJ de la ligne
                            $tableBilletage.bootstrapTable('updateByUniqueId', {
                                id: idElementLigne,
                                row: {
                                    quantite_billet : sommeDeuxQtes,
                                    montant_billet: $.number(billet*sommeDeuxQtes),
                                }
                            });
                            ligneBilletTrouver.quantite_billets = sommeDeuxQtes;
                          
                            $("#quantite_billet").val("");
                            $("#billet").val("");
                            $("#montant_billet").val("");
                            return;
                    }
                    idTablleBillet++; 
                    $tableBilletage.bootstrapTable('insertRow',{
                        index: idTablleBillet,
                        row: {
                          id: idTablleBillet,
                          billet: billet,
                          quantite_billet: quantite_billet,
                          montant_billet: $.number(quantite_billet*billet)
                        }
                    })
                  
                    //Creation de l'article dans le tableau virtuel (panier)
                    var DataBillet= {'id':idTablleBillet, 'billets':billet, 'quantite_billets':quantite_billet};
                    panierBillet.push(DataBillet);
                 
                    $("#quantite_billet").val("");
                    $("#billet").val("");
                    $("#montant_billet").val("");
                    if(idTablleBillet>0){
                        $(".delete-billetage-row").show();
                    }else{
                        $(".delete-billetage-row").hide();
                    }
                
            }else{
                $.gritter.add({
                    title: "E-Civil",
                    text: "Les champs billet et quantité ne doivent pas être vides et la quantité minimum doit être 1.",
                    sticky: false,
                    image: basePath + "/assets/img/gritter/confirm.png",
                });
                return;
            }
        })
        // Find and remove selected table billet rows  
        $(".delete-billetage-row").click(function () {
           var selecteds = $tableBilletage.bootstrapTable('getSelections');
           var ids = $.map($tableBilletage.bootstrapTable('getSelections'), function (row) {
                        return row.id
                    })
                $tableBilletage.bootstrapTable('remove', {
                    field: 'id',
                    values: ids 
                })
              
                $.each(selecteds, function (index, value) { 
                    var ligneTrouver = _.findWhere(panierBillet, {id: value.id})
                    panierBillet = _.reject(panierBillet, function (article) {
                        return article.id == value.id;
                    });
                });
               
                if(panierBillet.length==0){
                    $(".delete-billetage-row").hide();
                    idTablleBillet = 0;
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
                var url = "{{route('taxe.payement-taxes.store')}}";
             }else{
                var id = $("#idPayementModifier").val();
                var methode = 'PUT';
                var url = 'payement-taxes/' + id;
             }
            editerPayementAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
        
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idPayementSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('payement-taxes/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
        
        $("#formOuvertureCaisse").submit(function(e){
            e.preventDefault();
            var $valid = $(this).valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            var $ajaxLoader = $("#formOuvertureCaisse .loader-overlay");
            var methode = 'POST';
            var url = "{{route('taxe.caisse-ouverte.store')}}";
            ouvertureCaisseAction(methode, url, $(this), $(this).serialize(), $ajaxLoader);
        });
        
        $("#formFermetureCaisse").submit(function(e){
            e.preventDefault();
            var $valid = $(this).valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
          
            $("#caisses_fermeture").val(caisse_id);
            var $ajaxLoader = $("#formFermetureCaisse .loader-overlay");
            var methode = 'POST';
            var url = "{{route('taxe.femeture-caisse')}}";
            var formData = new FormData($(this)[0]);
            createFormData(formData, 'panierBillet', panierBillet);
            fermetureCaisseAction(methode, url, $(this), formData, $ajaxLoader);
        });
    });
    
    function createFormData(formData, key, data) {
        if (data === Object(data) || Array.isArray(data)) {
            for (var i in data) {
                createFormData(formData, key + '[' + i + ']', data[i]);
            }
        } else {
            formData.append(key, data);
        }
    }
    
    function updateRow(idPayement) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var payement =_.findWhere(rows, {id: idPayement});
        $scope.$apply(function () {
            $scope.populateForm(payement);
        });
        
        $.getJSON("../taxe/get-contribuable-by-activite/" + payement.declaration_activite_id, function (reponse) {
            if(reponse.total>0){
                $.each(reponse.rows, function (index, contribuable) { 
                    $('#contribuable').select2("val", contribuable.id);
                });
                
                
            }
        });
        
        $.getJSON("../taxe/get-activite-by-id/" + payement.declaration_activite.id, function (reponse) {
            if(reponse.total>0){
                $("#declaration_activite_id").val('').trigger('change');
                $.each(reponse.rows, function (index, structure) { 
                    if(structure.id === payement.declaration_activite.id){
                       $('#declaration_activite_id').append("<option selected='selected' value=" + structure.id + ">" + structure.nom_structure + "</option>")
                    }
                       $('#montant').val(structure.montant_taxe);
                });
            }
        });
        
        $(".bs-modal-ajout").modal("show");
    }
    
    function deleteRow(idPayement) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var payement =_.findWhere(rows, {id: idPayement});
           $scope.$apply(function () {
              $scope.populateForm(payement);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function ticketPrintRow(idPayement){
        window.open("../taxe/facture-pdf/" + idPayement ,'_blank')
    }
    
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function tiketFormatter(id, row){
        return '<button type="button" class="btn btn-xs btn-info" data-placement="left" data-toggle="tooltip" title="Ticket" onClick="javascript:ticketPrintRow(' + row.id + ');"><i class="fa fa-file-pdf-o"></i></button>';
    }
    
    function editerPayementAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                        $("#contribuable, #declaration_activite_id").val('').trigger('change');
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

    function ouvertureCaisseAction(methode, url, $formObject, formData, $ajoutLoader) {
        jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        success:function (reponse, textStatus, xhr){
            if (reponse.code === 1) {
                //Si la caisse est ouverte on recharge la page
                location.reload();
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
    
    function fermetureCaisseAction(methode, url, $formObject, formData, $ajoutLoader) {
    jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        contentType: false,
        processData: false,
        success:function (reponse, textStatus, xhr){
            if (reponse.code === 1) {
                //Si la caisse est fermée on génère l'etat du billetage et on recharge la page
                 window.open("../taxe/billetage-pdf/" + reponse.data.id ,'_blank')
                location.reload();
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


