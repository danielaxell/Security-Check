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
        submitHandler: submitInsertPaketKeluarHist,
    });

    //simpan Search Paket Keluar
    function submitInsertPaketKeluarHist() {
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
                url: base_url + "/paketkeluarhist/getPaketKeluar",
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
                { data: "nama" },
                { data: "nama_paket" },
                { data: "tujuan_paket" },
                { data: "nm_ekspedisi" },
                { data: "tgl_terima" },
                { data: "tgl_serah" },
            ],
            columnDefs: [
                {
                    targets: 9,
                    data: "id_paketkeluar",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-success" id="btn-view" data-toggle="tooltip" title="View Detail" data-toggle="modal"' +
                            'data-target="#viewModal" data-id_paketkeluar="' +
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
        const id_paketkeluar = $(this).data("id_paketkeluar");
        if (id_paketkeluar) {
            $.ajax({
                url: base_url + "/paketkeluarhist/getPaketKeluar",
                method: "GET",
                data: {
                    id_paketkeluar: id_paketkeluar,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form view Paket Keluar
                    $("#nama_terminal").text(data.nama_terminal);
                    $("#nama_divisi").text(data.nama_divisi);
                    $("#nipp").text(data.nipp);
                    $("#nama").text(data.nama);
                    $("#nama_paket").text(data.nama_paket);
                    $("#tujuan_paket").text(data.tujuan_paket);
                    $("#nm_ekspedisi").text(data.nm_ekspedisi);
                    $("#tgl_terima").text(data.tgl_terima);
                    $("#tgl_serah").text(data.tgl_serah);
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
