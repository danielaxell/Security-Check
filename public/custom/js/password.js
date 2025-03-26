$(document).ready(function () {
    // clear data in passwordModal
    $(document).on("click", "#btn-password", function () {
        var inputan = $("input,textarea,select");
        $("#password_form")[0].reset(); // clear isian form
        $(".invalid-feedback").remove(); // hapus validasi error
        $(document).find(inputan).removeClass("is-invalid"); // hapus warna field merah invalid
        $(document).find(inputan).removeClass("is-valid"); // hapus warna field hijau valid
    });

    //simpan data
    function submitChangePassword() {
        var but = $("#btn-upd-pwd");
        $.ajax({
            url: base_url + "/auth/change_pwd",
            type: "POST",
            dataType: "JSON",
            data: $("#password_form").serialize(),
            beforeSend: function () {
                but.text("Changing..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#passwordModal").modal("hide");
                    toastr.success(data.pesan_success);
                } else {
                    toastr.warning(data.pesan_warning);
                    $("#warning-password")
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

    // validasi inputan password saat submit
    $("#password_form").validate({
        rules: {
            old_password: "required",
            password: {
                required: true,
                minlength: 8,
            },
            confirm_password: {
                required: true,
                minlength: 8,
                equalTo: "#password",
            },
        },
        messages: {
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
            divparent = element.next(".input-group-append");
            error.insertAfter(divparent);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: submitChangePassword,
    });
});
