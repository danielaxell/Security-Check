$(document).ready(function () {
    /********************************************************** L O A D   M A P   U S E R   R O L E **********************************************************/
    getUserRole();
    //simpan Add userRole
    function getUserRole() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            destroy: true,
            ajax: {
                url: base_url + "/mapuserrole/getUserRole",
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
                { data: "nipp" },
                { data: "nama" },
                { data: "nama_role" },
            ],
            columnDefs: [
                {
                    targets: 5,
                    data: "id_user_role",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-id_user_role="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_user_role="' +
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

    /********************************************************** A D D   M A P U S E R R O L E **********************************************************/
    // select2 nipp dengan seacrh
    $(".nipp").select2({
        ajax: {
            url: base_url + "/user/getListUserAktif",
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
        placeholder: "Masukkan Nama Pegawai / NIPP",
        minimumInputLength: 3,
    });

    // validasi inputan Add Map User-Role
    $("#insert_form").validate({
        rules: {
            nipp: "required",
            id_role: "required",
        },
        messages: {
            nipp: "NIPP wajib diisi.",
            id_role: "Role wajib diisi.",
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
        submitHandler: submitInsertMapUserRole,
    });

    //simpan Add Map User-Role
    function submitInsertMapUserRole() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/mapuserrole/store",
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
                    getUserRole();
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

    /********************************************************** E D I T   M A P U S E R R O L E **********************************************************/
    // get Value Field Edit Map User-Role
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const id_user_role = $(this).data("id_user_role");
        if (id_user_role) {
            $.ajax({
                url: base_url + "/mapuserrole/getUserRole",
                method: "GET",
                data: {
                    id_user_role: id_user_role,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#id_role").val(data.id_role).trigger("change");
                    $("#nipp").val(data.nipp);
                    $("input[type='hidden']#id_user_role").val(
                        data.id_user_role
                    );
                    $("input[type='hidden']#nipp").val(data.nipp);
                },
            });
        }
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Edit Map User-Role
    $("#edit_form").validate({
        rules: {
            nipp: "required",
            id_role: "required",
        },
        messages: {
            nipp: "NIPP wajib diisi.",
            id_role: "Role wajib diisi.",
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
        submitHandler: submitEditMapUserRole,
    });

    //simpan Edit Map User-Role
    function submitEditMapUserRole() {
        var but = $("#btn-update");
        var id_user_role = $("input[type='hidden']#id_user_role").val();

        $.ajax({
            url: base_url + "/mapuserrole/update/" + id_user_role,
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
                    getUserRole();
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

    /********************************************************** D E L E T E   M A P U S E R R O L E **********************************************************/
    // get Value Field Delete Map User-Role
    $("#tabledata").on("click", "#btn-delete", function () {
        const id_user_role = $(this).data("id_user_role");
        if (id_user_role) {
            $.ajax({
                url: base_url + "/mapuserrole/getUserRole",
                method: "GET",
                data: {
                    id_user_role: id_user_role,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $(".nipp_del").val(data.nipp);
                    $(".nama_role").val(data.nama_role);
                    $("input[type='hidden'].id_user_role").val(
                        data.id_user_role
                    );
                    $("input[type='hidden'].nipp_del").val(data.nipp);
                    $("input[type='hidden'].nama_role").val(data.nama_role);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete Map User-Role
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_user_role = $('input[type="hidden"].id_user_role').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/mapuserrole/delete/" + id_user_role,
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
                    getUserRole();
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

    /********************************************************** A D D  &  E D I T   M A P U S E R R O L E **********************************************************/
    // placeholder select2 form create&edit user
    $(".id_role").select2({
        placeholder: "Pilih Role",
    });
});
