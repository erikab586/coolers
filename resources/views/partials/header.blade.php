 <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    @php
      $user = Auth::user();
    @endphp
    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/logo.jpg') }}" alt="">
        <span class="d-none d-lg-block"></span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <!--form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form-->
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('assets/img/usuario.jfif') }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->name }} {{ $user->apellidos }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
               <h6>{{ $user->name }} {{ $user->apellidos }}</h6>
               <span>{{ $user->rol->nombrerol }}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>
                  @if($user->rol->nombrerol === 'Administrador')
                      Todos los coolers
                  @elseif($user->coolers && $user->coolers->count())
                      {{ $user->coolers->pluck('nombrecooler')->join(', ') }}
                  @else
                      Sin cooler asignado
                  @endif
                </span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Necesitas ayuda?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
                <a class="dropdown-item d-flex align-items-center" href="#"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Salir</span>
                </a>

                <form id="logout-form" action="{{ route('usuario.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>


          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link " href="{{ route('dashboard') }}">
          <i class="bi bi-laptop"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @if (in_array($user->rol->nombrerol, ['Administrador']))
        <li class="nav-heading">Administración</li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Contratos</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('contrato.mostrar') }}">
                <i class="bi bi-circle"></i><span>Listado Contratos</span>
              </a>
            </li>
          </ul>
        </li><!-- End Tables Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('cobranza') }}">
            <i class="bi bi-cash-coin"></i>
            <span>Cobranza</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('camara.mostrar') }}">
            <i class="bi bi-door-open-fill"></i>
            <span>Cámaras</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('cooler.mostrar') }}">
            <i class="bi bi-columns-gap"></i>
            <span>Sucursales</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('comercializadora.mostrar') }}">
            <i class="bi bi-shop"></i>
            <span>Comercializadoras</span>
          </a>
        </li><!-- End Profile Page Nav -->
          <li class="nav-heading">Operatividad</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('contrato.recepcionar') }}">
            <i class="bi bi-receipt"></i>
            <span>Recepciones</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('tarima.mostrar') }}">
            <i class="bi bi-border-width"></i>
            <span>Tarimas</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('enfrio.mostrar') }}">
            <i class="bi bi-thermometer-snow"></i>
            <span>Pre-Enfriado</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('conservacion.mostrar') }}">
            <i class="bi bi-thermometer-low"></i>
            <span>Conservación</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('cruce_anden.mostrar') }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>Cruce de Andén</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('embarcacion.mostrar') }}">
            <i class="bi bi-truck"></i>
            <span>Embarcación</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-heading">Configuración</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('fruta.mostrar') }}">
            <i class="ri-apple-fill"></i>
            <span>Frutas</span>
          </a>
        </li><!-- End Profile Page Nav -->
      
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('tipopallets.mostrar') }}">
            <i class="bi bi-inboxes-fill"></i>
            <span>Tipo Empaque</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('presentacion.mostrar') }}">
            <i class="bi bi-union"></i>
            <span>Presentaciones</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('variedad.mostrar') }}">
            <i class="bi bi-card-list"></i>
            <span>Variedades</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('rolusuario.mostrar') }}">
            <i class="bi bi-people-fill"></i>
            <span>Rol de Usuarios</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('usuario.mostrar') }}">
            <i class="bi bi-person-fill"></i>
            <span>Usuarios</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('permisos.index') }}">
            <i class="bi bi-shield-lock"></i>
            <span>Permisos</span>
          </a>
        </li>
        <li class="nav-heading">Configuración de sistema</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="users-profile.html">
            <i class="bi bi-gear"></i>
            <span>Sistema</span>
          </a>
        </li><!-- End Profile Page Nav -->
       
      
      @endif 
      @if (in_array($user->rol->nombrerol, ['Operativo']))
        <li class="nav-heading">Operatividad</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('contrato.recepcionar') }}">
            <i class="bi bi-receipt"></i>
            <span>Recepciones</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('tarima.mostrar') }}">
            <i class="bi bi-border-width"></i>
            <span>Tarimas</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('enfrio.mostrar') }}">
            <i class="bi bi-thermometer-snow"></i>
            <span>Pre-Enfriado</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('conservacion.mostrar') }}">
            <i class="bi bi-thermometer-low"></i>
            <span>Conservación</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('cruce_anden.mostrar') }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>Cruce de Andén</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('embarcacion.mostrar') }}">
            <i class="bi bi-truck"></i>
            <span>Embarcación</span>
          </a>
        </li><!-- End Profile Page Nav -->
      @endif
       @if (in_array($user->rol->nombrerol, ['Supervisor']))
        <li class="nav-heading">Administración</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('camara.mostrar') }}">
            <i class="bi bi-door-open-fill"></i>
            <span>Cámaras</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('cooler.mostrar') }}">
            <i class="bi bi-columns-gap"></i>
            <span>Sucursales</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('comercializadora.mostrar') }}">
            <i class="bi bi-shop"></i>
            <span>Comercializadoras</span>
          </a>
        </li><!-- End Profile Page Nav -->
          <li class="nav-heading">Operatividad</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('contrato.recepcionar') }}">
            <i class="bi bi-receipt"></i>
            <span>Recepciones</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('tarima.mostrar') }}">
            <i class="bi bi-border-width"></i>
            <span>Tarimas</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('enfrio.mostrar') }}">
            <i class="bi bi-thermometer-snow"></i>
            <span>Pre-Enfriado</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('conservacion.mostrar') }}">
            <i class="bi bi-thermometer-low"></i>
            <span>Conservación</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('cruce_anden.mostrar') }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>Cruce de Andén</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('embarcacion.mostrar') }}">
            <i class="bi bi-truck"></i>
            <span>Embarcación</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-heading">Configuración</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('fruta.mostrar') }}">
            <i class="ri-apple-fill"></i>
            <span>Frutas</span>
          </a>
        </li><!-- End Profile Page Nav -->
      
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('tipopallets.mostrar') }}">
            <i class="bi bi-inboxes-fill"></i>
            <span>Tipo Empaque</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('presentacion.mostrar') }}">
            <i class="bi bi-union"></i>
            <span>Presentaciones</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('variedad.mostrar') }}">
            <i class="bi bi-card-list"></i>
            <span>Variedades</span>
          </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('rolusuario.mostrar') }}">
            <i class="bi bi-people-fill"></i>
            <span>Rol de Usuarios</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('usuario.mostrar') }}">
            <i class="bi bi-person-fill"></i>
            <span>Usuarios</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('permisos.index') }}">
            <i class="bi bi-shield-lock"></i>
            <span>Permisos</span>
          </a>
        </li>
        <li class="nav-heading">Configuración de sistema</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="users-profile.html">
            <i class="bi bi-gear"></i>
            <span>Sistema</span>
          </a>
        </li><!-- End Profile Page Nav -->
       
      
      @endif 
    </ul>

  </aside><!-- End Sidebar-->
