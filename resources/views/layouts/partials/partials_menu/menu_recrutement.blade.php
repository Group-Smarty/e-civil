<li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'recrutement.agents.index' 
                        || Route::currentRouteName() === 'recrutement.contrats.index' 
                        ? 'active treeview' : 'treeview'}}">
          <a href="#">
            <i class="fa fa-newspaper-o"></i> <span>Recrutement</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'recrutement.agents.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('recrutement.agents.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-users"></i> Agents
              </a>
            </li>
           
            <li class="{{ request()->is('/recrutement')
                        || Route::currentRouteName() === 'recrutement.contrats.index'
                        ? 'active' : ''
                }}">
              <a href="{{route('recrutement.contrats.index')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase"></i> Contrat de travail
              </a>
            </li>
           
          </ul>
