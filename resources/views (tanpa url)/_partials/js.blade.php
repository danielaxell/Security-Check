<!-- All Jquery -->
<script src="/assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="/assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- apps -->
<script src="/dist/js/app.min.js"></script>
<script src="/dist/js/app.init.js"></script>
<script src="/dist/js/app-style-switcher.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="/assets/extra-libs/sparkline/sparkline.js"></script>
<!-- Wave Effects -->
<script src="/dist/js/waves.js"></script>
<!-- Menu sidebar -->
<script src="/dist/js/sidebarmenu.js"></script>
<!-- Web Default JavaScript -->
<script src="/dist/js/custom.min.js"></script>
<!-- datatable javascript -->
<script src="/assets/extra-libs/DataTables/datatables.min.js"></script>
<!-- datatable file export javascript -->
<script src="/assets/extra-libs/DataTables/file_export/dataTables.buttons.min.js"></script>
<script src="/assets/extra-libs/DataTables/file_export/buttons.flash.min.js"></script>
<script src="/assets/extra-libs/DataTables/file_export/jszip.min.js"></script>
<script src="/assets/extra-libs/DataTables/file_export/pdfmake.min.js"></script>
<script src="/assets/extra-libs/DataTables/file_export/vfs_fonts.js"></script>
<script src="/assets/extra-libs/DataTables/file_export/buttons.html5.min.js"></script>
<script src="/assets/extra-libs/DataTables/file_export/buttons.print.min.js"></script>
<!-- validation JS -->
<script src="/assets/libs/jquery-validation/dist/jquery.validate.min.js"></script>
<!-- additional validation alphanumeric JS -->
<script src="/assets/libs/jquery-validation/dist/additional/alphanumeric.js"></script>
<!-- Toastr JS -->
<script src="/assets/libs/toastr/build/toastr.min.js"></script>
<!-- show password JS -->
<script src="/assets/libs/bootstrap-show-password.js"></script>
<!-- Select2 JS -->
<script src="/assets/libs/select2/dist/js/select2.full.min.js"></script>
<script src="/assets/libs/select2/dist/js/select2.min.js"></script>
<!-- Switch JS -->
<script src="/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
<!-- date-range-picker -->
<script src="/assets/libs/daterangepicker/new/moment.min.js"></script>
<script src="/assets/libs/daterangepicker/new/daterangepicker.min.js"></script>
<!-- loading overlay -->
<script src="/assets/libs/loadingoverlay/loadingoverlay.min.js"></script>

<script>
    var base_url = '{{ url("") }}';

        // additional validation alphanumericspace
        jQuery.validator.addMethod("alphanumericspace", function(value, element) {
            return this.optional(element) || /^[0-9a-zA-Z ]*$/.test(value);
        }, "Letters, numbers, and spaces only please");
        // additional validation numeric
        jQuery.validator.addMethod("numeric", function(value, element) {
            return this.optional(element) || /^[0-9]*$/.test(value);
        }, "Numbers only please");

        $(document).ready(function() {
            // init datatable
            $('#tabledata').DataTable({
                "stateSave": true, //dapat kembali sesuai page terakhir di klik
                "stateSaveParams": function(settings, data) { //clear field search
                    data.search.search = "";
                }
            });
            $('.buttons-pdf, .buttons-excel').addClass('btn btn-info mr-1');
            // init Select2
            $('.select2').select2({
                allowClear: true
            });
            // init switch checkbox or radio
            $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
            // init single date
            $('.singledate').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "opens": "left",
                "drops": "auto",
                "locale": {
                    "format": "DD/MM/YYYY"
                },
            });
            // init single date up
            $('.singledateup').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "opens": "left",
                "drops": "up",
                "locale": {
                    "format": "DD/MM/YYYY"
                },
            });
            // init single date time
            $('.singledatetime').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "opens": "left",
                "drops": "auto",
                "locale": {
                    "format": "DD/MM/YYYY HH:mm"
                },
            });
            // init range date
            $('.rangedate').daterangepicker({
                "showDropdowns": true,
                "autoApply": true,
                "opens": "left",
                "drops": "auto",
                "locale": {
                    "format": "DD/MM/YYYY"
                },
            });
            // init tooltip agar terbaca di datatable ajax
            $('body').tooltip({
                selector: '[data-toggle="tooltip"]'
            });

            // clear data in add Modal
            $(document).on("click", "#btn-add", function() {
                var inputan = $("input,textarea,select");
                $("#insert_form")[0].reset(); // clear isian form
                $(".select2").val(null).change(); //clear isian select2
                $('.select2limit').val(null).change(); //clear isian select2limit
                $(".invalid-feedback").remove(); // hapus validasi error
                $(document).find(inputan).removeClass("is-invalid"); // hapus warna field merah invalid
                $(document).find(inputan).removeClass("is-valid"); // hapus warna field hijau valid
                $(".alert").empty(); //hapus isi alert notif
                $(".alert").removeClass('alert'); //hapus alert notif
            });
            // clear data on form edit in datatable
            $("#tabledata").on("click", "#btn-edit", function() {
                var inputan = $("input,textarea,select");
                $("#edit_form")[0].reset(); // clear isian form
                $(".select2").val(null).change(); //clear isian select2
                $('.select2limit').val(null).change(); //clear isian select2limit
                $(".invalid-feedback").remove(); // hapus validasi error
                $(document).find(inputan).removeClass("is-invalid"); // hapus warna field merah invalid
                $(document).find(inputan).removeClass("is-valid"); // hapus warna field hijau valid
                $(".alert").empty(); //hapus isi alert notif
                $(".alert").removeClass('alert'); //hapus alert notif
            });
        });
</script>

<!-- Notif when load page  -->
<!-- Notif untuk flash data "sucess" -->
@if (session()->has('success'))
<script>
    $(window).on('load', function() {
                toastr.success('{{ session("success") }}');
            });
</script>
@endif
<!-- Notif untuk flash data "warning" -->
@if (session()->has('warning'))
<script>
    $(window).on('load', function() {
                toastr.warning('{{ session("warning") }}');
            });
</script>
@endif
<!-- Notif untuk flash data "info" -->
@if (session()->has('info'))
<script>
    $(window).on('load', function() {
                toastr.info('{{ session("info") }}');
            });
</script>
@endif
<!-- Notif untuk flash data "danger" -->
@if (session()->has('danger'))
<script>
    $(window).on('load', function() {
                toastr.error('{{ session("danger") }}');
            });
</script>
@endif