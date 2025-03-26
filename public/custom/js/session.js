$(document).ready(function () {
    // clear data in sessionModal & memunculkan daftar terminal
    $("#btn-session").on("click", function () {
        var inputan = $("input,textarea,select");
        $("#session_form")[0].reset(); // clear isian form
        $(".select2").val(null).change(); //clear isian select2
        $(".invalid-feedback").remove(); // hapus validasi error
        $(document).find(inputan).removeClass("is-invalid"); // hapus warna field merah invalid
        $(document).find(inputan).removeClass("is-valid"); // hapus warna field hijau valid

        $.ajax({
            url: base_url + "/auth/getTerminal",
            method: "GET",
            success: function (data) {
                $("#id_terminal_session").html('<option value=""></option>');
                var dataObj = jQuery.parseJSON(data);
                if (dataObj) {
                    $(dataObj).each(function () {
                        var option = $("<option>");
                        option
                            .attr("value", this.id_terminal)
                            .text(this.nama_terminal);
                        $("#id_terminal_session").append(option);
                    });
                } else {
                    $("#id_terminal_session").html(
                        '<option value=""></option>'
                    );
                }
            },
        });
    });

    // memunculkan daftar divisi
    $("#id_terminal_session").change(function () {
        var id_terminal = $(this).val();
        if (id_terminal) {
            $.ajax({
                url: base_url + "/divisi/getListDivisi",
                method: "GET",
                data: "id_terminal=" + id_terminal,
                success: function (data) {
                    $("#id_divisi_session").html('<option value=""></option>');
                    var dataObj = jQuery.parseJSON(data);
                    if (dataObj) {
                        $(dataObj).each(function () {
                            var option = $("<option>");
                            option
                                .attr("value", this.id_divisi)
                                .text(this.nama_divisi);
                            $("#id_divisi_session").append(option);
                        });
                    } else {
                        $("#id_divisi_session").html(
                            '<option value=""></option>'
                        );
                    }
                },
            });
        } else {
            $("#id_divisi_session").html('<option value=""></option>');
        }
    });

    // placeholder select2 form update session
    $("#id_terminal_session").select2({
        placeholder: "Pilih Terminal",
    });
    $("#id_divisi_session").select2({
        placeholder: "Pilih Divisi",
    });

    // validasi inputan session saat submit
    $("#session_form").validate({
        rules: {
            id_terminal: "required",
            id_divisi: "required",
        },
        messages: {
            id_terminal: "Terminal wajib diisi",
            id_divisi: "Divisi wajib diisi",
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
        submitHandler: submitChangeSession,
    });

    //simpan data dari form update session
    function submitChangeSession() {
        var but = $("#btn-upd-sess");
        $.ajax({
            url: base_url + "/auth/change_session",
            type: "POST",
            dataType: "JSON",
            data: $("#session_form").serialize(),
            beforeSend: function () {
                but.text("Changing..."); //change button text
                but.attr("disabled", true); //set button disable
            },
            success: function (data) {
                if (data.status) {
                    //if success close modal
                    $("#sessionModal").modal("hide");
                    window.location.href = base_url + "/dashboard";
                    toastr.success(data.pesan_success);
                } else {
                    toastr.warning(data.pesan_warning);
                    $("#warning-session")
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
});
