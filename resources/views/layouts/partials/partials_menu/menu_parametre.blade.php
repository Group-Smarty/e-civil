<li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.fonctions.index' 
                        || Route::currentRouteName() === 'parametre.type-contrats.index' 
                        || Route::currentRouteName() === 'parametre.services.index' 
                        || Route::currentRouteName() === 'parametre.communes.index' 
                        || Route::currentRouteName() === 'parametre.type-pieces.index' 
                        || Route::currentRouteName() === 'parametre.mode-travails.index' 
                        || Route::currentRouteName() === 'parametre.nations.index' 
                        || Route::currentRouteName() === 'parametre.regimes.index' 
                        || Route::currentRouteName() === 'parametre.type-courriers.index' 
                        || Route::currentRouteName() === 'parametre.type-societes.index' 
                        || Route::currentRouteName() === 'parametre.secteurs.index' 
                        ? 'active treeview' : 'treeview'}}">
          <a href="#">
              <i class="fa fa-cogs"></i> <span>Param&egrave;tre</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.fonctions.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.fonctions.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-circle-o"></i> Fonction
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.type-contrats.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.type-contrats.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-houzz"></i> Type de contrat
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.services.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.services.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-server"></i> Service
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.communes.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.communes.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-map"></i> Commune
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.type-pieces.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.type-pieces.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-bars"></i> Type de pi&egrave;ce
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.mode-travails.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.mode-travails.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-crop"></i> Mode de travail
              </a>
            </li>
             <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.nations.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.nations.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-flag"></i> Pays
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.regimes.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.regimes.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> R&eacute;gime
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.type-courriers.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.type-courriers.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-circle-o"></i> Type de courrier
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.type-societes.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.type-societes.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-list-ol"></i> Type de soci&eacute;t&eacute;
              </a>
            </li>
            <li class="{{ request()->is('/parametre')
                        || Route::currentRouteName() === 'parametre.secteurs.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('parametre.secteurs.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-institution"></i> Secteurs d'activit&eacute;s
              </a>
            </li>
          </ul>
