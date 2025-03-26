$(document).ready(function () {
    /********************************************************** S E A R C H   H I S T O R I   P A K E T   K E L U A R **********************************************************/
    // validasi inputan Search Paket Keluar
    $("#cari_form").validate({
        rules: {
            tgl_cari: "required",
        },
        messages: {
            tgl_cari: "Tanggal Pencarian Tidak Boleh Kosong.",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.hasClass("select2")) {
                error.insertAfter(element.next("span"));
            } else if (element.hasClass("rangedate")) {
                divparent = element.next(".input-group-append");
                error.insertAfter(divparent);
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: submitInsertTamuHadirHist,
    });

    //simpan Search Paket Keluar
    function submitInsertTamuHadirHist() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            // dom default lBfrtip
            dom:
                "<'row'<'col-md-2'l><'col-md-2'B><'col-md-8'f>>" + // atur show entries, tombol export, dan search
                "<'row'<'col-md-12'tr>>" + // atur isi table
                "<'row'<'col-md-5'i><'col-md-7'p>>", // atur info showing entries dan page
            buttons: ["excel", "pdf"],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"],
            ], // setting custom entries u/ kebutuhan export all
            destroy: true,
            ajax: {
                url: base_url + "/tamuhadirhist/getTamuHadir",
                type: "GET",
                data: function () {
                    return $("#cari_form").serialize();
                },
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "nama_terminal" },
                { data: "nama_divisi" },
                { data: "nipp" },
                { data: "nama_pegawai" },
                { data: "ktp" },
                { data: "nama_tamu" },
                { data: "tgl_datang" },
                { data: "tgl_pulang" },
            ],
            columnDefs: [
                {
                    targets: 9,
                    data: "id_tamuhadir",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-success" id="btn-view" data-toggle="tooltip" title="View Detail" data-toggle="modal"' +
                            'data-target="#viewModal" data-id_tamuhadir="' +
                            data +
                            '"> <i class="fas fa-eye"></i> </a>' +
                            "</div>"
                        );
                    },
                },
            ],
        });
        $(".buttons-pdf, .buttons-excel").addClass("btn btn-info mr-1");
    }

    /********************************************************** V I E W   H I S T O R I   P A K E T   K E L U A R **********************************************************/
    // get Value Field view hisori Paket Keluar
    $("#tabledata").on("click", "#btn-view", function () {
        // get data from button view
        const id_tamuhadir = $(this).data("id_tamuhadir");
        if (id_tamuhadir) {
            $.ajax({
                url: base_url + "/tamuhadirhist/getTamuHadir",
                method: "GET",
                data: {
                    id_tamuhadir: id_tamuhadir,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form view Paket Keluar
                    $("td#nama_terminal").text(data.nama_terminal);
                    $("td#nama_divisi").text(data.nama_divisi);
                    $("td#nipp").text(data.nipp);
                    $("td#nama_pegawai").text(data.nama_pegawai);
                    $("td#ktp").text(data.ktp);
                    $("td#nama_tamu").text(data.nama_tamu);
                    $("td#instansi").text(data.instansi);
                    $("td#nonama_kartuakses").text(
                        data.no_kartuakses + " | " + data.nama_kartuakses
                    );
                    $("td#tgl_datang").text(data.tgl_datang);
                    $("td#tgl_pulang").text(data.tgl_pulang);
                    $("img#file_foto").attr(
                        "src",
                        base_url + "/" + data.file_foto
                    );
                },
            });
        }
        // Call Modal view
        $("#viewModal").modal("show");
    });
});
