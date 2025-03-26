$(document).ready(function () {
    /********************************************************** L O A D   M A P   U S E R   R O L E **********************************************************/
    getMenuRole();
    //simpan Add menuRole
    function getMenuRole() {
        var groupColumn = 1;
        $(".table-responsive").css("display", "block");
        var table = $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            stateDuration: -1, //tambahan config agar statesave dapat digunakan untuk visible: false column
            destroy: true,
            ajax: {
                url: base_url + "/mapmenurole/getMenuRole",
                type: "GET",
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "id_nama_role", visible: false },
                { data: "id_menu" },
                { data: "nama_menu" },
                {
                    data: "id_menu_role",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-id_menu_role="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_menu_role="' +
                            data +
                            '">' +
                            '<i class="fa fa-trash-alt"></i>' +
                            "</a>" +
                            "</div>"
                        );
                    },
                },
            ],
            order: [[groupColumn, "asc"]],
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: "current" }).nodes();
                var last = null;

                api.column(groupColumn, { page: "current" })
                    .data()
                    .each(function (group, i) {
                        if (last !== group) {
                            $(rows)
                                .eq(i)
                                .before(
                                    '<tr class="group"><td colspan="7">' +
                                        group +
                                        "</td></tr>"
                                );

                            last = group;
                        }
                    });
            },
        });
        // Order by the grouping
        $("#tabledata tbody").on("click", "tr.group", function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === groupColumn && currentOrder[1] === "asc") {
                table.order([groupColumn, "desc"]).draw();
            } else {
                table.order([groupColumn, "asc"]).draw();
            }
        });
    }

    /********************************************************** A D D   M A P M E N U R O L E **********************************************************/
    // validasi inputan Add Map Menu-Role
    $("#insert_form").validate({
        rules: {
            id_menu: "required",
            id_role: "required",
        },
        messages: {
            id_menu: "Menu wajib diisi.",
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
        submitHandler: submitInsertMapMenuRole,
    });

    //simpan Add Map Menu-Role
    function submitInsertMapMenuRole() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/mapmenurole/store",
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
                    getMenuRole();
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

    /********************************************************** E D I T   M A P M E N U R O L E **********************************************************/
    // get Value Field Edit Map Menu-Role
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const id_menu_role = $(this).data("id_menu_role");
        if (id_menu_role) {
            $.ajax({
                url: base_url + "/mapmenurole/getMenuRole",
                method: "GET",
                data: {
                    id_menu_role: id_menu_role,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#nama_role").val(data.nama_role);
                    $("#id_menu").val(data.id_menu).trigger("change");
                    $("input[type='hidden']#id_menu_role").val(
                        data.id_menu_role
                    );
                    $("input[type='hidden']#id_role").val(data.id_role);
                    $("input[type='hidden']#nama_role").val(data.nama_role);
                },
            });
        }
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Edit Map Menu-Role
    $("#edit_form").validate({
        rules: {
            id_menu: "required",
            nama_role: "required",
        },
        messages: {
            id_menu: "Menu wajib diisi.",
            nama_role: "Role wajib diisi.",
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
        submitHandler: submitEditMapMenuRole,
    });

    //simpan Edit Map Menu-Role
    function submitEditMapMenuRole() {
        var but = $("#btn-update");
        var id_menu_role = $('input[type="hidden"]#id_menu_role').val();

        $.ajax({
            url: base_url + "/mapmenurole/update/" + id_menu_role,
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
                    getMenuRole();
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

    /********************************************************** D E L E T E   M A P M E N U R O L E **********************************************************/
    // get Value Field Delete Map Menu-Role
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_menu_role = $(this).data("id_menu_role");
        if (id_menu_role) {
            $.ajax({
                url: base_url + "/mapmenurole/getMenuRole",
                method: "GET",
                data: {
                    id_menu_role: id_menu_role,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $(".nama_role").val(data.nama_role);
                    $(".nama_menu").val(data.nama_menu);
                    $("input[type='hidden'].id_menu_role").val(
                        data.id_menu_role
                    );
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete Map Menu-Role
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_menu_role = $('input[type="hidden"].id_menu_role').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/mapmenurole/delete/" + id_menu_role,
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
                    getMenuRole();
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

    /********************************************************** A D D  &  E D I T   M A P M E N U R O L E **********************************************************/
    // placeholder select2 form create&edit user
    $(".id_menu").select2({
        placeholder: "Pilih Menu",
    });
    $(".id_role").select2({
        placeholder: "Pilih Role",
    });
});
