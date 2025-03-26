$(document).ready(function () {
    /********************************************************** L O A D   K A R T U   A K S E S **********************************************************/
    getKartuAkses();

    //simpan Add kartuakses
    function getKartuAkses() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            destroy: true,
            ajax: {
                url: base_url + "/kartuakses/getKartuAkses",
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
                { data: "no_kartuakses" },
                { data: "nama_kartuakses" },
            ],
            columnDefs: [
                {
                    targets: 4,
                    data: "id_kartuakses",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-id_kartuakses="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_kartuakses="' +
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

    /********************************************************** A D D   K A R T U   A K S E S **********************************************************/
    // validasi inputan Add kartuakses
    $("#insert_form").validate({
        rules: {
            no_kartuakses: {
                required: true,
                maxlength: 20,
                alphanumeric: true,
            },
            nama_kartuakses: "required",
        },
        messages: {
            no_kartuakses: {
                required: "No Kartu Akses wajib diisi.",
                maxlength: "No Kartu Akses maksimal 20 karakter.",
                alphanumeric: "No Kartu Akses hanya boleh huruf dan angka",
            },
            nama_kartuakses: "Nama Kartu Akses wajib diisi.",
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
        submitHandler: submitInsertKartuAkses,
    });

    //simpan Add kartuakses
    function submitInsertKartuAkses() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/kartuakses/store",
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
                    getKartuAkses();
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

    /********************************************************** D E L E T E   K A R T U   A K S E S **********************************************************/

    // get Value Field Delete kartuakses
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_kartuakses = $(this).data("id_kartuakses");
        if (id_kartuakses) {
            $.ajax({
                url: base_url + "/kartuakses/getKartuAkses",
                method: "GET",
                data: {
                    id_kartuakses: id_kartuakses,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $("b.nama_terminal").text(data.nama_terminal);
                    $("b.no_kartuakses").text(data.no_kartuakses);
                    $(".id_kartuakses").val(data.id_kartuakses);
                    $(".no_kartuakses").val(data.no_kartuakses);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete kartuakses
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_kartuakses = $('input[type="hidden"].id_kartuakses').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/kartuakses/delete/" + id_kartuakses,
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
                    getKartuAkses();
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

    /********************************************************** E D I T   K A R T U   A K S E S **********************************************************/
    // get Value Field Edit kartu akses
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const id_kartuakses = $(this).data("id_kartuakses");
        if (id_kartuakses) {
            $.ajax({
                url: base_url + "/kartuakses/getKartuAkses",
                method: "GET",
                data: {
                    id_kartuakses: id_kartuakses,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#nama_terminal").text(data.nama_terminal);
                    $("#no_kartuakses").val(data.no_kartuakses);
                    $("#nama_kartuakses").val(data.nama_kartuakses);
                    $("input[type='hidden']#id_kartuakses").val(
                        data.id_kartuakses
                    );
                },
            });
        }
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Edit kartu akses
    $("#edit_form").validate({
        rules: {
            no_kartuakses: {
                required: true,
                maxlength: 20,
                alphanumeric: true,
            },
            nama_kartuakses: "required",
        },
        messages: {
            no_kartuakses: {
                required: "No Kartu Akses wajib diisi.",
                maxlength: "No Kartu Akses maksimal 20 karakter.",
                alphanumeric: "No Kartu Akses hanya boleh huruf dan angka",
            },
            nama_kartuakses: "Nama Kartu Akses wajib diisi.",
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
        submitHandler: submitEditKartuAkses,
    });

    //simpan Edit kartu akses
    function submitEditKartuAkses() {
        var but = $("#btn_delete");
        var id_kartuakses = $('input[type="hidden"]#id_kartuakses').val();

        $.ajax({
            url: base_url + "/kartuakses/update/" + id_kartuakses,
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
                    getKartuAkses();
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

    /********************************************************** A D D  &  E D I T   K A R T U   A K S E S **********************************************************/
    // Uppercase No Kartu Akses
    $(".no_kartuakses").keyup(function () {
        this.value = this.value.toUpperCase();
    });

    // uppercase huruf pertama dari kata
    $(".nama_kartuakses").keyup(function () {
        var txt = $(this).val();
        // Regex taken from php.js (http://phpjs.org/functions/ucwords:569)
        $(this).val(
            txt.replace(/^(.)|\s(.)/g, function ($1) {
                return $1.toUpperCase();
            })
        );
    });
});
