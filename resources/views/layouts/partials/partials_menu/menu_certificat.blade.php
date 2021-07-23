<li class="{{ Route::currentRouteName() === 'e-civil.certificat-vie.index' 
              || Route::currentRouteName() === 'e-civil.certificat-vie-entretien.index' 
              || Route::currentRouteName() === 'e-civil.certificat-non-remargiages.index' 
              || Route::currentRouteName() === 'e-civil.non-inscritption-registres-deces.index' 
              || Route::currentRouteName() === 'e-civil.certificat-infructueuses.index' 
              || Route::currentRouteName() === 'e-civil.certificat-concubinages.index' 
              || Route::currentRouteName() === 'e-civil.certificat-non-divorces.index' 
              || Route::currentRouteName() === 'e-civil.certificat-non-naissances.index' 
              || Route::currentRouteName() === 'e-civil.certificat-non-separation-corps.index' 
              || Route::currentRouteName() === 'e-civil.soit-transmis.index' 
              || Route::currentRouteName() === 'e-civil.certificat-celibats.index' 
              || Route::currentRouteName() === 'e-civil.certificat-celebrations.index' 
              ? 'active treeview' : 'treeview'}}">
          <a href="#">
            <i class="fa fa-bookmark "></i> <span>Certificats</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
         
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-vie.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-vie.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-heartbeat"></i> Certificat de vie
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-vie-entretien.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-vie-entretien.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-group"></i> Certificat de vie et d'entretien
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-non-remargiages.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-non-remargiages.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-mars-stroke-v"></i> Certificat de non r&eacute;mariage
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.non-inscritption-registres-deces.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.non-inscritption-registres-deces.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Certificat de non inscription
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-infructueuses.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-infructueuses.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-search-plus"></i> Certificat rech. infructueuses
              </a>
            </li>
             <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-concubinages.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-concubinages.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-cubes"></i> Certificat de concubinage
              </a>
            </li>
             <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-non-divorces.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-non-divorces.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> Certificat de non divorce
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-non-naissances.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-non-naissances.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-mars"></i> Certificat de non naissance
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-non-separation-corps.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-non-separation-corps.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-exclamation"></i> Certificat non s&eacute;paration corps
              </a>
            </li>
             <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.soit-transmis.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.soit-transmis.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-reply"></i> Soit transmis
              </a>
            </li>
             <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-celibats.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-celibats.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Certificat c&eacute;libat
              </a>
            </li>
             <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.certificat-celebrations.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.certificat-celebrations.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Certificat c&eacute;l&eacute;bration
              </a>
            </li>
          </ul>
