<li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'courrier.annuaires.index' 
                        || Route::currentRouteName() === 'courrier.courriers.index' 
                        || Route::currentRouteName() === 'courrier.courriers-emis' 
                        || Route::currentRouteName() === 'courrier.courriers-recus' 
                        ? 'active treeview' : 'treeview'}}">
          <a href="#">
            <i class="fa fa-envelope"></i> <span>Courrier</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'courrier.annuaires.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('courrier.annuaires.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-book"></i> Annuaires
              </a>
            </li>
            <li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'courrier.courriers-emis'
                        ? 'active' : ''
                }}">
              <a href="{{route('courrier.courriers-emis')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-mail-reply"></i> Courrier sortant
              </a>
            </li>
            <li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'courrier.courriers-recus'
                        ? 'active' : ''
                }}">
              <a href="{{route('courrier.courriers-recus')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-mail-forward"></i> Courrier entrant
              </a>
            </li>
            <li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'courrier.courriers.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('courrier.courriers.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-inbox"></i> Tous les courriers
              </a>
            </li>
          </ul>
