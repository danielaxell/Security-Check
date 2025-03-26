$(document).ready(function () {
    getTamu();
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

    /********************************************************** L O A D   T A M U **********************************************************/
    //simpan Add Paket Masuk
    function getTamu() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            dom: "lBfrtip",
            buttons: ["excel", "pdf"],
            destroy: true,
            ajax: {
                url: base_url + "/tamu/getTamu",
                type: "GET",
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "ktp" },
                { data: "nama" },
                { data: "alamat" },
                { data: "instansi" },
            ],
            columnDefs: [
                {
                    targets: 5,
                    data: "id_tamu",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal"' +
                            'data-target="#editModal" data-id_tamu="' +
                            data +
                            '"> <i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal"' +
                            'data-target="#deleteModal" data-id_tamu="' +
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

    /********************************************************** A D D   T A M U **********************************************************/
    // validasi inputan Add User
    $("#insert_form").validate({
        rules: {
            ktp: {
                required: true,
                maxlength: 16,
                numeric: true,
            },
            nama: {
                required: true,
            },
            alamat: {
                required: true,
            },
            instansi: {
                required: true,
            },
        },
        messages: {
            ktp: {
                required: "No.KTP wajib diisi.",
                maxlength: "No.KTP maximal 16 karakter.",
                numeric: "No.KTP hanya boleh berisi angka.",
            },
            nama: "Nama wajib diisi.",
            alamat: "Alamat wajib diisi.",
            instansi: "Instansi wajib diiisi.",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.hasClass("select2")) {
                error.insertAfter(element.next("span"));
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
        submitHandler: submitInsertTamu,
    });

    //simpan Add User
    function submitInsertTamu() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/tamu/store",
            type: "POST",
            dataType: "JSON",
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content"), //harus mengunakan header token ini, karena tidak menggunakan $("#edit_form").serialize()
            },
            data: {
                ktp: $(".ktp").val(),
                nama: $(".nama").val(),
                alamat: $(".alamat").val(),
                instansi: $(".instansi").val(),
                file_foto: $("img#file_foto").attr("src"),
            },
            beforeSend: function () {
                but.text("Saving..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#addModal").modal("hide");
                    getTamu();
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

    /********************************************************** E D I T   T A M U **********************************************************/
    // get Value Field Edit Tamu
    $("#tabledata").on("click", "#btn-edit", function () {
        $("#frame_camera_preview_edit").css("display", "none"); // hide camera preview
        $("#result_snapshot_edit").empty(); // hapus hasil snapshot sbelumnya
        $("#frame_file_foto_edit").css("display", "block"); // show file foto
        // get data from button serahterima
        const id_tamu = $(this).data("id_tamu");
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
                    $("#ktp").val(data.ktp);
                    $("#nama").val(data.nama);
                    $("#alamat").val(data.alamat);
                    $("#instansi").val(data.instansi);
                    $("img#file_foto").attr(
                        "src",
                        base_url + "/" + data.file_foto
                    );
                    $("input[type='hidden']#id_tamu").val(id_tamu);
                },
            });
        }
        // Call Modal serahterima
        $("#editModal").modal("show");
    });

    // edit foto tamu
    $(document).on("click", "#btn-ubah-foto", function () {
        // hide file foto
        $("#frame_file_foto_edit").css("display", "none");
        // show camera preview
        $("#frame_camera_preview_edit").css("display", "block");
        // untuk setting ukuran gambar webcam
        Webcam.set({
            width: 320,
            height: 240,
            image_format: "jpeg",
            jpeg_quality: 90,
        });
        Webcam.attach("#my_camera_edit");
    });

    // aksi setelah take foto
    $(document).on("click", "#take_snapshot_edit", function () {
        // take snapshot and get image data
        Webcam.snap(function (data_uri) {
            // display results in page
            $("#result_snapshot_edit").html(
                '<label>Hasil Foto</label><br><img src="' +
                    data_uri +
                    '" id="file_foto_edit"/>'
            );
        });
    });

    // reset webcam in edit modal
    $("#editModal").on("hidden.bs.modal", function () {
        Webcam.reset();
    });

    // validasi inputan Edit User
    $("#edit_form").validate({
        rules: {
            ktp: {
                required: true,
                maxlength: 16,
                numeric: true,
            },
            nama: {
                required: true,
            },
            alamat: {
                required: true,
            },
            instansi: {
                required: true,
            },
        },
        messages: {
            ktp: {
                required: "No.KTP wajib diisi.",
                maxlength: "No.KTP maximal 16 karakter.",
                numeric: "No.KTP hanya boleh berisi angka.",
            },
            nama: "Nama wajib diisi.",
            alamat: "Alamat wajib diisi.",
            instansi: "Instansi wajib diiisi.",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.hasClass("select2")) {
                error.insertAfter(element.next("span"));
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
        submitHandler: submitEditTamu,
    });

    //simpan Edit User
    function submitEditTamu() {
        var but = $("#btn-update");
        var id_tamu = $('input[type="hidden"]#id_tamu').val();

        $.ajax({
            url: base_url + "/tamu/update/" + id_tamu,
            type: "PUT", // harus pakai PUT, krn data di ajax tidak menggunakan $("#edit_form").serialize()
            dataType: "JSON",
            headers: {
                "X-CSRF-Token": $('meta[name="_token"]').attr("content"), //harus mengunakan header token ini, karena tidak menggunakan $("#edit_form").serialize()
            },
            data: {
                ktp: $("#ktp").val(),
                nama: $("#nama").val(),
                alamat: $("#alamat").val(),
                instansi: $("#instansi").val(),
                id_tamu: $("#id_tamu").val(),
                file_foto_edit: $("#file_foto_edit").attr("src"),
            },
            beforeSend: function () {
                but.text("Updating..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#editModal").modal("hide");
                    getTamu();
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
                    $("#warning-edit")
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

    /********************************************************** D E L E T E   E K S P E D I S I **********************************************************/
    // get Value Field Delete tamu
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_tamu = $(this).data("id_tamu");
        if (id_tamu) {
            $.ajax({
                url: base_url + "/tamu/getTamu",
                method: "GET",
                data: {
                    id_tamu: id_tamu,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $("b.ktp").text(data.ktp);
                    $("b.nama").text(data.nama);
                    $(".id_tamu").val(data.id_tamu);
                    $(".ktp").val(data.ktp);
                    $(".nama").val(data.nama);
                    $(".file_foto").val(data.file_foto);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete tamu
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_tamu = $('input[type="hidden"].id_tamu').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/tamu/delete/" + id_tamu,
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
                    getTamu();
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
