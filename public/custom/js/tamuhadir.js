var id_divisi;
$(document).ready(function () {
    getTamuHadir();
    /********************************************************** C A R I   K E H A D I R A N   T A M U **********************************************************/
    // select2 get list dropdown tamu
    $(".id_tamu").select2({
        ajax: {
            url: base_url + "/tamu/getListTamu",
            dataType: "JSON",
            type: "GET",
            delay: 250,
            data: function (parameter) {
                //parameter input untuk di pass ke controller bebas contoh q: data.term, hal: data.page. objek term dan page baku dari select2
                return {
                    search: parameter.term,
                };
            },
            processResults: function (response) {
                //parameter output untuk yg dipass dr controller bebas contoh result: data.item, pagination: data.total. result dan pagination baku dari select2
                return {
                    results: response,
                };
            },
            cache: true,
        },
        placeholder: "Masukkan Nama / No.KTP",
        minimumInputLength: 3,
    });

    /********************************************************** A M B I L  F O T O  T A M U **********************************************************/
    // edit foto tamu
    // $(document).on("click", "#btn-ubah-foto", function () {
    //   // hide file foto
    //   $("#frame_file_foto").css("display", "none");
    //   // show camera preview
    //   $("#frame_camera_preview").css("display", "block");
    //   showWebcam();
    // });

    // show webcam
    function showWebcam() {
        // untuk setting ukuran gambar webcam
        Webcam.set({
            width: 320,
            height: 240,
            image_format: "jpeg",
            jpeg_quality: 90,
        });
        Webcam.attach("#my_camera");
    }

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

    // reset webcam in edit modal
    $(document).on("click", "#btn-cancel", function () {
        Webcam.reset(); // webcam dimatikan
        hideFormTamuHadir();
    });

    /********************************************************** L O A D   K E H A D I R A N   T A M U **********************************************************/
    //simpan Add Tamu
    function getTamuHadir() {
        $(".select2.id_tamu").val(null).change();
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
                url: base_url + "/tamuhadir/getTamuHadir",
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
                            row.nama_pegawai +
                            "</span><br/>" +
                            '<span style="font-size:12px"><b>NIPP :</b> ' +
                            row.nipp +
                            "</span>"
                        );
                    },
                },
                { data: "ktp" },
                { data: "nama_tamu" },
                { data: "tgl_datang" },
                {
                    data: "id_tamuhadir",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<button type="button" class="btn btn-sm btn-success btn-serahterima" data-id_tamuhadir="' +
                            data +
                            '" data-toggle="tooltip" title="Serah Terima">' +
                            '<i class="fas fa-exchange-alt"></i>' +
                            '</button>' +
                            '<button type="button" class="btn btn-sm btn-danger btn-delete" data-id_tamuhadir="' +
                            data +
                            '" data-toggle="tooltip" title="Hapus">' +
                            '<i class="fa fa-trash-alt"></i>' +
                            '</button>' +
                            "</div>"
                        );
                    },
                },
            ],
            drawCallback: function() {
                // Bind event handler setelah tabel selesai di-render
                $('.btn-serahterima').on('click', function() {
                    const id_tamuhadir = $(this).data("id_tamuhadir");
                    handleSerahterima(id_tamuhadir);
                });
                
                $('.btn-delete').on('click', function() {
                    const id_tamuhadir = $(this).data("id_tamuhadir");
                    handleDelete(id_tamuhadir);
                });
            }
        });
        $(".buttons-pdf, .buttons-excel").addClass("btn btn-info mr-1");
    }

    // Handler untuk tombol serah terima
    function handleSerahterima(id_tamuhadir) {
        if (id_tamuhadir) {
            $.ajax({
                url: base_url + "/tamuhadir/getTamuHadir",
                method: "GET",
                data: {
                    id_tamuhadir: id_tamuhadir,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form serahterima Tamu
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
                    $("input[type='hidden']#id_tamuhadir").val(
                        data.id_tamuhadir
                    );
                    $("input[type='hidden']#ktp").val(data.ktp);
                    $("input[type='hidden']#nama").val(data.nama_tamu);
                    $("input[type='hidden']#tgl_datang").val(data.tgl_datang);
                    $("#file_foto_edit").attr(
                        "src",
                        base_url + "/" + data.file_foto
                    );
                    
                    // Tampilkan modal
                    $("#serahterimaModal").modal("show");
                },
                error: function(xhr, status, error) {
                    toastr.error("Terjadi kesalahan saat mengambil data tamu: " + error);
                }
            });
        }
    }

    // Handler untuk tombol delete
    function handleDelete(id_tamuhadir) {
        if (id_tamuhadir) {
            $.ajax({
                url: base_url + "/tamuhadir/getTamuHadir",
                method: "GET",
                data: {
                    id_tamuhadir: id_tamuhadir,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $("b.ktp").text(data.ktp);
                    $("b.nama").text(data.nama_tamu);
                    $("b.tgl_datang").text(data.tgl_datang);
                    $("input[type='hidden'].id_tamuhadir").val(
                        data.id_tamuhadir
                    );
                    $("input[type='hidden'].ktp").val(data.ktp);
                    $("input[type='hidden'].nama").val(data.nama_tamu);
                    $("input[type='hidden'].tgl_datang").val(data.tgl_datang);
                    
                    // Tampilkan modal
                    $("#deleteModal").modal("show");
                },
                error: function(xhr, status, error) {
                    toastr.error("Terjadi kesalahan saat mengambil data tamu: " + error);
                }
            });
        }
    }

    function hideFormTamuHadir() {
        // form input dihide
        $("div.form_input_tamu").css("display", "none");
        $("div.tombol_input_tamu").css("display", "none");
        $("#frame_camera_preview").css("display", "none"); // hide camera preview
        $("#result_snapshot").empty(); // hapus hasil snapshot sbelumnya
        $("#frame_file_foto").css("display", "none"); // show file foto
    }

    function showFormTamuHadir(parameter) {
        if (parameter == "add_master_tamu") {
            // form input dimunculkan
            $("div.form_input_tamu").css("display", "flex");
            $("div.tombol_input_tamu").css("display", "block");
            $("#frame_camera_preview").css("display", "block"); // show camera preview
            $("#result_snapshot").empty(); // hapus hasil snapshot sbelumnya
            $("#frame_file_foto").css("display", "none"); // hide file foto
        } else {
            // form input dimunculkan
            $("div.form_input_tamu").css("display", "flex");
            $("div.tombol_input_tamu").css("display", "block");
            $("#frame_camera_preview").css("display", "none"); // hide camera preview
            $("#result_snapshot").empty(); // hapus hasil snapshot sbelumnya
            $("#frame_file_foto").css("display", "block"); // show file foto
        }
    }

    /********************************************************** A D D   K E H A D I R A N   T A M U **********************************************************/
    // get list kartuakses tersedia
    function getListKartuAksesTersedia() {
        $.ajax({
            url: base_url + "/kartuakses/getListKartuAksesTersedia",
            method: "GET",
            success: function (data) {
                $(".id_kartuakses").html(
                    '<option value="">Pilih Kartu Akses</option>'
                );
                var dataObj = jQuery.parseJSON(data);
                if (dataObj) {
                    $(dataObj).each(function () {
                        var option = $("<option>");
                        option
                            .attr("value", this.id_kartuakses)
                            .text(this.nonama_kartuakses);
                        $(".id_kartuakses").append(option);
                    });
                }
            },
        });
    }

    // memunculkan form add
    $(document).on("click", "#btn-add", function () {
        $(".ktp").prop("disabled", false);
        $(".nama").prop("disabled", false);
        $(".alamat").prop("disabled", false);
        $(".instansi").prop("disabled", false);
        var param = "add_master_tamu";
        showFormTamuHadir(param);
        showWebcam();
        $("input[type='hidden']#akses").val(param);

        // get list kartuakses tersedia
        getListKartuAksesTersedia();
    });

    // isi info di form add jika list tamu di klik add_not_master_tamu
    $(document).on("change", ".id_tamu", function () {
        var id_tamu = $(".id_tamu").val();
        if (id_tamu) {
            // jika id tamu bukan null/ada nilainya //lakukan reset form
            var inputan = $("input, select");
            $(".select2.nipp").val(null).change(); //clear isian select2
            $(".select2.id_divisi").val(null).change(); //clear isian select2
            $(".invalid-feedback").remove(); // hapus validasi error
            $(document).find(inputan).removeClass("is-invalid"); // hapus warna field merah invalid
            $(document).find(inputan).removeClass("is-valid"); // hapus warna field hijau valid
            $(".alert").empty(); //hapus isi alert notif
            $(".alert").removeClass("alert"); //hapus alert notif

            Webcam.reset(); // webcam dimatikan
            var param = "add_not_master_tamu";
            showFormTamuHadir(param);

            // get list kartuakses tersedia
            getListKartuAksesTersedia();

            // get data from button serahterima
            if (id_tamu) {
                $.ajax({
                    url: base_url + "/tamu/getTamu",
                    method: "GET",
                    data: {
                        id_tamu: id_tamu,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        // Set data to Form Edit Tamu
                        $(".ktp").val(data.ktp);
                        $(".nama").val(data.nama);
                        $(".alamat").val(data.alamat);
                        $(".instansi").val(data.instansi);
                        $("#data_file_foto").attr(
                            "src",
                            base_url + "/" + data.file_foto
                        );
                        $("input[type='hidden']#id_tamu").val(data.id_tamu);
                        // $("input[type='hidden']#nama").val(data.nama);
                        // $("input[type='hidden']#ktp").val(data.ktp);
                        $("input[type='hidden']#akses").val(param);
                        $(".ktp").prop("disabled", true);
                        $(".nama").prop("disabled", true);
                        $(".alamat").prop("disabled", true);
                        $(".instansi").prop("disabled", true);
                    },
                });
            }
        }
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

    // Inisialisasi select2 untuk kartu akses
    $(".id_kartuakses").select2({
        placeholder: "Pilih Kartu Akses"
    });

    // validasi form
    $("#insert_form").validate({
        rules: {
            ktp: "required",
            nama: "required",
            alamat: "required",
            instansi: "required",
            nipp: "required",
            id_divisi: "required",
            id_kartuakses: "required",
            tgl_masuk: "required",
            jam_masuk: "required"
        },
        messages: {
            ktp: "KTP wajib diisi.",
            nama: "Nama wajib diisi.",
            alamat: "Alamat wajib diisi.",
            instansi: "Instansi wajib diisi.",
            nipp: "NIPP wajib diisi.",
            id_divisi: "Divisi wajib diisi.",
            id_kartuakses: "Kartu Akses wajib diisi.",
            tgl_masuk: "Tanggal Masuk wajib diisi.",
            jam_masuk: "Jam Masuk wajib diisi."
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
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
        submitHandler: submitInsertTamuHadir
    });

    // Radio button handler untuk jenis tamu
    $('input[type=radio][name=jenis_tamu]').change(function() {
        if (this.value == 'tamu_baru') {
            $('.form_input_tamu').show();
            $('.form_cari_tamu').hide();
        }
        else if (this.value == 'tamu_lama') {
            $('.form_input_tamu').hide();
            $('.form_cari_tamu').show();
        }
    });

    // Fungsi untuk submit form tambah tamu
    function submitInsertTamuHadir() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/tamuhadir/store",
            type: "POST",
            dataType: "JSON",
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content")
            },
            data: {
                ktp: $(".ktp").val(),
                nama: $(".nama").val(),
                alamat: $(".alamat").val(),
                instansi: $(".instansi").val(),
                file_foto: $("img#file_foto").attr("src"),
                nipp: $(".nipp").val(),
                id_divisi: $(".id_divisi").val(),
                id_kartuakses: $(".id_kartuakses").val(),
                tgl_datang: $(".tgl_masuk").val(),
                jam_datang: $(".jam_masuk").val(),
                id_tamu: $("#id_tamu").val(),
                akses: $("#akses").val(),
            },
            beforeSend: function () {
                $.LoadingOverlay("show"); // show overlay loading
                but.text("Saving..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    getTamuHadir();
                    toastr.success(data.pesan_success);
                    $("#success-notif")
                        .addClass("alert alert-success alert-dismissible")
                        .html(
                            '<h5><i class="icon fas fa-check"></i> Berhasil!</h5>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                data.pesan_success
                        );
                    hideFormTamuHadir();
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

    /********************************************************** S E R A H   T E R I M A   K E H A D I R A N   T A M U **********************************************************/
    // validasi inputan serahterima Tamu
    $("#serahterima_form").validate({
        rules: {
            tgl_pulang: "required",
        },
        messages: {
            tgl_pulang: "Tanggal Pulang wajib diisi.",
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
        submitHandler: submitSerahTerimaTamuHadir,
    });

    //simpan serahterima Tamu
    function submitSerahTerimaTamuHadir() {
        var but = $("#btn-update");
        var id_tamuhadir = $('input[type="hidden"]#id_tamuhadir').val();

        $.ajax({
            url: base_url + "/tamuhadir/serahterima/" + id_tamuhadir,
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
                    getTamuHadir();
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

    /********************************************************** D E L E T E   K E H A D I R A N   T A M U **********************************************************/
    // simpan delete Tamu
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_tamuhadir = $('input[type="hidden"].id_tamuhadir').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/tamuhadir/delete/" + id_tamuhadir,
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
                    getTamuHadir();
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
