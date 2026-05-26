<script>
    // To toggle password
    function togglePasswordVisibility(inputName) {
        const inputs = document.querySelectorAll(`input[name="${inputName}"]`);

        inputs.forEach(input => {
            const icon = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    // To preview Image
    function previewImage(input) {
        var $imagePreview = $(input).closest('.modal-body').find('.imagePreview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $imagePreview.attr('src', e.target.result);
                $imagePreview.css('display', 'inline-block');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // remove arrow from select2 in show modals
    $(document).on('shown.bs.modal', function (e) {
        var modal = $(e.target);
        var id = modal.attr('id');

        if (id && id.startsWith('showModal_')) {
            modal.find('select').css({
                'appearance': 'none',
                '-webkit-appearance': 'none',
                '-moz-appearance': 'none',
                'background-image': 'none'
            });
        }
    });
</script>


<!-- Crud dataTable -->
<script>
    $(document).on('draw.dt', function () {
        // نقل المودالات من داخل الجدول إلى الحاوية الخارجية
        $('table .modal').each(function () {
            $('#modalsContainer').append($(this).detach());
        });
    });

    // إعادة تهيئة select2 بعد كل redraw
    $('#dataTable').on('draw.dt', function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownCssClass: "select2-dropdown-scroll"

        });
    });

    $(document).on('select2:open', () => {
        let results = document.querySelector('.select2-results__options');
        if (results) {
            results.addEventListener('wheel', function(e) {
                e.stopPropagation(); // prevent parent scroll
            }, { passive: false }); // passive: false to allow preventDefault
        }
    });


</script>



<!-- Load voucherTypes Select2 translations -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>

<script>
    const trans = {
        loading: "{{ trns('loading...') }}",
        no_results: "{{ trns('no_results_found') }}",
        error_loading: "{{ trns('error_loading_data') }}"
    };
    const appLocale = '{{ app()->getLocale() }}';

</script>

<!-- Load group options for modals -->
<script>
    function initGroupSelect2(context = document) {
        $(context).find('select[name="group_id"]').each(function () {
            const select = $(this);

            // Destroy old instance if exists
            if (select.hasClass("select2-hidden-accessible")) {
                select.select2('destroy');
            }

            select.select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "{{ trns('select_group') }}",
                language: appLocale,
                dropdownParent: select.closest('.modal').length ? select.closest('.modal') : $('body'), // To work with modals
                ajax: {
                    url: (params) => {
                        const modalId = select.closest('.modal').attr('id');
                        const matches = modalId && modalId.match(/^editModal_(\d+)$/);
                        let url = "/dashboard/groups/list";
                        if (matches && matches[1]) {
                            url += "/" + matches[1];
                        }
                        return url;
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term // يتم إرساله للسيرفر
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.title
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    }

    // ✅ عند تحميل الصفحة لأول مرة
    $(document).ready(function () {
        initGroupSelect2();
    });

    // ✅ عند فتح أي مودال
    $(document).on('shown.bs.modal', '.modal', function () {
        initGroupSelect2(this);
    });

    // ✅ بعد إعادة رسم DataTable
    $('#dataTable').on('draw.dt', function () {
        initGroupSelect2(); // للصفحة بالكامل لأن المودالات ممكن تنضاف هنا
    });
</script>



<!-- Load voucherTypes Select2 -->
<script>
    function initVoucherTypeSelect2(context = document) {
        $(context).find('select[name="voucherType_id"]').each(function () {
            const select = $(this);

            // Destroy old instance if exists
            if (select.hasClass("select2-hidden-accessible")) {
                select.select2('destroy');
            }

            select.select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "{{ trns('select_voucherType') }}",
                language: appLocale,
                dropdownParent: select.closest('.modal').length ? select.closest('.modal') : $('body'), // To work with modals
                ajax: {
                    url: (params) => {
                        const modalId = select.closest('.modal').attr('id');
                        const matches = modalId && modalId.match(/^editModal_(\d+)$/);
                        let url = "/dashboard/voucher-types/list";
                        if (matches && matches[1]) {
                            url += "/" + matches[1];
                        }
                        return url;
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term // يتم إرساله للسيرفر
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.title
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    }

    // ✅ عند تحميل الصفحة لأول مرة
    $(document).ready(function () {
        initVoucherTypeSelect2();
    });

    // ✅ عند فتح أي مودال
    $(document).on('shown.bs.modal', '.modal', function () {
        initVoucherTypeSelect2(this);
    });

    // ✅ بعد إعادة رسم DataTable
    $('#dataTable').on('draw.dt', function () {
        initVoucherTypeSelect2(); // للصفحة بالكامل لأن المودالات ممكن تنضاف هنا
    });
</script>



<!-- Load Ledgers Select2  -->
<script>
    function initAllLedgerSelect2(context = document) {
        $(context).find('select[name="ledger_ids[]"]').each(function () {
            const select = $(this);

            // Destroy old instance if exists
            if (select.hasClass("select2-hidden-accessible")) {
                select.select2('destroy');
            }

            select.select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "{{ trns('select_ledger') }}",
                language: appLocale,
                dropdownParent: select.closest('.modal').length ? select.closest('.modal') : $('body'), // To work with modals
                ajax: {
                    url: "/dashboard/all-ledgers-list",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    // id: item.id,
                                    id: item.uuid,
                                    text: item.title,
                                };
                            })
                        };
                    },
                    cache: true
                },
            });

        });
    }

    // ✅ عند تحميل الصفحة لأول مرة
    $(document).ready(function () {
        initAllLedgerSelect2();
    });

    // ✅ عند فتح أي مودال
    $(document).on('shown.bs.modal', '.modal', function () {
        initAllLedgerSelect2(this);
    });

    // ✅ بعد إعادة رسم DataTable
    $('#dataTable').on('draw.dt', function () {
        initAllLedgerSelect2(); // للصفحة بالكامل لأن المودالات ممكن تنضاف هنا
    });
</script>



