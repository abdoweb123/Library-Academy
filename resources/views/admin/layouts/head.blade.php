<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- FAVICON -->
<link rel="shortcut icon" type="image/x-icon"
      href="{{ asset(isset($setting->where('key','logo')->first()->value) ? $setting->where('key','logo')->first()->value : null)}}"/>

<!-- TITLE -->
<title>
    {{ setting('app_name_' . lang()) }}
    @if (Route::is('dashboard.admin.adminHome'))
        | {{ trns('dashboard') }}
    @else
        | @yield('title')
    @endif
</title>


<link rel="icon" type="image/x-icon" href="{{asset(setting('logo'))}}">

<!-- BOOTSTRAP CSS -->

@if(lang() == 'ar')
    <!-- STYLE CSS *** remove rtl to switch *** -->
    <link href="{{asset('assets/admin/assets/css-rtl/style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css-rtl/skin-modes.css" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css-rtl/dark-style.css" rel="stylesheet"/>
@else
    <!-- STYLE CSS *** remove rtl to switch *** -->
    <link href="{{asset('assets/admin/assets/css/style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css/skin-modes.css" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css/dark-style.css" rel="stylesheet"/>
@endif

@if(lang() == 'ar')
    {{--    <!-- SIDE-MENU CSS *** remove rtl to switch *** -->--}}
    <link href="{{asset('assets/admin')}}/assets/css-rtl/sidemenu.css" rel="stylesheet">
@else
    <link href="{{asset('assets/admin')}}/assets/css/sidemenu.css" rel="stylesheet">

@endif
<!--PERFECT SCROLL CSS-->
{{--<link href="{{asset('assets/admin')}}/assets/plugins/p-scroll/perfect-scrollbar.css" rel="stylesheet"/>--}}

<!-- CUSTOM SCROLL BAR CSS-->
{{--<link href="{{asset('assets/admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet"/>--}}

<!--- FONT-ICONS CSS -->
<link href="{{asset('assets/admin/assets/css/icons.css')}}" rel="stylesheet"/>

<!-- SIDEBAR CSS -->
<link href="{{asset('assets/admin')}}/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

<!-- COLOR SKIN CSS -->
<link id="theme" rel="stylesheet" type="text/css" media="all"
      href="{{asset('assets/admin')}}/assets/colors/color1.css"/>

{{--  ckeditor  --}}
<link rel="stylesheet" href="{{ asset('assets/dropify/css/dropify.min.css')}}">


{{--  ckeditor  --}}

<!-- Switcher CSS -->
<link href="{{asset('assets/admin')}}/assets/switcher/css/switcher-rtl.css" rel="stylesheet">
<link href="{{asset('assets/admin')}}/assets/switcher/demo.css" rel="stylesheet">

<script defer src="{{asset('assets/admin')}}/assets/iconfonts/font-awesome/js/brands.js"></script>
<script defer src="{{asset('assets/admin')}}/assets/iconfonts/font-awesome/js/solid.js"></script>
<script defer src="{{asset('assets/admin')}}/assets/iconfonts/font-awesome/js/fontawesome.js"></script>
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet"/> --}}
<script defer src="{{asset('assets/css/dropify.min.css')}}"></script>

<link href="{{ asset('assets/admin/assets/css/select2.min.css') }}" rel="stylesheet"/>

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.4/dist/sweetalert2.min.css"> --}}
<script defer src="{{asset('assets/css/sweetalert2.min.css')}}"></script>

{{-- toastr --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
      integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"/> --}}
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}"/>

      
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
<script src="{{ asset('assets/css/toastr.min.js') }}"></script>

@yield('css')

<link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">


<!-- Magnific Popup CSS -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css"/> --}}
<link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}"/>

<link rel="stylesheet" href="{{asset('richtexteditor')}}/rte_theme_default.css" />

<!-- jQuery أولاً -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="{{ asset('assets/css/jquery-3.6.0.min.js') }}"></script>

<!-- ثم Bootstrap -->
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ asset('assets/css/bootstrap.bundle.min.js') }}"></script>

<!-- CSS -->
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}"/>

{{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/css/buttons.dataTables.min.css') }}"/>