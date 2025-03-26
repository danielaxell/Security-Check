$(document).ready(function () {
    /********************************************************** L O A D   D I V I S I **********************************************************/
    getDivisi();

    //simpan Add Divisi
    function getDivisi() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            destroy: true,
            ajax: {
                url: base_url + "/divisi/getDivisi",
                type: "GET",
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "nama_terminal" },
                { data: "kd_divisi" },
                { data: "nama_divisi" },
            ],
            columnDefs: [
                {
                    targets: 4,
                    data: "id_divisi",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-id_divisi="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_divisi="' +
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
    }

    /********************************************************** A D D   D I V I S I **********************************************************/
    // validasi inputan Add divisi
    $("#insert_form").validate({
        rules: {
            kd_divisi: {
                required: true,
                maxlength: 10,
                alphanumeric: true,
            },
            nama_divisi: {
                required: true,
                alphanumericspace: true,
            },
        },
        messages: {
            kd_divisi: {
                required: "Kode Divisi wajib diisi.",
                maxlength: "Kode Divisi minimal 10 karakter.",
                alphanumeric:
                    "Kode Divisi hanya boleh berisi huruf, angka, dan underscore.",
            },
            nama_divisi: {
                required: "Nama Divisi wajib diisi.",
                alphanumericspace:
                    "Nama Divisi hanya boleh berisi huruf, angka, dan spasi.",
            },
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
        submitHandler: submitInsertDivisi,
    });

    //simpan Add divisi
    function submitInsertDivisi() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/divisi/store",
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
                    getDivisi();
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

    /********************************************************** E D I T   D I V I S I **********************************************************/
    // get Value Field Edit divisi
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const id_divisi = $(this).data("id_divisi");
        if (id_divisi) {
            $.ajax({
                url: base_url + "/divisi/getDivisi",
                method: "GET",
                data: {
                    id_divisi: id_divisi,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#nm_terminal").val(data.nama_terminal);
                    $("#id_divisi").val(data.id_divisi);
                    $("#nm_divisi").val(data.nama_divisi);
                    $("#kd_divisi").val(data.kd_divisi);
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
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Edit divisi
    $("#edit_form").validate({
        rules: {
            nm_terminal: "required",
            kd_divisi: {
                required: true,
                maxlength: 10,
                alphanumeric: true,
            },
            nama_divisi: {
                required: true,
                alphanumericspace: true,
            },
        },
        messages: {
            nm_terminal: "Terminal wajib diisi.",
            kd_divisi: {
                required: "Kode Divisi wajib diisi.",
                maxlength: "Kode Divisi minimal 10 karakter.",
                alphanumeric:
                    "Kode Divisi hanya boleh berisi huruf, angka, dan underscore.",
            },
            nama_divisi: {
                required: "Nama Divisi wajib diisi.",
                alphanumericspace:
                    "Nama Divisi hanya boleh berisi huruf, angka, dan spasi.",
            },
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
        submitHandler: submitEditDivisi,
    });

    //simpan Edit divisi
    function submitEditDivisi() {
        var but = $("#btn-update");
        var id_divisi = $('input[type="hidden"]#id_divisi').val();

        $.ajax({
            url: base_url + "/divisi/update/" + id_divisi,
            type: "POST",
            dataType: "JSON",
            data: $("#edit_form").serialize(),
            beforeSend: function () {
                but.text("Updating..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#editModal").modal("hide");
                    getDivisi();
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

    /********************************************************** D E L E T E   D I V I S I **********************************************************/

    // get Value Field Delete divisi
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_divisi = $(this).data("id_divisi");
        if (id_divisi) {
            $.ajax({
                url: base_url + "/divisi/getDivisi",
                method: "GET",
                data: {
                    id_divisi: id_divisi,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $(".id_divisi").val(data.id_divisi);
                    $(".nm_terminal").val(data.nama_terminal);
                    $(".nm_divisi").val(data.nama_divisi);
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
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete divisi
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_divisi = $('input[type="hidden"].id_divisi').val();
        console.log(id_divisi);
        event.preventDefault();
        $.ajax({
            url: base_url + "/divisi/delete/" + id_divisi,
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
                    getDivisi();
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

    /********************************************************** A D D  &  E D I T   D I V I S I **********************************************************/
    // placeholder select2 form create&edit divisi
    $(".id_terminal").select2({
        placeholder: "Pilih Terminal",
    });

    // Uppercase Kode Divisi
    $(".kd_divisi").keyup(function () {
        this.value = this.value.toUpperCase();
    });

    // uppercase huruf pertama dari kata
    $(".nm_divisi").keyup(function () {
        var txt = $(this).val();
        // Regex taken from php.js (http://phpjs.org/functions/ucwords:569)
        $(this).val(
            txt.replace(/^(.)|\s(.)/g, function ($1) {
                return $1.toUpperCase();
            })
        );
    });
});
