var id_divisi;
$(document).ready(function () {
    /********************************************************** L O A D   P A K E T   K E L U A R **********************************************************/
    getPaketKeluar();

    //simpan Add Paket Masuk
    function getPaketKeluar() {
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
                url: base_url + "/paketkeluar/getPaketKeluar",
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
                { data: "tujuan_paket" },
                { data: "tgl_terima" },
                {
                    data: "id_paketkeluar",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-success" id="btn-serahterima" data-toggle="tooltip" title="Serah Terima" data-toggle="modal" data-target="#serahterimaModal" data-id_paketkeluar="' +
                            data +
                            '">' +
                            '<i class="fas fa-exchange-alt"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_paketkeluar="' +
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

    /********************************************************** A M B I L   F O T O   P A K E T   M A S U K **********************************************************/ // setting gambar webcam dan attach ke tag html
    $(document).on("click", "#btn-serahterima", function () {
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
    $("#serahterimaModal").on("hidden.bs.modal", function () {
        Webcam.reset();
    });

    /********************************************************** A D D   P A K E T   K E L U A R **********************************************************/
    // placeholder select2 form create&serahterima Paket Masuk
    $(".id").select2({
        placeholder: "Pilih Terminal",
    });
    $(".id_divisi").select2({
        placeholder: "Pilih Divisi",
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

    // Reset form ketika modal ditutup
    $('#addModal').on('hidden.bs.modal', function() {
        $('#search_nipp').val(null).trigger('change');
        $('#nama_divisi').val('');
        $('#id_divisi').val('');
    });

    // validasi inputan Add Paket Masuk
    $("#insert_form").validate({
        rules: {
            id_divisi: "required",
            nipp: "required",
            nama_paket: "required",
            tujuan_paket: "required",
            tgl_terima: "required",
        },
        messages: {
            id_divisi: "Divisi wajib diisi.",
            nipp: "NIPP wajib diisi.",
            nama_paket: "Paket wajib diisi.",
            tujuan_paket: "Tujuan Paket wajib diisi.",
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
        submitHandler: submitInsertPaketKeluar,
    });

    //simpan Add Paket Masuk
    function submitInsertPaketKeluar() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/paketkeluar/store",
            type: "POST",
            dataType: "JSON",
            data: $("#insert_form").serialize(),
            beforeSend: function () {
                but.text("Saving..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#addModal").modal("hide");
                    getPaketKeluar();
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

    /********************************************************** S E R A H   T E R I M A   P A K E T   K E L U A R **********************************************************/
    // placeholder select2 form serahterima Paket Masuk
    $("#id_ekspedisi").select2({
        placeholder: "Pilih Ekspedisi",
    });
    // get Value Field serahterima Paket Masuk
    $("#tabledata").on("click", "#btn-serahterima", function () {
        // get data from button serahterima
        const id_paketkeluar = $(this).data("id_paketkeluar");
        if (id_paketkeluar) {
            $.ajax({
                url: base_url + "/paketkeluar/getPaketKeluar",
                method: "GET",
                data: {
                    id_paketkeluar: id_paketkeluar,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form serahterima Paket Masuk
                    $("#nama_terminal").text(data.nama_terminal);
                    $("#nama_divisi").text(data.nama_divisi);
                    $("#nipp").text(data.nipp);
                    $("#nama").text(data.nama);
                    $("#nama_paket").text(data.nama_paket);
                    $("#tujuan_paket").text(data.tujuan_paket);
                    $("#tgl_terima").text(data.tgl_terima);
                    $("input[type='hidden']#id_paketkeluar").val(
                        id_paketkeluar
                    );
                    $("input[type='hidden']#nipp").val(data.nipp);
                    $("input[type='hidden']#nama_paket").val(data.nama_paket);
                },
            });
        }
        // Call Modal serahterima
        $("#serahterimaModal").modal("show");
    });

    // validasi inputan serahterima Paket Masuk
    $("#serahterima_form").validate({
        rules: {
            id_ekspedisi: "required",
            tgl_serah: "required",
        },
        messages: {
            id_ekspedisi: "Ekspedisi wajib diisi.",
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
        submitHandler: submitSerahTerimaPaketKeluar,
    });

    //simpan serahterima Paket Masuk
    function submitSerahTerimaPaketKeluar() {
        var but = $("#btn-update");
        var id_paketkeluar = $('input[type="hidden"]#id_paketkeluar').val();

        $.ajax({
            url: base_url + "/paketkeluar/serahterima/" + id_paketkeluar,
            type: "PUT", // harus pakai PUT, krn data di ajax tidak menggunakan $("#serahterima_form").serialize()
            dataType: "JSON",
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content"), // harus mengunakan header token ini, karena tidak menggunakan $("#serahterima_form").serialize()
            },
            data: {
                file_foto: $("img#file_foto").attr("src"),
                id_ekspedisi: $("#id_ekspedisi").val(),
                tgl_serah: $("#tgl_serah").val(),
                id_paketkeluar: $("#id_paketkeluar").val(),
                nipp: $("input[type='hidden']#nipp").val(),
                nama_paket: $("input[type='hidden']#nama_paket").val(),
            },
            beforeSend: function () {
                $.LoadingOverlay("show"); // show overlay loading
                but.text("Updating..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#serahterimaModal").modal("hide");
                    getPaketKeluar();
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
                $.LoadingOverlay("hide"); // hide overlay loading
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

    /********************************************************** D E L E T E   P A K E T   K E L U A R **********************************************************/
    // get Value Field Delete Paket Masuk
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_paketkeluar = $(this).data("id_paketkeluar");
        if (id_paketkeluar) {
            $.ajax({
                url: base_url + "/paketkeluar/getPaketKeluar",
                method: "GET",
                data: {
                    id_paketkeluar: id_paketkeluar,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $("b.nama_paket").text(data.nama_paket);
                    $("b.nama_terminal").text(data.nama_terminal);
                    $(".id_paketkeluar").val(id_paketkeluar);
                    $(".nama_paket").val(data.nama_paket);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete Paket Masuk
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_paketkeluar = $('input[type="hidden"].id_paketkeluar').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/paketkeluar/delete/" + id_paketkeluar,
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
                    getPaketKeluar();
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
                $("#btn_delete").text("Yes"); //change button text
                $("#btn_delete").attr("disabled", false); //set button enable
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

    // Handle form submit
    $('#add_form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#addModal').modal('hide');
                    // Refresh tabel atau tampilkan pesan sukses
                    alert(response.pesan_success);
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Gagal menyimpan data');
            }
        });
    });
});

