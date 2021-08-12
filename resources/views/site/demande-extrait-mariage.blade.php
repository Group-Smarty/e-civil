@extends('site.layout')
@section('content')
<main id="main">
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="/">Accueil</a></li>
          <li>D&eacute;mande</li>
        </ol>
        <h2>Certificat de mariage</h2>
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
                  <input type="hidden" name="type_demande" value="mariage">
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