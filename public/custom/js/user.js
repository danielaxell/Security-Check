var id_divisi;
$(document).ready(function () {
    /********************************************************** L O A D   U S E R **********************************************************/
    getUser();
    //simpan Add user
    function getUser() {
        $(".table-responsive").css("display", "block");
        $("#tabledata").DataTable({
            stateSave: true, //dapat kembali sesuai page terakhir di klik
            destroy: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: base_url + "/user/getUser",
                type: "GET",
                // dataSrc: "",
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                { data: "nama_terminal" },
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
                { data: "email" },
                { data: "nama_divisi" },
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
                    data: "is_user_login",
                    render: function (data, type, row, meta) {
                        var out = "";
                        if (data == "Y") {
                            out = "Ya";
                        } else {
                            out = "Tidak";
                        }
                        return out;
                    },
                },
                {
                    data: "nipp",
                    render: function (data, type, row, meta) {
                        return (
                            '<div class="btn-group">' +
                            '<a href="#" class="btn btn-sm btn-info" id="btn-edit" data-toggle="tooltip" title="Edit" data-toggle="modal" data-target="#editModal" data-nipp="' +
                            data +
                            '">' +
                            '<i class="fas fa-edit"></i>' +
                            "</a>" +
                            '<a href="#" class="btn btn-sm btn-danger" id="btn-delete" data-toggle="tooltip" title="Hapus" data-toggle="modal" data-target="#deleteModal" data-nipp="' +
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

    /********************************************************** A D D   U S E R **********************************************************/
    /********************************************************** C H A N G E   P A S S W O R D **********************************************************/
    $("#change_password_form").validate({
        rules: {
            old_password: "required",
            password: {
                required: true,
                minlength: 3,
            },
            confirm_password: {
                required: true,
                minlength: 3,
                equalTo: "#password"
            },
        },
        messages: {
            old_password: "Password Lama wajib diisi",
            password: {
                required: "Password wajib diisi",
                minlength: "Password minimal 3 karakter",
            },
            confirm_password: {
                required: "Konfirmasi password wajib diisi",
                minlength: "Konfirmasi password minimal 3 karakter",
                equalTo: "Konfirmasi password tidak sama dengan password baru",
            },
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: submitChangePassword,
    });

    function submitChangePassword() {
        var but = $("#btn-change-password");
        $.ajax({
            url: base_url + "/auth/change_pwd",
            type: "PUT",
            dataType: "JSON",
            data: $("#change_password_form").serialize(),
            beforeSend: function () {
                but.text("Changing..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    toastr.success(data.pesan_success);
                    $("#changePasswordModal").modal("hide");
                } else {
                    toastr.warning(data.pesan_warning);
                }
            },
            complete: function () {
                but.text("Change Password"); //change button text
                but.attr("disabled", false); //set button enable
            },
            error: function (xhr, status, error) {
                toastr.error(status + " " + xhr.status + " " + error + " " + xhr.responseText);
            },
        });
    }

    // validasi inputan Add User
    $("#insert_form").validate({
        rules: {
            id_terminal: "required",
            nipp: {
                required: true,
                minlength: 6,
                alphanumeric: true,
            },
            nama: {
                required: true,
                minlength: 3,
                alphanumericspace: true,
            },
            email: {
                required: false,
                email: true,
            },
            id_divisi: "required",
            rec_stat: "required",
        },
        messages: {
            id_terminal: "Terminal wajib diisi.",
            nipp: {
                required: "NIPP wajib diisi.",
                minlength: "NIPP minimal 9 karakter.",
                alphanumeric:
                    "NIPP hanya boleh berisi huruf, angka, dan underscore.",
            },
            nama: {
                required: "Nama wajib diisi.",
                minlength: "Nama minimal 3 karakter.",
                alphanumericspace:
                    "Nama hanya boleh berisi huruf, angka, dan spasi.",
            },
            email: {
                email: "Email tidak valid.",
            },
            id_divisi: "Divisi wajib diisi.",
            rec_stat: "Status wajib diisi.",

            old_password: "Password Lama wajib diisi",
            password: {
                required: "Password wajib diisi",
                minlength: "Password minimal 8 karakter",
            },
            confirm_password: {
                required: "Konfirmasi password wajib diisi",
                minlength: "Konfirmasi password minimal 8 karakter",
                equalTo: "Konfirmasi password tidak sama dengan password baru",
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
        submitHandler: submitInsertUser,
    });

    //simpan Add User
    function submitInsertUser() {
        var but = $("#btn-simpan");
        // console.log($("#insert_form").serialize());
        $.ajax({
            url: base_url + "/user/store",
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
                    getUser();
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

    /********************************************************** E D I T   U S E R **********************************************************/
    // get Value Field Edit User
    $("#tabledata").on("click", "#btn-edit", function () {
        // get data from button edit
        const nipp = $(this).data("nipp");
        if (nipp) {
            $.ajax({
                url: base_url + "/user/getUser",
                method: "GET",
                data: {
                    nipp: nipp,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form Edit
                    $("#id_terminal").val(data.id_terminal).trigger("change");
                    $(".nipp").val(data.nipp);
                    $("#nama").val(data.nama);
                    $("#email").val(data.email);
                    id_divisi = data.id_divisi;
                    $("#rec_stat").val(data.rec_stat);
                    $("#is_user_login")
                        .prop("checked", data.is_user_login)
                        .trigger("change");
                },
            });
        }
        // Call Modal Edit
        $("#editModal").modal("show");
    });

    // validasi inputan Edit User
    $("#edit_form").validate({
        rules: {
            id_terminal: "required",
            nama: {
                required: true,
                minlength: 3,
                alphanumericspace: true,
            },
            email: {
                required: false,
                email: true,
            },
            id_divisi: "required",
            rec_stat: "required",
        },
        messages: {
            id_terminal: "Terminal wajib diisi.",
            nama: {
                required: "Nama wajib diisi.",
                minlength: "Nama minimal 3 karakter.",
                alphanumericspace:
                    "Nama hanya boleh berisi huruf, angka, dan spasi.",
            },
            email: {
                email: "Email tidak valid.",
            },
            id_divisi: "Divisi wajib diisi.",
            rec_stat: "Status wajib diisi.",

            old_password: "Password Lama wajib diisi",
            password: {
                required: "Password wajib diisi",
                minlength: "Password minimal 8 karakter",
            },
            confirm_password: {
                required: "Konfirmasi password wajib diisi",
                minlength: "Konfirmasi password minimal 8 karakter",
                equalTo: "Konfirmasi password tidak sama dengan password baru",
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
        submitHandler: submitEditUser,
    });

    //simpan Edit User
    function submitEditUser() {
        var nipp = $('input[type="hidden"].nipp').val();
        var but = $("#btn-update");
        $.ajax({
            url: base_url + "/user/update/" + nipp,
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
                    getUser();
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

    /********************************************************** D E L E T E   U S E R **********************************************************/
    // get Value Field Delete User
    $("#tabledata").on("click", "#btn-delete", function () {
        // get data from button delete
        const nipp = $(this).data("nipp");
        if (nipp) {
            $.ajax({
                url: base_url + "/user/getUser",
                method: "GET",
                data: {
                    nipp: nipp,
                },
                dataType: "JSON",
                success: function (data) {
                    // Set data to Form delete
                    $(".nipp").val(data.nipp);
                    $(".nama").val(data.nama);
                },
            });
        }
        // Call Modal delete
        $("#deleteModal").modal("show");
    });

    //simpan delete User
    $("#delete_form").on("submit", function (event) {
        var but = $("#btn_delete");
        var nipp = $('input[type="hidden"].nipp').val();
        event.preventDefault();
        $.ajax({
            url: base_url + "/user/delete/" + nipp,
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
                    getUser();
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

    /********************************************************** A D D  &  E D I T   U S E R **********************************************************/
    // placeholder select2 form create&edit user
    $(".id_terminal").select2({
        placeholder: "Pilih Terminal",
    });
    $(".id_divisi").select2({
        placeholder: "Pilih Divisi",
    });

    // memunculkan dropdown Divisi ktk terminal dipilih
    $(document).on("change", ".id_terminal", function () {
        var id_terminal = $(this).val();
        if (id_terminal) {
            $.ajax({
                url: base_url + "/divisi/getListDivisi",
                method: "GET",
                data: "id_terminal=" + id_terminal,
                success: function (data) {
                    $(".id_divisi").html(
                        '<option value="">Pilih Divisi</option>'
                    );
                    var dataObj = jQuery.parseJSON(data);
                    if (dataObj) {
                        $(dataObj).each(function () {
                            var option = $("<option>");
                            option
                                .attr("value", this.id_divisi)
                                .text(this.nama_divisi);
                            $(".id_divisi").append(option);
                        });
                        if (id_divisi) {
                            $(".id_divisi").val(id_divisi).trigger("change");
                        }
                    } else {
                        $(".id_divisi").html(
                            '<option value="">Divisi Tidak Ada</option>'
                        );
                    }
                },
            });
        } else {
            $(".id_divisi").html('<option value="">Pilih Divisi</option>');
        }
    });
});

// Uppercase Nama Pegawai
$(".nama").keyup(function () {
    this.value = this.value.toUpperCase();
});
