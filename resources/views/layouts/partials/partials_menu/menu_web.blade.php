<li class="{{Route::currentRouteName() === 'e-civil.demandes-recues' ? 'active treeview' : 'treeview'}}">
          <a href="#">
            <i class="fa fa-link"></i> <span>Site web</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Route::currentRouteName() === 'e-civil.demandes-recues'
                        ? 'active' : ''
                }}">
              <a href="{{route('e-civil.demandes-recues')}}">
                &nbsp;&nbsp;&nbsp;<i class="fa fa-book"></i> D&eacute;mandes re&ccedil;ues
              </a>
            </li>
</ul>
            