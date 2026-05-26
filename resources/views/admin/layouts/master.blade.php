<!doctype html>
<html lang="ar" dir="rtl">

<head>
    @include('admin/layouts/head')
    @include('admin/layouts/css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="app sidebar-mini {{ session('sidebarCollapsed') ? 'sidenav-toggled' : '' }}">

    <!-- Start Switcher -->
    {{-- @include('admin/layouts/switcher') --}}
    <!-- End Switcher -->

    <!-- GLOBAL-LOADER -->
    @include('admin/layouts/loader')
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">
            <!--APP-SIDEBAR-->
            @include('admin/layouts/main-sidebar')
            <!--/APP-SIDEBAR-->

            <!-- Header -->
            @include('admin/layouts/main-header')

            <div class="home-content d-flex justify-content-between"
                 style="position: initial; top: 0px; z-index: 100; background:#E4E9F7;">
                <div>
                    <i class='fa fa-bars fa-xl side_main_button mx-3' style="cursor: pointer; font-size: 35px;"></i>
                    <span class="text">{{ setting('appName_'.lang()) }}</span>
                </div>
            </div>

            <!-- Header -->
            <!--Content-area open -->
            <div class="app-content">
                <div class="side-app">

                    <!-- PAGE-HEADER -->
                    <div class="page-header m-0">
                        <div>
                            @if (Route::currentRouteName() == 'dashboard.adminHome')
                                <h1 class="page-title" id="pageTitle11" style="font-size: 28px;">
                                    {{ trns('welcome_back') }} {{ Auth::guard('admin')->user()->name }}
                                </h1>
                            @endif
                            @if (Route::currentRouteName() != 'dashboard.adminHome')
                                <ol class="breadcrumb" style="font-size: 18px;">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.admin.adminHome') }}"
                                            style="color: #0478ed;">{{ trns('home') }}</a></li>
                                    <li class="mr-1 ml-1" style="color:#606d94;"> / </li>
                                    <li class="breadcrumb-item"><a href="#">@yield('page_name')</a></li>
                                </ol>
                            @endif
                        </div>
                    </div>
                    <!-- PAGE-HEADER END -->
                    @yield('content')
                </div>
                <!-- End Page -->
            </div>
            <!-- CONTAINER END -->
        </div>
        <!-- SIDE-BAR -->

        <!-- FOOTER -->
        @include('admin/layouts/footer')
        <!-- FOOTER END -->
    </div>
    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up mt-4"></i></a>

    @include('admin/layouts/scripts')
    @include('admin/includesJs.crudOperations')
    @yield('ajaxCalls')
    @stack('scripts')
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const darkModeToggle = '{{ session()->get('system_theme') }}';

        //     console.log(darkModeToggle);
        //     // Function to toggle dark mode
        //     function toggleDarkMode() {
        //         document.body.classList.toggle('dark-mode');
        //         document.body.classList.toggle('dark-menu');

        //         // Update the stored value in localStorage
        //         if (document.body.classList.contains('dark-mode')) {
        //             localStorage.setItem('darkMode', 'enabled');
        //         } else {
        //             localStorage.removeItem('darkMode');
        //         }
        //     }

        //     // Event listener for the button
        //     darkModeToggle.addEventListener('click', toggleDarkMode);

        //     // Check localStorage for dark mode setting on page load
        //     if (localStorage.getItem('darkMode') === 'enabled') {
        //         document.body.classList.add('dark-mode');
        //         document.body.classList.add('dark-menu');
        //         $('.Global-Loader').addClass('darkmode');
        //     }
        // });

        document.addEventListener("DOMContentLoaded", function() {
            // Get the current theme from session (set by PHP)
            const currentTheme = '{{ session('system_theme', 'light') }}';

            // Apply the theme on page load
            if (currentTheme === 'dark') {
                document.body.classList.add('dark-mode', 'dark-menu');
                document.querySelector('.Global-Loader')?.classList.add('darkmode');
            } else {
                document.body.classList.remove('dark-mode', 'dark-menu');
                document.querySelector('.Global-Loader')?.classList.remove('darkmode');
            }
        });



        // Function to set the page title
        $(document).ready(function() {
            setTimeout(e => {
                var pageTitle = $('#pageTitle11');
                pageTitle.addClass('d-none');
            }, 5000);
        });
    </script>

    <!--  CHECK-ALL AND UNCHECK-ALL -->
    <script>
        function checkAll(name, button) {
            const form = button.closest('form');
            form.querySelectorAll(`input[name="${name}[]"]`).forEach(cb => cb.checked = true);
        }

        function uncheckAll(name, button) {
            const form = button.closest('form');
            form.querySelectorAll(`input[name="${name}[]"]`).forEach(cb => cb.checked = false);
        }

        $(document).on('submit', '.modal form', function (e) {
            e.preventDefault();
        });
    </script>

  
    <!-- Start datatable setting -->
    <script>
        $.extend(true, $.fn.dataTable.defaults, {
            dom: 'Blfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            lengthMenu: [
                [10, 20, 50, 100, -1],
                ['10', '20', '50', '100', 'الكل']
            ],
            pageLength: 50,
            language: {
                lengthMenu: "{{ trns('show') }} _MENU_ {{ trns('entries') }}",
                search: "{{ trns('search') }}:",
                info: "{{ trns('showing') }} _START_ {{ trns('to') }} _END_ {{ trns('of') }} _TOTAL_ {{ trns('entries') }}",
                infoEmpty: "{{ trns('no_entries') }}",
                zeroRecords: "{{ trns('no_matching_records_found') }}",
                paginate: {
                    first: "{{ trns('first') }}",
                    last: "{{ trns('last') }}",
                    next: "{{ trns('next') }}",
                    previous: "{{ trns('previous') }}"
                },
                emptyTable: "{{ trns('no_data_available_in_table') }}"
            }
        });

    </script>
    <!-- Start datatable setting -->

    
    <!-- Start Filter Company -->
    <script>
        $('#filter_company_input').on('change', function () {
            let companyId = $(this).val();

            $.ajax({
                url: '/dashboard/companies/set-active/' + companyId, // تأكد من صحة المسار
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // CSRF Token مهم
                },
                success: function (response) {
                    if (response.success) {
                        // خزّن رسالة النجاح مؤقتاً
                        localStorage.setItem('companyActivated', 'true');
                        // ثم أعد تحميل الصفحة
                        location.reload(); // reload current page to reflect active company
                    } else {
                        // رسالة عند محاولة تفعيل شركة مفعلة مسبقاً
                        toastr.info("{{ trns('already_active') }}");
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastr.error("{{ trns('error_occurred') }}");
                }
            });
        });

        $(document).ready(function () {
            if (localStorage.getItem('companyActivated')) {
                toastr.success("{{ trns('activated_successfully') }}");
                localStorage.removeItem('companyActivated');
            }
        });
    </script>
    <!-- End Filter Company -->

{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="{{ asset('assets/css/sweetalert2.min.js') }}"></script>

    @if(session('success') || session('error') || session('warning'))
    <script>
        Swal.fire({
            icon: '{{ session('success') ? 'success' : (session('error') ? 'error' : 'warning') }}',
            text: '{{ session('success') ?? session('error') ?? session('warning') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif

    @yield('js')


</body>

</html>
