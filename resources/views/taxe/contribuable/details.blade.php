@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Taxe')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#activite_info" data-toggle="tab" aria-expanded="true">Liste des activit&eacute;s d&eacute;clar&eacute;es</a>
        </li>
        <li class="">
            <a href="#payement_taxe_info" data-toggle="tab" aria-expanded="true">Liste des payements de taxe</a>
        </li>
    </ul> 
    <div class="tab-content">  
        <div class="tab-pane active" id="activite_info">
                    <div class="box-header">
                        <div class="col-md-12">
                            <h3 class="box-title pull-left">Liste des activit&eacute;s d&eacute;clar&eacute;es</h3>
                        </div>
                    </div>
                    <div class="box-body">
                <table id="tableActivite" class="table table-warning table-striped box box-warning"
                       data-pagination="true"
                       data-search="false" 
                       data-toggle="table"
                       data-unique-id="id"
                       data-show-toggle="false"
                       data-show-columns="true">
                    <thead>
                        <tr>
                            <th data-field="date_declarations">Date d&eacute;cl. </th>
                            <th data-field="nom_structure">Structure </th>
                            <th data-field="type_societe.libelle_type_societe">Type soci&eacute;t&eacute;</th>
                            <th data-field="nom_activite">Activit&eacute; </th>
                            <th data-field="secteur.libelle_secteur">Secteur d'activit&eacute; </th>
                            <th data-field="contact">Contact</th>
                            <th data-field="localite.libelle_localite">Localit&eacute;</th>
                            <th data-field="situation_geographique" data-visible="false">Adresse geo.</th>
                            <th data-field="type_taxe.libelle_type_taxe">Type taxe</th>
                            <th data-field="contribuable.nom_complet">Contribuable</th>
                            <th data-field="numero_cc" data-visible="false">N° CC </th>
                            <th data-field="numero_registre" data-visible="false">N° registre </th>
                            <th data-field="longitude" data-visible="false">Longitude</th>
                            <th data-field="latitude" data-visible="false">Latitude</th>
                            <th data-field="adresse_postale" data-visible="false">Adresse postale</th>
                            <th data-field="email" data-visible="false">E-mail</th>
                        </tr>
                    </thead>
                </table>
            </div>              
        </div>
        <div class="tab-pane" id="payement_taxe_info">
            <div class="box-header">
                <div class="col-md-12">
                    <h3 class="box-title pull-left">Liste des payements de taxe</h3>
                </div>
            </div>
            <div class="box-body">
                <table id="tablePayement" class="table table-warning table-striped box box-warning"
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
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

<input type="hidden" id="contribuable" value="{{$contribuable->id}}"/>
<script type="text/javascript">
    var $tableActivite = jQuery("#tableActivite"), $tablePayement = jQuery("#tablePayement");
    var contribuable = $("#contribuable").val();
    
    $(function () {
        $tableActivite.bootstrapTable('refreshOptions', {url: '../liste-activites-by-contribuable/' + contribuable});
        $tablePayement.bootstrapTable('refreshOptions', {url: '../gett-all-payements-taxes/' + contribuable});
   
    });
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


