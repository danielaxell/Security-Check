function submitSerahTerimaPaketKeluar() {
    $('#form-serahterima-paketkeluar').validate({
        rules: {
            tgl_serah: {
                required: true
            }
        },
        messages: {
            tgl_serah: {
                required: "Tanggal serah harus diisi"
            }
        },
        submitHandler: function(form) {
            var id = $('#id_paketkeluar').val();
            var formData = new FormData(form);

            // Tambahkan log untuk debugging
            console.log('Form Data:', {
                id: id,
                tgl_serah: formData.get('tgl_serah'),
                foto: formData.get('foto')
            });

            $.ajax({
                url: base_url + '/paketkeluar/serahterima/' + id,
                type: 'POST', // Ubah ke POST
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT', // Tambahkan ini untuk simulasi PUT
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.pesan_success,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function() {
                            $('#modal-serahterima-paketkeluar').modal('hide');
                            table.ajax.reload();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Tambahkan handling error
                    console.error('Error:', xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses data',
                        icon: 'error'
                    });
                }
            });
        }
    });
} 