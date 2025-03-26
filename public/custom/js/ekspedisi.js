$(document).ready(function () {
    /********************************************************** L O A D   E K P E D I S I **********************************************************/
    getEkspedisi();

    //simpan Add ekspedisi
    function getEkspedisi() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            destroy: true,
            ajax: {
                url: base_url + "/ekspedisi/getEkspedisi",
                type: "GET",
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "nm_ekspedisi" },
                { data: "ket_ekspedisi" },
            ],
            columnDefs: [
                {
                    targets: 3,
                    data: "id_ekspedisi",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-id_ekspedisi="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_ekspedisi="' +
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

    /********************************************************** A D D   E K S P E D I S I **********************************************************/
    // validasi inputan Add Ekspedisi
    $("#insert_form").validate({
        rules: {
            nm_ekspedisi: "required",
        },
        messages: {
            nm_ekspedisi: "Nama Ekspedisi wajib diisi.",
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
        submitHandler: submitInsertEkspedisi,
    });

    //simpan Add Ekspedisi
    function submitInsertEkspedisi() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/ekspedisi/store",
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
                    getEkspedisi();
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

    /********************************************************** E D I T   E K S P E D I S I **********************************************************/
    // get Value Field Edit Ekspedisi
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const id_ekspedisi = $(this).data("id_ekspedisi");
        if (id_ekspedisi) {
            $.ajax({
                url: base_url + "/ekspedisi/getInfoEkspedisi",
                method: "GET",
                data: {
                    id_ekspedisi: id_ekspedisi,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#id_ekspedisi").val(data.id_ekspedisi);
                    $("#nm_ekspedisi").val(data.nm_ekspedisi);
                    $("#ket_ekspedisi").val(data.ket_ekspedisi);
                },
            });
        }
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Edit Ekspedisi
    $("#edit_form").validate({
        rules: {
            nm_ekspedisi: "required",
        },
        messages: {
            nm_ekspedisi: "Nama Ekspedisi wajib diisi.",
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
        submitHandler: submitEditEkspedisi,
    });

    //simpan Edit Ekspedisi
    function submitEditEkspedisi() {
        var but = $("#btn-update");
        var id_ekspedisi = $('input[type="hidden"]#id_ekspedisi').val();

        $.ajax({
            url: base_url + "/ekspedisi/update/" + id_ekspedisi,
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
                    getEkspedisi();
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
        });
    }

    /********************************************************** D E L E T E   E K S P E D I S I **********************************************************/
    // get Value Field Delete ekspedisi
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_ekspedisi = $(this).data("id_ekspedisi");
        if (id_ekspedisi) {
            $.ajax({
                url: base_url + "/ekspedisi/getInfoEkspedisi",
                method: "GET",
                data: {
                    id_ekspedisi: id_ekspedisi,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $(".id_ekspedisi").val(data.id_ekspedisi);
                    $(".nm_ekspedisi").val(data.nm_ekspedisi);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete ekspedisi
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_ekspedisi = $('input[type="hidden"].id_ekspedisi').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/ekspedisi/delete/" + id_ekspedisi,
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
                    getEkspedisi();
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
