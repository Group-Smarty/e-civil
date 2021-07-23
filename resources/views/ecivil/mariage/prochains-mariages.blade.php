@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur' or Auth::user()->service == 'ETAT CIVIL')
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
<div class="row">
    <div class="col-md-3">
        <label>Voir pour un mois</label>
        <select id="mois" class="form-control">
            <option value="0">-- Tous les prochains mariages --</option>
            @foreach($moisFr as $mois)
            <option value="{{$mois}}">{{$mois}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-9"><br/>
        <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
    </div>
</div><br/>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-prochains-mariages'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_mariages">Date</th>
            <th data-field="nom_complet_homme">Epoux</th>
            <th data-field="nom_complet_femme">Epouse</th>
            <th data-field="contact_declarant">Contact</th>
        </tr>
    </thead>
</table>


<script type="text/javascript">
    var $table = jQuery("#table"), rows = [];
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $("#mois").change(function (e) {
            var mois = $("#mois").val();
            if(mois == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-prochains-mariages'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-prochains-mariages-par-mois/' + mois});
            }
        });
    
    });

    function imprimePdf(){
        var mois = $("#mois").val();
        if(mois==0){
            window.open("../e-civil/fiche-prochains-mariages/" ,'_blank');
        }else{
            window.open("../e-civil/fiche-prochains-mariages-par-mois/" + mois ,'_blank');  
        }
    }

</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


