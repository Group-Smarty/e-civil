<li class="{{ request()->is('taxe/*') || Route::currentRouteName() === 'taxe.point-caisse' || request()->is('taxe/details-contribuables/*') ? 'active treeview' : 'treeview'}}">
          <a href="#">
            <i class="fa fa-google-wallet"></i> <span>Taxes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Route::currentRouteName() === 'taxe.type-taxes.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('taxe.type-taxes.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Type de taxe
              </a>
            </li>
            <li class="{{ Route::currentRouteName() === 'taxe.localites.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('taxe.localites.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-map-marker"></i> Localit&eacute;
              </a>
            </li>
<!--            <li class="{{ Route::currentRouteName() === 'taxe.timbres.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('taxe.timbres.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Timbre
              </a>
            </li>-->
            <li class="{{ Route::currentRouteName() === 'taxe.contribuables.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('taxe.contribuables.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-users"></i> Contribuables
              </a>
            </li>
            <li class="{{ Route::currentRouteName() === 'taxe.declaration-activites.index' || request()->is('taxe/details-contribuables/*')
                        ? 'active' : ''
                }}">
              <a href="{{route('taxe.declaration-activites.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-bookmark"></i> D&eacute;claration d'activit&eacute;s
              </a>
            </li>
             <li class="{{ Route::currentRouteName() === 'taxe.caisses.index'
                         ? 'active' : ''
                }}">
              <a href="{{route('taxe.caisses.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-fax"></i> Caisse
              </a>
            </li>
            <li class="{{ Route::currentRouteName() === 'taxe.payement-taxes.index'
                        || Route::currentRouteName() === 'taxe.point-caisse' ? 'active' : ''
                }}">
              <a href="{{route('taxe.payement-taxes.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-money"></i> Payement des Taxes
              </a>
            </li>
            <li class="{{ Route::currentRouteName() === 'taxe.billetages.index' ? 'active' : ''
                }}">
              <a href="{{route('taxe.billetages.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Historique des caisses
              </a>
            </li>
    </ul>
