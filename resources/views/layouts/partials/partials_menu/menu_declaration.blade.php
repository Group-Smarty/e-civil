<li class="{{ Route::currentRouteName() === 'e-civil.declarations.index' 
             || Route::currentRouteName() === 'e-civil.naissances.index' 
             || Route::currentRouteName() === 'e-civil.mariages.index' 
             || Route::currentRouteName() === 'e-civil.decedes.index' 
             ? 'active treeview' : 'treeview'}}">
          <a href="#">
              <i class="fa fa-edit "></i> <span>D&eacute;clarations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.naissances.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.naissances.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-book"></i> D&eacute;claration des naissances
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.mariages.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.mariages.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-venus-double"></i> D&eacute;claration des mariages
              </a>
            </li>
            <li class="{{ request()->is('/e-civil')
                        || Route::currentRouteName() === 'e-civil.decedes.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.decedes.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-times"></i> D&eacute;claration des d&eacute;c&egrave;s
              </a>
            </li>
       
<!--            <li class="{{ request()->is('/e-civil') 
                        || Route::currentRouteName() === 'e-civil.declarations.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.declarations.index')}}">
                  &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Toutes les d&eacute;clarations
              </a>
            </li>-->
          </ul>
