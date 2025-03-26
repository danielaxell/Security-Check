$(document).ready(function () {
    /********************************************************** L O A D   S U B   M E N U **********************************************************/
    getSubmenu();

    //simpan Add Sub Menu
    function getSubmenu() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            destroy: true,
            ajax: {
                url: base_url + "/submenu/getSubmenu",
                type: "GET",
                dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "urutan" },
                { data: "id_submenu" },
                { data: "nama_submenu" },
                { data: "url" },
                { data: "icon" },
                {
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
                    data: "id_submenu",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-id_submenu="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-id_submenu="' +
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
        getMaxUrutanSubmenu();
    }

    // get Max Uturan Submenu
    function getMaxUrutanSubmenu() {
        $.ajax({
            url: base_url + "/submenu/getMaxUrutanSubmenu",
            method: "GET",
            dataType: "JSON",
            success: function (data) {
                $("span.max_urutan").text(data.max_urutan);
            },
        });
    }

    /********************************************************** A D D   S U B   M E N U **********************************************************/
    // validasi inputan Add Sub Menu
    $("#insert_form").validate({
        rules: {
            urutan: "required",
            nama_submenu: "required",
            icon: "required",
            rec_stat: "required",
            url: "required",
        },
        messages: {
            urutan: "Urutan Sub Menu wajib diisi.",
            nama_submenu: "Nama Sub Menu wajib diisi.",
            icon: "Icon wajib diisi.",
            rec_stat: "Status wajib diisi.",
            url: "Url wajib diisi.",
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
        submitHandler: submitInsertMenu,
    });

    //simpan Add Sub Menu
    function submitInsertMenu() {
        var but = $("#btn-simpan");
        $.ajax({
            url: base_url + "/submenu/store",
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
                    getSubmenu();
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

    /********************************************************** E D I T   S U B   M E N U **********************************************************/
    // get Value Field Edit Sub Menu
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const id_submenu = $(this).data("id_submenu");
        if (id_submenu) {
            $.ajax({
                url: base_url + "/submenu/getSubmenu",
                method: "GET",
                data: {
                    id_submenu: id_submenu,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#urutan").val(data.urutan);
                    $("#id_submenu").val(data.id_submenu);
                    $("#nama_submenu").val(data.nama_submenu);
                    $("#url").val(data.url);
                    $("#icon").val(data.icon);
                    $("#rec_stat").val(data.rec_stat).trigger("change");
                },
            });
        }
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Add Sub Menu
    $("#edit_form").validate({
        rules: {
            id_submenu: "required",
            urutan: "required",
            nama_submenu: "required",
            icon: "required",
            rec_stat: "required",
            url: "required",
        },
        messages: {
            id_submenu: "ID Sub Menu Tidak Boleh dirubah/kosong.",
            urutan: "Urutan Sub Menu wajib diisi.",
            nama_submenu: "Nama Sub Menu wajib diisi.",
            icon: "Icon wajib diisi.",
            rec_stat: "Status wajib diisi.",
            url: "Url wajib diisi.",
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
        submitHandler: submitEditMenu,
    });

    //simpan Edit Sub Menu
    function submitEditMenu() {
        var but = $("#btn-update");
        var id_submenu = $("input#id_submenu").val();

        $.ajax({
            url: base_url + "/submenu/update/" + id_submenu,
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
                    getSubmenu();
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

    /********************************************************** D E L E T E   S U B   M E N U **********************************************************/
    // get Value Field Delete Sub Menu
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const id_submenu = $(this).data("id_submenu");
        if (id_submenu) {
            $.ajax({
                url: base_url + "/submenu/getSubmenu",
                method: "GET",
                data: {
                    id_submenu: id_submenu,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $(".id_submenu").val(data.id_submenu);
                    $(".nama_submenu").val(data.nama_submenu);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete Sub Menu
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var id_submenu = $('input[type="hidden"].id_submenu').val();

        event.preventDefault();
        $.ajax({
            url: base_url + "/submenu/delete/" + id_submenu,
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
                    getSubmenu();
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
