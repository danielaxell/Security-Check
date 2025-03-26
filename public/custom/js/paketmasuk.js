var id_divisi;
$(document).ready(function () {
    /********************************************************** L O A D   P A K E T   M A S U K **********************************************************/
    getPaketMasuk();

    //simpan Add Paket Masuk
    function getPaketMasuk() {
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
                url: base_url + "/paketmasuk/getPaketMasuk",
                type: "GET",
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                {
                    render: function (data, type, row, meta) {
                        return (
                            "<span>" +
                            row.nama_terminal +
                            "</span><br/>" +
                            '<span style="font-size:12px"><b>Divisi :</b> ' +
                            row.nama_divisi +
                            "</span>"
                        );
                    },
                },
                {
                    render: function (data, type, row, meta) {
                        return (
                            "<span>" +
                            row.nama +
                            "</span><br/>" +
                            '<span style="font-size:12px"><b>NIPP :</b> ' +
                            row.nipp +
                            "</span>"
                        );
                    },
                },
                { data: "nama_paket" },
                { data: "nm_ekspedisi" },
                { data: "tgl_terima" },
                {
                    data: "id_paketmasuk",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-success" id="btn-serahterima" data-toggle="tooltip" title="Serah Terima" data-toggle="modal" data-target="#serahterimaModal" data-id_paketmasuk="' +
                            data +
                            '">' +
                            '<i class="fas fa-exchange-alt"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_paketmasuk="' +
                            data +
                            '">' +
                            '<i class="fa fa-trash-alt"></i>' +
                            "</a>" +
                            "</div>"
                        );
                    },
                },
            ],
        });
        $(".buttons-pdf, .buttons-excel").addClass("btn btn-info mr-1");
    }

    /********************************************************** A M B I L   F O T O   P A K E T   M A S U K **********************************************************/
    // setting gambar webcam dan attach ke tag html
    $(document).on("click", "#btn-add", function () {
        $("#result_snapshot").empty(); //hapus hsil snapshot sbelumnya
        // untuk setting ukuran gambar webcam
        Webcam.set({
            width: 320,
            height: 240,
            image_format: "jpeg",
            jpeg_quality: 90,
        });
        Webcam.attach("#my_camera");
    });

    // aksi setelah take foto
    $(document).on("click", "#take_snapshot", function () {
        // take snapshot and get image data
        Webcam.snap(function (data_uri) {
            // display results in page
            $("#result_snapshot").html(
                '<label>Hasil Foto</label><br><img src="' +
                    data_uri +
                    '" id="file_foto"/>'
            );
        });
    });

    // reset webcam in tambah modal
    $("#addModal").on("hidden.bs.modal", function () {
        Webcam.reset();
    });

    /********************************************************** A D D   P A K E T   M A S U K **********************************************************/
    // placeholder select2 form create&serahterima Paket Masuk
    $(".id").select2({
        placeholder: "Pilih Terminal",
    });
    $(".id_divisi").select2({
        placeholder: "Pilih Divisi",
    });
    $(".id_ekspedisi").select2({
        placeholder: "Pilih Ekspedisi",
    });
    $(".nipp").select2({
        placeholder: "Pilih Pegawai",
        minimumInputLength: 3,
    });

    // select2 nipp dengan search
    $(".nipp").select2({
        ajax: {
            url: base_url + "/user/getListUserAktif",
            dataType: "JSON",
            type: "GET",
            delay: 250,
            data: function (parameter) {
                return {
                    search: parameter.term,
                };
            },
            processResults: function (response) {
                // Hapus duplikasi data berdasarkan id (nipp)
                let uniqueResults = [];
                let seen = new Set();
                response.forEach(item => {
                    if (!seen.has(item.id)) {
                        seen.add(item.id);
                        uniqueResults.push(item);
                    }
                });
                return {
                    results: uniqueResults
                };
            },
            cache: false // Matikan cache untuk menghindari duplikasi
        },
        placeholder: "Masukkan Nama Pegawai / NIPP",
        minimumInputLength: 3,
    });

    // Event ketika NIPP dipilih untuk memunculkan data divisi
    $(document).on("change", ".nipp", function () {
        let selectedNipp = $(this).val();
        if (selectedNipp) {
            $.ajax({
                url: base_url + "/user/getDivisiByNipp/" + selectedNipp,
                method: "GET",
                success: function (response) {
                    let data = JSON.parse(response);
                    if (data && data.id_divisi) {
                        // Tambahkan opsi baru dan pilih divisi yang sesuai
                        let option = new Option(data.nama_divisi, data.id_divisi, true, true);
                        $(".id_divisi").append(option).trigger('change');
                    }
                }
            });
        }
    });

    // Inisialisasi select2 untuk divisi
    $(".id_divisi").select2({
        placeholder: "Pilih Divisi",
        ajax: {
            url: base_url + "/divisi/getListDivisi",
            dataType: "JSON",
            type: "GET",
            processResults: function (response) {
                return {
                    results: $.map(response, function(item) {
                        return {
                            id: item.id_divisi,
                            text: item.nama_divisi
                        }
                    })
                };
            }
        },
        allowClear: true
    });

    // Inisialisasi select2 untuk ekspedisi
    $(".id_ekspedisi").select2({
        placeholder: "Pilih Ekspedisi"
    });

    // validasi inputan Add Paket Masuk
    $("#insert_form").validate({
        rules: {
            id_divisi: "required",
            nipp: "required",
            nama_paket: "required",
            id_ekspedisi: "required",
            tgl_terima: "required",
        },
        messages: {
            id_divisi: "Divisi wajib diisi.",
            nipp: "NIPP wajib diisi.",
            nama_paket: "Paket wajib diisi.",
            id_ekspedisi: "Ekspedisi wajib diisi.",
            tgl_terima: "Tanggal Terima wajib diisi.",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.hasClass("select2")) {
                error.insertAfter(element.next("span"));
            } else if (element.hasClass("singledate")) {
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
        submitHandler: submitInsertPaketMasuk,
    });

    //simpan Add Paket Masuk
    function submitInsertPaketMasuk() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/paketmasuk/store",
            type: "POST",
            dataType: "JSON",
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content"), // harus mengunakan header token ini, karena tidak menggunakan $("#insert_form").serialize()
            },
            data: {
                id_divisi: $(".id_divisi").val(),
                nipp: $(".nipp").val(),
                nama_paket: $(".nama_paket").val(),
                id_ekspedisi: $(".id_ekspedisi").val(),
                tgl_terima: $(".tgl_terima").val(),
                file_foto: $("img#file_foto").attr("src"),
            },
            beforeSend: function () {
                $.LoadingOverlay("show"); // show overlay loading
                but.text("Saving..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#addModal").modal("hide");
                    getPaketMasuk();
                    toastr.success(data.pesan_success);
                    $("#success-notif")
                        .addClass("alert alert-success alert-dismissible")
                        .html(
                            '<h5><i class="icon fas fa-check"></i> Berhasil!</h5>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                data.pesan_success
                        );
                } else {
                    toastr.warning(data.pesan_warning);
                    $("#warning-add")
                        .addClass("alert alert-warning alert-dismissible")
                        .html(
                            '<h5><i class="con fas fa-exclamation-triangle"></i> Perhatian!</h5>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                data.pesan_warning
                        );
                }
            },
            complete: function () {
                $.LoadingOverlay("hide"); // hide overlay loading
                but.text("Simpan"); //change button text
                but.attr("disabled", false); //set button enable
            },
            error: function (xhr, status, error) {
                toastr.error(
                    status +
                        " " +
                        xhr.status +
                        " " +
                        error +
                        " " +
                        xhr.responseText
                );
            },
        });
    }

    /********************************************************** S E R A H   T E R I M A   P A K E T   M A S U K **********************************************************/
    // get Value Field serahterima Paket Masuk
    $("#tabledata").on("click", "#btn-serahterima", function () {
        // get data from button serahterima
        const id_paketmasuk = $(this).data("id_paketmasuk");
        if (id_paketmasuk) {
            $.ajax({
                url: base_url + "/paketmasuk/getPaketMasuk",
                method: "GET",
                data: {
                    id_paketmasuk: id_paketmasuk,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form serahterima Paket Masuk
                    $("#nama_terminal").text(data.nama_terminal);
                    $("#nama_divisi").text(data.nama_divisi);
                    $("#nipp").text(data.nipp);
                    $("#nama").text(data.nama);
                    $("#nama_paket").text(data.nama_paket);
                    $("#nm_ekspedisi").text(data.nm_ekspedisi);
                    $("#tgl_terima").text(data.tgl_terima);
                    $("input[type='hidden']#id_paketmasuk").val(id_paketmasuk);
                    $("input[type='hidden']#nama_paket").val(data.nama_paket);
                    $("img#file_foto").attr(
                        "src",
                        base_url + "/" + data.file_foto
                    );
                },
            });
        }
        // Call Modal serahterima
        $("#serahterimaModal").modal("show");
    });

    // validasi inputan serahterima Paket Masuk
    $("#serahterima_form").validate({
        rules: {
            penerima_fisik: "required",
            tgl_serah: "required",
        },
        messages: {
            penerima_fisik: "Penerima Fisik wajib diisi.",
            tgl_serah: "Tanggal Serah wajib diisi.",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.hasClass("select2")) {
                error.insertAfter(element.next("span"));
            } else if (element.hasClass("singledate")) {
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
        submitHandler: submitSerahTerimaPaketMasuk,
    });

    //simpan serahterima Paket Masuk
    function submitSerahTerimaPaketMasuk() {
        var but = $("#btn-update");
        var id_paketmasuk = $('input[type="hidden"]#id_paketmasuk').val();

        $.ajax({
            url: base_url + "/paketmasuk/serahterima/" + id_paketmasuk,
            type: "POST",
            dataType: "JSON",
            data: $("#serahterima_form").serialize(),
            beforeSend: function () {
                but.text("Updating..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#serahterimaModal").modal("hide");
                    getPaketMasuk();
                    toastr.success(data.pesan_success);
                    $("#success-notif")
                        .addClass("alert alert-success alert-dismissible")
                        .html(
                            '<h5><i class="icon fas fa-check"></i> Berhasil!</h5>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                data.pesan_success
                        );
                } else {
                    toastr.warning(data.pesan_warning);
                    $("#warning-serahterima")
                        .addClass("alert alert-warning alert-dismissible")
                        .html(
                            '<h5><i class="con fas fa-exclamation-triangle"></i> Perhatian!</h5>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                data.pesan_warning
                        );
                }
            },
            complete: function () {
                but.text("Update"); //change button text
                but.attr("disabled", false); //set button enable
            },
            error: function (xhr, status, error) {
                toastr.error(
                    status +
                        " " +
                        xhr.status +
                        " " +
                        error +
                        " " +
                        xhr.responseText
                );
            },
        });
    }

    /********************************************************** D E L E T E   P A K E T   M A S U K **********************************************************/
    // get Value Field Delete Paket Masuk
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_paketmasuk = $(this).data("id_paketmasuk");
        if (id_paketmasuk) {
            $.ajax({
                url: base_url + "/paketmasuk/getPaketMasuk",
                method: "GET",
                data: {
                    id_paketmasuk: id_paketmasuk,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $("b.nama_paket").text(data.nama_paket);
                    $("b.nama_terminal").text(data.nama_terminal);
                    $(".id_paketmasuk").val(id_paketmasuk);
                    $(".nama_paket").val(data.nama_paket);
                    $(".file_foto").val(data.file_foto);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete Paket Masuk
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_paketmasuk = $('input[type="hidden"].id_paketmasuk').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/paketmasuk/delete/" + id_paketmasuk,
            type: "POST",
            dataType: "JSON",
            data: $("#delete_form").serialize(),
            beforeSend: function () {
                but.text("Deleting..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal and reload ajax table
                    $("#deleteModal").modal("hide");
                    getPaketMasuk();
                    toastr.success(data.pesan_success);
                    $("#success-notif")
                        .addClass("alert alert-success alert-dismissible")
                        .html(
                            '<h5><i class="icon fas fa-check"></i> Berhasil!</h5>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                data.pesan_success
                        );
                } else {
                    $("#deleteModal").modal("hide");
                    toastr.warning(data.pesan_warning);
                    $("#warning-delete")
                        .addClass("alert alert-warning alert-dismissible")
                        .html(
                            '<h5><i class="con fas fa-exclamation-triangle"></i> Perhatian!</h5>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                data.pesan_warning
                        );
                }
            },
            complete: function () {
                but.text("Yes"); //change button text
                but.attr("disabled", false); //set button enable
            },
            error: function (xhr, status, error) {
                toastr.error(
                    status +
                        " " +
                        xhr.status +
                        " " +
                        error +
                        " " +
                        xhr.responseText
                );
            },
        });
    });
});
