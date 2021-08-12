@extends('site.layout')
@section('content')
<style type="text/css">
input[type=checkbox] {
         position: relative;
         cursor: pointer;
    }
    input[type=checkbox]:before {
         content: "";
         display: block;
         position: absolute;
         width: 16px;
         height: 16px;
         top: 0;
         left: 0;
         border: 2px solid #555555;
         border-radius: 3px;
         background-color: white;
}
    input[type=checkbox]:checked:after {
         content: "";
         display: block;
         width: 5px;
         height: 10px;
         border: solid black;
         border-width: 0 2px 2px 0;
         -webkit-transform: rotate(45deg);
         -ms-transform: rotate(45deg);
         transform: rotate(45deg);
         position: absolute;
         top: 2px;
         left: 6px;
}
</style>
<script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
<main id="main">
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="/">Accueil</a></li>
          <li>D&eacute;mande</li>
        </ol>
        <h2>Extrait de naissance</h2>
      </div>
    </section>

   <section class="contact section-bg">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Remplir le formulaire</h2>
        </div>
        <div class="row">
          <div class="col-lg-12">
            @if(session()->has('numero_demande'))
              <h4 style="color: red;">Conserver votre num&eacute;ro de d&eacute;mande pour voir la disponibilit&eacute; et le retrait de votre document : {{ session('numero_demande') }}</h4>
            @endif
          </div>
          <div class="col-lg-12">
            <form action="{{route('store-demande-en-ligne')}}" method="post" role="form" class="php-email-form">
              @csrf
              <div class="row">
                <div class="col-md-4 form-group">
                  <input type="hidden" name="type_demande" value="naissance">
                  <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" name="nom_demandeur" class="form-control" id="nom_demandeur" placeholder="Nom et prénom(s) du démandeur" required>
                  @error('nom_demandeur'){{ $message }} @enderror
                </div>
                <div class="col-md-3 form-group mt-3 mt-md-0">
                  <input type="text" class="form-control bfh-phone" data-format="(dd) dd-dd-dd-dd" name="contact_demandeur" id="contact_demandeur" placeholder="Contact du démandeur">
                  @error('contact_demandeur'){{ $message }} @enderror
                </div>
                <div class="col-md-3 form-group mt-3 mt-md-0">
                  <input type="text" class="form-control" name="numero_acte" id="numero_acte" placeholder="N° d'acte de naissance" required>
                  @error('numero_acte'){{ $message }} @enderror
                </div>
                <div class="col-md-2 form-group mt-3 mt-md-0">
                  <input type="number" min="1" class="form-control" name="nombre_copie" id="nombre_copie" placeholder="Nombre de copies" required>
                  @error('nombre_copie'){{ $message }} @enderror
                </div>
              </div>
              <div class="row">
                  <div class="col-md-6 form-group"> <br/>
                      <input type="checkbox" id="copie_integrale" name="copie_integrale">&nbsp;&nbsp; Cochez cette case si c'est une demande de copie int&eacute;grale
                  </div>
              </div>
              <div class="my-3">
                <div class="loading">Chargement</div>
                <div class="error-message"></div>
                <div class="sent-message">Demande envoyée !</div>
              </div>
              <div class="text-center"><button type="submit">Envoyer</button></div>
            </form>
          </div>

        </div>

      </div>
    </section>
</main>
@endsection