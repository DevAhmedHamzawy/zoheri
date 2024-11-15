<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrapone.min.css') }}" integrity="sha384-Jt6Tol1A2P9JBesGeCxNrxkmRFSjWCBW1Af7CSQSKsfMVQCqnUVWhZzG0puJMCK6" crossorigin="anonymous">    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('panel/panel/css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">

    <script src="{{ asset('js/axios.min.js') }}" defer></script>

    <title>{{-- $settings->name --}} - لوحة التحكم</title>
    @yield('header')
</head>
<body>
  <nav class="navbar navbar-expand-lg fixed-top">

    <a class="navbar-brand" href="#"><img src="{{ asset('panel/panel/img/logo.png') }}" width="100" height="80" style="margin-right: 0.9em;" /></a>


    <button class="navbar-toggler sideMenuToggler" type="button" >
      <i class="fa fa-bars" style="color:#000; font-size:28px;"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto" >

        <li class="">

            <a class=" "  style="margin-top:1px;" id="navbarDropdownMenuLink"  aria-haspopup="true" aria-expanded="false">
               <span class="hidden-xs">{{ Auth::user()->user_name }}</span>

            </a>
            <!-- <div class="dropdown-menu" id="actions" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  تسجيل الخروج
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            </div> -->
        </li>

         <li class="nav-item ">
              <a class="dropdown-item" href="{{ route('logout') }}" style="margin-top:5px;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   <span class="fa fa-sign-out" style="color:#000;"></span>

                  تسجيل الخروج

              </a>

         </li>
          <li class="nav-item dropdown messages-menu" style="display: none;">
            <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" data-target="#notifications" aria-haspopup="true" aria-expanded="false">
              <i class="material-icons">notifications</i>
              <span class="label label-success bg-success">{{-- count($contact) --}}</span>
            </a>
            <div class="dropdown-menu" id="notifications" aria-labelledby="navbarDropdownMenuLink">
              <ul class="dropdown-menu-over list-unstyled">
                <li class="header-ul text-center">لديك {{-- count($contact) --}} رسائل جديدة</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu list-unstyled">
                  {{--  @foreach($contact as $c)
                    <li><!-- start message -->
                    <a href="#">
                      <div class="float-right">
                        <img src="http://via.placeholder.com/160x160" class="rounded-circle  " alt="User Image">
                      </div>
                      <h4>
                      {{ $c->name }}
                      <small><i class="fa fa-clock-o"></i>{{ $c->created_at }}</small>
                      </h4>
                      <p>{{ $c->text }}</p>
                    </a>
                  </li>
                  @endforeach --}}
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer-ul text-center"><a href="{{ url("../public/contacts") }}">مشاهدة كل الرسائل</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item dropdown notifications-menu" style='display: none'>
          <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" data-target="#messages" aria-haspopup="true" aria-expanded="false">
            <span class="material-icons">mail</span>
            <span class="label label-warning bg-warning">{{-- count($activity) --}}</span>
          </a>
          <div id="messages" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <ul class="dropdown-menu-over list-unstyled">
              <li class="header-ul text-center">لديك {{-- count($activity) --}} إشعارات جديدة</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu list-unstyled">
                  <li>
                   {{-- @foreach($activity as $a)
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> {{ $a->description }}
                    </a>
                    @endforeach --}}
                  </li>
                </ul>
              </li>
              <li class="footer-ul text-center"><a href="{{ url("../public/activitylog") }}">مشاهدة الكل</a></li>
            </ul>
          </div>
        </li>


      </ul>
    </div>
    <button class="navbar-toggler admin-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fa fa-bars" style="color:#000; font-size:28px;"></i>
    </button>



    <div class="user-menu-mobile nav-item text-left">

      <img src="{{ Auth::user()->image }}" class="user-image" >
      <span>{{ Auth::user()->user_name }}</span>
      |
      <a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa fa-sign-out" style="color:#000;"></i>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>

    </div>







</nav>

    <div class="wrapper d-flex">
        <div class="sideMenu bg-mattBlackLight" style="font-size: 15px;">
            <div class="sidebar">
                <ul class="navbar-nav">
                    @if(auth()->user()->roles[0]->active == 1)
                        <li class="nav-item">
                            <a href="#" data-toggle="collapse" data-target="#d_three" class="nav-link px-2 collapsed " > <i class="material-icons icon">assignment_ind</i> <span class="text"> إعدادات النظام  </span> <span class="the_arrow fa fa-chevron-left text-light"></span> </a>
                            <ul class="sub-menu collapse" id="d_three"  >
                            @can('view_role')
                                <li class="nav-item"><a href="{{ url('roles') }}" class="nav-link px-2"><span class="text text-white"> المناصب </span></a></li>
                            @endcan
                            @can('edit_code')
                                <li class="nav-item"><a href="{{ url('code/1/edit') }}" class="nav-link px-2"><span class="text text-white"> رمز تسجيل المنشاة </span></a></li>
                            @endcan
                            @can('view_branch')
                                <li class="nav-item"><a href="{{ url('branches') }}" class="nav-link px-2"><span class="text text-white"> الفروع </span></a></li>
                            @endcan
                            @can('view_hurdle')
                                <li class="nav-item"><a href="{{ url('hurdles') }}" class="nav-link px-2"><span class="text text-white"> الحواجز </span></a></li>
                            @endcan
                            @can('view_vehicle')
                                <li class="nav-item"><a href="{{ url('vehicles') }}" class="nav-link px-2"><span class="text text-white"> المركبات </span></a></li>
                            @endcan
                            @can('view_model')
                                <li class="nav-item"><a href="{{ url('models') }}" class="nav-link px-2"><span class="text text-white"> الطراز </span></a></li>
                            @endcan
                            @can('view_term')
                                <li class="nav-item"><a href="{{ url('terms') }}" class="nav-link px-2"><span class="text text-white"> بنود المصروفات </span></a></li>
                            @endcan
                            @can('view_discount')
                                <li class="nav-item"><a href="{{ url('discounts') }}" class="nav-link px-2"><span class="text text-white"> الخصومات </span></a></li>
                            @endcan
                            @can('edit_settings')
                                <li class="nav-item"><a href="{{ url('settings/1/edit') }}" class="nav-link px-2"><span class="text text-white"> الضريبة </span></a></li>
                            @endcan
                            </ul>
                        </li>
                        @can('view_user')
                            <li class="nav-item"><a href="{{ url('users') }}" class="nav-link px-2"><i class="material-icons icon">collections</i><span class="text">إدارة المستخدمين</span></a></li>
                        @endcan
                        @can('view_selling')
                            <li class="nav-item"><a href="{{ url('sellings') }}" class="nav-link px-2"><i class="material-icons icon">collections</i><span class="text">المبيعات</span></a></li>
                        @endcan
                        @can('view_product')
                            <li class="nav-item"><a href="{{ url('products') }}" class="nav-link px-2"><i class="material-icons icon">collections</i><span class="text">إدارة تسعير المنتجات</span></a></li>
                        @endcan
                        @can('view_expense')
                            <li class="nav-item"><a href="{{ url('expenses') }}" class="nav-link px-2"><i class="material-icons icon">collections</i><span class="text">إدارة المصروفات</span></a></li>
                        @endcan
                    @endif

                </ul>
            </div>
        </div>



    <div class="content">
        <main style="margin-top: 47px;">


          @if ($errors->any())
              @foreach ($errors->all() as $error)
                  <div class="alert alert-danger">{{$error}}</div>
              @endforeach
          @endif

            @yield('content')

        </main>
    </div>



</body>


<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="{{ asset('js/popper.min.js') }}" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('panel/panel/js/script.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
 <script>
  if($(".sideMenuToggler:first").css('display')!='none' ) {
        $('.wrapper').addClass('active');
    }
</script>


@yield('footer')
</html>
