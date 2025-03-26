$(document).ready(function () {
    //simpan data
    function submitRole() {
        var but = $("#btn-role");
        $.ajax({
            url: base_url + "/auth/proses_login",
            type: "POST",
            dataType: "JSON",
            data: $("#role_form").serialize(),
            beforeSend: function () {
                var loader = base_url + "/assets/images/btn-ajax-loader.gif";
                but.html('<img src="' + loader + '" /> Signing In ...'); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    window.location.href = base_url + "/dashboard";
                }
            },
            complete: function () {
                setTimeout(function () {
                    but.text("Pilih Role"); //change button text
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
    $("#role_form").validate({
        rules: {
            id_role: "required",
        },
        messages: {
            id_role: "Pilih Salah Satu Role.",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            $("span#validate_role").append(error);
        },
        submitHandler: submitRole,
    });
});
