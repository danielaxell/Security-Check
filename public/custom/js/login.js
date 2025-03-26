$(document).ready(function () {
    //simpan data
    function submitLogin() {
        var but = $("#btn-login");
        $.ajax({
            url: base_url + "/auth/akses",
            type: "POST",
            dataType: "JSON",
            data: $("#login_form").serialize(),
            beforeSend: function () {
                var loader = base_url + "/assets/images/btn-ajax-loader.gif";
                but.html('<img src="' + loader + '" /> Signing In ...'); //change button text
                but.attr("disabled", true); //set button disabl e
            },
            success: function (data) {
                if (data.status) {
                    window.location.href = base_url + "/auth/pilih_role";
                } else {
                    toastr.error(data.pesan_error);
                }
            },
            complete: function () {
                setTimeout(function () {
                    but.html('<i class="fa fa-sign-in-alt"></i> &nbsp;Sign In'); //change button text
                    but.attr("disabled", false); //set button enable
                }, 500);
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

    // validasi inputan login saat submit
    $("#login_form").validate({
        rules: {
            nipp: "required",
            password: "required",
        },
        messages: {
            nipp: "Username wajib diisi.",
            password: "Password wajib diisi.",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: submitLogin,
    });
});
