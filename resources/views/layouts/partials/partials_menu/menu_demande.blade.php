<li class="{{ Route::currentRouteName() === 'e-civil.demande-copie-acte-naissance' 
              || Route::currentRouteName() === 'e-civil.demande-copie-acte-mariage' 
              || Route::currentRouteName() === 'e-civil.demande-copie-acte-deces' 
              || Route::currentRouteName() === 'e-civil.inhumations.index' 
              ? 'active treeview' : 'treeview'}}">
          <a href="#">
              <i class="fa fa-bookmark-o "></i> <span>Demandes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.demande-copie-acte-naissance'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.demande-copie-acte-naissance')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-copy"></i> Demande de copie naissance
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.demande-copie-acte-mariage'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.demande-copie-acte-mariage')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-file-text"></i> Demande de copie mariage
              </a>
            </li>
             <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.demande-copie-acte-deces'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.demande-copie-acte-deces')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i> Demande copie de d&eacute;c&egrave;s
              </a>
            </li>
             <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.inhumations.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.inhumations.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-mail-forward"></i> Permis d'inhumation
              </a>
            </li>
          </ul>
