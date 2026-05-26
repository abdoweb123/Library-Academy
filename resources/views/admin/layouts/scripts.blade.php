
<!-- jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
<script src="{{ asset('assets/css/jquery-3.5.1.min.js') }}"></script>

<!-- Bootstrap JS -->
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ asset('assets/css/stackpath_bootstrap.bundle.min.js') }}"></script>

{{-- datatables  --}}
<!-- JS -->
{{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> --}}
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

{{-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script> --}}
<script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>

{{-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script> --}}
<script src="{{ asset('assets/js/buttons.html5.min.js') }}"></script>

{{-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script> --}}
<script src="{{ asset('assets/js/buttons.print.min.js') }}"></script>

{{-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script> --}}
<script src="{{ asset('assets/js/buttons.bootstrap4.min.js') }}"></script>


<!-- Excel -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> --}}
<script src="{{ asset('assets/js/jszip.min.js') }}"></script>

<!-- PDF -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script> --}}
<script src="{{ asset('assets/js/pdfmake.min.js') }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> --}}
<script src="{{ asset('assets/js/vfs_fonts.js') }}"></script>


<!-- Buttons HTML5 export -->
{{-- <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script> --}}
<script src="{{ asset('assets/js/buttons2.html5.min.js') }}"></script>

<!-- Buttons print -->
{{-- <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script> --}}
<script src="{{ asset('assets/js/buttons2.print.min.js') }}"></script>

<!-- BOOTSTRAP JS -->
{{-- <script src="{{asset('assets/admin')}}/assets/plugins/bootstrap5/js/bootstrap.bundle.min.js"></script> --}}
{{-- <script src="{{asset('assets/admin')}}/assets/plugins/bootstrap5/js/popper.min.js"></script> --}}

<!-- SPARKLINE JS-->
<script src="{{ asset('assets/admin') }}/assets/js/jquery.sparkline.min.js"></script>

<!-- CHART-CIRCLE JS-->
<script src="{{ asset('assets/admin') }}/assets/js/circle-progress.min.js"></script>

<!-- RATING STARJS -->
<script src="{{ asset('assets/admin') }}/assets/plugins/rating/jquery.rating-stars.js"></script>

<!-- EVA-ICONS JS -->
<script src="{{ asset('assets/admin') }}/assets/iconfonts/eva.min.js"></script>

<!-- INPUT MASK JS-->
<script src="{{ asset('assets/admin') }}/assets/plugins/input-mask/jquery.mask.min.js"></script>

<!-- SIDE-MENU JS-->
<script src="{{ asset('assets/admin') }}/assets/plugins/sidemenu/sidemenu.js"></script>

{{-- <!-- PERFECT SCROLL BAR js--> --}}
{{--<script src="{{ asset('assets/admin') }}/assets/plugins/p-scroll/perfect-scrollbar.min.js"></script>--}}
<script src="{{ asset('assets/admin') }}/assets/plugins/sidemenu/sidemenu-scroll-rtl.js"></script>

<!-- CUSTOM SCROLLBAR JS-->
{{--<script src="{{ asset('assets/admin') }}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>--}}

<!-- SIDEBAR JS -->
<script src="{{ asset('assets/admin') }}/assets/plugins/sidebar/sidebar-rtl.js"></script>

<!-- CUSTOM JS -->
<script src="{{ asset('assets/admin') }}/assets/js/custom.js"></script>

<!-- Switcher JS -->
<script src="{{ asset('assets/admin') }}/assets/switcher/js/switcher-rtl.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script> --}}
<script src="{{ asset('assets/dropify/js/dropify.min.js') }}"></script>

<script src="{{ asset('assets/admin/assets/js/select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%', // Optional: adjust width to match Bootstrap
            theme: 'bootstrap4' // Optional: for Bootstrap theme, if you're using it
        });
    });

    // $(document).on('click', 'body', function () {
    //     // تهيئة Select2 للمحتوى الجديد
    //     $('.select2').select2({
    //         width: '100%', // Optional: adjust width to match Bootstrap
    //         theme: 'bootstrap4' // Optional: for Bootstrap theme, if you're using it
    //     });
    // });
</script>

{{--<script src="{{ asset('assets/website/js/all.min.js') }}"></script>--}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.4/dist/sweetalert2.all.min.js"></script> --}}
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

{{--<script src="{{ asset('assets/fileUpload/fileUpload.js') }}"></script>--}}

<script type="text/javascript" src="{{ asset('assets/uploadjs/image-uploader.min.js') }}"></script>

{{-- toastr --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>


<script>
    $(document).ready(function () {
        $('.dropify').dropify();
    });
</script>

<script>
    window.addEventListener('online', () => {
        // window.location.reload();
        toastr.success("{{ trns('Internet connection has been restored.') }}");
    });
    window.addEventListener('offline', () => {
        toastr.error("{{ trns('Disconnected, please check your internet quality') }}");
    });
</script>

@yield('js')

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script> --}}
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>

<script>
    $(document).on('click', '.image-popup', function (e) {
        e.preventDefault(); // Prevent the default action
        $(this).magnificPopup({
            items: {
                src: $(this).attr('href') // Ensure this pulls the correct URL
            },
            type: 'image',
            closeOnContentClick: true,
            image: {
                verticalFit: true
            }
        }).magnificPopup('open');
    });
</script>
<script type="text/javascript" src="{{asset('richtexteditor')}}/rte.js"></script>
<script type="text/javascript" src='{{asset('richtexteditor')}}/plugins/all_plugins.js'></script>


{{-- Bootstrap Toggle checkbox --}}
    <!-- Bootstrap Toggle CSS -->
    {{-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> --}}
    <link href="{{ asset('assets/css/bootstrap-toggle.min.css') }}" rel="stylesheet"/>

    <!-- Bootstrap Toggle JS -->
    {{-- <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script> --}}
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').on('draw.dt', function() {
                $('input[data-toggle="toggle"]').bootstrapToggle();
            });
        })
    </script>
{{-- Bootstrap Toggle checkbox --}}


{{-- Sidebar Management --}}
<script>
    $(document).ready(function() {
        let toggleBtn = $("#toggleSidebar");

        toggleBtn.click(function() {
            $.post("{{ route('toggle.sidebar') }}", {
                _token: "{{ csrf_token() }}"
            }, function(response) {
                if (response.status === "ok") {
                    console.log(response.message);
                }
            });
        });
    });
</script>


{{-- SweetAlert2 for success and error messages --}}

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500, // ← يغلق بعد 2 ثانية
            timerProgressBar: true
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
@endif
