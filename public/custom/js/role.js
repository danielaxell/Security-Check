$(document).ready(function () {
    /********************************************************** L O A D   R O L E **********************************************************/
    getRole();
    //simpan Add role
    function getRole() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            destroy: true,
            ajax: {
                url: base_url + "/role/getRole",
                type: "GET",
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "id_role" },
                { data: "nama_role" },
            ],

            columnDefs: [
                {
                    targets: 3,
                    data: "rec_stat",
                    render: function (data, type, row, meta) {
                        var out = "";
                        if (data == "A") {
                            out = "Aktif";
                        } else {
                            out = "Tidak Aktif";
                        }
                        return out;
                    },
                },
                {
                    targets: 4,
                    data: "id_role",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-success" id="btn-akses" data-toggle="tooltip" title="Setting Akses" data-toggle="modal" data-target="#aksesModal" data-id_role="' +
                            data +
                            '" data-nama_role="' +
                            row.nama_role +
                            '">' +
                            '<i class="fas fa-user-plus"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-id_role="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_role="' +
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

    /********************************************************** A D D   R O L E **********************************************************/
    // validasi inputan Add Role
    $("#insert_form").validate({
        rules: {
            nama_role: "required",
            rec_stat: "required",
        },
        messages: {
            nama_role: "Nama Role wajib diisi.",
            rec_stat: "Status wajib diisi.",
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
        submitHandler: submitInsertRole,
    });

    //simpan Add Role
    function submitInsertRole() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/role/store",
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
                    getRole();
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

    /********************************************************** S E T T I N G   A K S E S   U S E R **********************************************************/
    // get akses user
    $("#tabledata").on("click", "#btn-akses", function () {
        // Set nama_role to form akses
        var nama_role = $(this).data("nama_role");
        $("#nama_role_akses").val(nama_role);
        // get data from button setting akses
        var id_role = $(this).data("id_role");
        var groupColumn = 0;
        var i = 0;
        var table = $("#tabledata_akses").DataTable({
            paging: false,
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            stateSaveParams: function (settings, data) {
                //clear field search
                data.search.search = "";
            },
            stateDuration: -1,
            destroy: true,
            ajax: {
                url: base_url + "/akses/getAkses",
                type: "GET",
                data: function (data) {
                    data.id_role = id_role;
                },
                dataSrc: "",
            },
            columns: [
                { data: "nama_menu", visible: false },
                {
                    render: function (data, type, row, meta) {
                        return `${row.nama_submenu} <input type="hidden" name="id_akses[${i}]" value="${row.id_akses}">`;
                    },
                },
                {
                    data: "c",
                    render: function (data, type, row, meta) {
                        var out = "";
                        if (data == "Y") {
                            out = `<input type="checkbox" name="C[${i}]" value="Y" checked>`;
                        } else {
                            out = `<input type="checkbox" name="C[${i}]" value="Y">`;
                        }
                        return out;
                    },
                },
                {
                    data: "r",
                    render: function (data, type, row, meta) {
                        var out = "";
                        if (data == "Y") {
                            out = `<input type="checkbox" name="R[${i}]" value="Y" checked>`;
                        } else {
                            out = `<input type="checkbox" name="R[${i}]" value="Y">`;
                        }
                        return out;
                    },
                },
                {
                    data: "u",
                    render: function (data, type, row, meta) {
                        var out = "";
                        if (data == "Y") {
                            out = `<input type="checkbox" name="U[${i}]" value="Y" checked>`;
                        } else {
                            out = `<input type="checkbox" name="U[${i}]" value="Y">`;
                        }
                        return out;
                    },
                },
                {
                    data: "d",
                    render: function (data, type, row, meta) {
                        var out = "";
                        if (data == "Y") {
                            out = `<input type="checkbox" name="D[${i}]" value="Y" checked>`;
                        } else {
                            out = `<input type="checkbox" name="D[${i}]" value="Y">`;
                        }
                        i++;
                        return out;
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
        $("#tabledata_akses tbody").on("click", "tr.group", function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === groupColumn && currentOrder[1] === "asc") {
                table.order([groupColumn, "desc"]).draw();
            } else {
                table.order([groupColumn, "asc"]).draw();
            }
        });
        // Call Modal Akses
        $("#aksesModal").modal("show");
    });

    //simpan data dari form akses
    $("#akses_form").on("submit", function (event) {
        var but = $("#btn-update-akses");
        event.preventDefault();
        $.ajax({
            url: base_url + "/akses/update",
            type: "POST",
            dataType: "JSON",
            data: $("#akses_form").serialize(),
            beforeSend: function () {
                but.text("Updating..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#aksesModal").modal("hide");
                    getRole();
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
    });

    /********************************************************** E D I T   R O L E **********************************************************/
    // get Edit Role
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const id_role = $(this).data("id_role");
        if (id_role) {
            $.ajax({
                url: base_url + "/role/getRole",
                method: "GET",
                data: {
                    id_role: id_role,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#id_role").val(data.id_role);
                    $("#nama_role").val(data.nama_role);
                    $("#rec_stat").val(data.rec_stat).trigger("change");
                },
            });
        }
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Edit Role
    $("#edit_form").validate({
        rules: {
            id_role: "required",
            nama_role: "required",
            rec_stat: "required",
        },
        messages: {
            id_role: "ID Role Tidak Boleh dirubah/kosong.",
            nama_role: "Nama Role wajib diisi.",
            rec_stat: "Status wajib diisi.",
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
        submitHandler: submitEditRole,
    });

    //simpan Edit Role
    function submitEditRole() {
        var but = $("#btn-update");
        var id_role = $("input#id_role").val();

        $.ajax({
            url: base_url + "/role/update/" + id_role,
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
                    getRole();
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

    /********************************************************** D E L E T E   R O L E **********************************************************/
    // get Delete Role
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_role = $(this).data("id_role");
        if (id_role) {
            $.ajax({
                url: base_url + "/role/getRole",
                method: "GET",
                data: {
                    id_role: id_role,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $(".id_role").val(data.id_role);
                    $(".nama_role").val(data.nama_role);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete Role
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_role = $('input[type="hidden"].id_role').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/role/delete/" + id_role,
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
                    getRole();
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
