<li class="{{ Route::currentRouteName() === 'e-civil.deces-par-lieu' 
              || Route::currentRouteName() === 'etat.etat-naissances' 
              || Route::currentRouteName() === 'etat.etat-deces' 
              || Route::currentRouteName() === 'etat.etat-mariages' 
              || Route::currentRouteName() === 'e-civil.deces-par-motif' 
              || Route::currentRouteName() === 'e-civil.deces-par-mois' 
              || Route::currentRouteName() === 'e-civil.naissance-by-mois' 
              || Route::currentRouteName() === 'e-civil.naissance-by-secteur' 
              || Route::currentRouteName() === 'e-civil.prochains-mariages' 
              || Route::currentRouteName() === 'e-civil.nouveaux-majeurs' 
                        ? 'active treeview' : 'treeview'}}">
          <a href="#">
            <i class="fa fa-list"></i> <span>Etat</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Route::currentRouteName() === 'etat.etat-naissances'
                        ? 'active' : ''
                }}">
              <a href="{{route('etat.etat-naissances')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> Naissances
              </a>
            </li>
             <li class="{{Route::currentRouteName() === 'e-civil.nouveaux-majeurs'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.nouveaux-majeurs')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> Nouveaux majeurs
              </a>
            </li>
            <li class="{{Route::currentRouteName() === 'e-civil.naissance-by-mois'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.naissance-by-mois')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> Naissances par mois
              </a>
            </li>
            <li class="{{Route::currentRouteName() === 'e-civil.naissance-by-secteur'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.naissance-by-secteur')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> Naissances par lieu
              </a>
            </li>
            <li class="{{Route::currentRouteName() === 'etat.etat-mariages'
                        ? 'active' : ''
                }}">
              <a href="{{route('etat.etat-mariages')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> Mariages
              </a>
            </li>
            <li class="{{Route::currentRouteName() === 'e-civil.prochains-mariages'
                        ? 'active' : ''
                }}">
                <a href="{{route('e-civil.prochains-mariages')}}">
                    &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> Mariages &agrave; venir
                </a>
            </li>
            <li class="{{Route::currentRouteName() === 'etat.etat-deces'
                        ? 'active' : ''
                }}">
              <a href="{{route('etat.etat-deces')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> D&eacute;c&egrave;s
              </a>
            </li>
            <li class="{{Route::currentRouteName() === 'e-civil.deces-par-lieu'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.deces-par-lieu')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> D&eacute;c&egrave;s par lieu
              </a>
            </li>
            <li class="{{Route::currentRouteName() === 'e-civil.deces-par-motif'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.deces-par-motif')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> D&eacute;c&egrave;s par motif
              </a>
            </li>
            <li class="{{Route::currentRouteName() === 'e-civil.deces-par-mois'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.deces-par-mois')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-circle"></i> D&eacute;c&egrave;s par mois
              </a>
            </li>
          </ul>
