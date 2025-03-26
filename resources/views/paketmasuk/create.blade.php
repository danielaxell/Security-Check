<style>
    .modal-dialog {
        max-width: 900px;
    }
    .modal-body {
        padding: 20px;
    }
    .camera-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    #my_camera, #result_snapshot img {
        width: 100%;
        max-height: 250px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
let cameraStarted = false;

function startCamera() {
    console.log("🔵 Memulai Kamera...");

    if (typeof Webcam === "undefined") {
        console.error("❌ Webcam.js BELUM DIMUAT!");
        return;
    }

    if (!cameraStarted) {
        console.log("✅ Webcam.js DITEMUKAN, melanjutkan...");

        Webcam.set({
            width: 320,
            height: 240,
            image_format: "jpeg",
            jpeg_quality: 90
        });

        Webcam.attach("#my_camera");
        cameraStarted = true;

        // Debugging: Cek apakah elemen video muncul setelah attach
        setTimeout(() => {
            let videoStream = document.querySelector("#my_camera video");
            console.log(videoStream ? "✅ Video Stream ADA!" : "❌ Video Stream HILANG!");
        }, 1500);
    }
}

$(document).ready(function () {
    $(".select2").select2({
        dropdownParent: $("#insert_form")
    });

    $("#insert_form").on("shown.bs.modal", function () {
        console.log("🟢 Modal DIBUKA");

        // Tunggu modal stabil sebelum memulai kamera
        setTimeout(startCamera, 1000);
    });

    $("#take_snapshot").on("click", function () {
    Webcam.snap(function (data_uri) {
        $("#result_snapshot").html('<img src="' + data_uri + '"/>');
        $("#file_foto").val(data_uri); // Simpan hasil foto ke input hidden
        console.log("📸 Foto berhasil diambil & disimpan di file_foto.");
    });
});


    $("#insert_form").on("hidden.bs.modal", function () {
        if (cameraStarted) {
            Webcam.reset();
            cameraStarted = false;
            console.log("🛑 Kamera telah di-reset.");
        }
    });

    // 🌟 Tambahkan event listener untuk Select2 agar Webcam di-re-attach setiap kali ada perubahan
    $(".select2").on("select2:select", function () {
        console.log("🔄 Select2 dipilih, mengaktifkan kembali kamera...");
        setTimeout(() => {
            Webcam.attach("#my_camera");
        }, 500);
    });
});
</script>



<form id="insert_form" action="#" method="POST">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>NIPP/Nama *</label>
                    <select class="select2 form-control custom-select nipp" id="nipp_input" name="nipp" required></select>
                </div>
                <div class="form-group">
                    <label>Divisi *</label>
                    <select name="id_divisi" class="select2 form-control custom-select id_divisi" required></select>
                </div>
                <div class="form-group">
                <label for="nama_paket">Paket</label>
                <input type="text" name="nama_paket" class="form-control nama_paket" placeholder="Masukkan Paket" required>
                </div>
                <div class="form-group">
                    <label>Ekspedisi *</label>
                    <select name="id_ekspedisi" class="select2 form-control custom-select id_ekspedisi" required>
                        @foreach ($ekspedisies as $ekspedisi)
                        <option value="{{ $ekspedisi->id_ekspedisi }}">{{ $ekspedisi->nm_ekspedisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Terima *</label>
                    <div class="input-group">
                        <input type="text" name="tgl_terima" class="form-control tgl_terima singledateup" placeholder="Format (DD/MM/YYYY)" autocomplete="off" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="ti-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <p><code>*wajib diisi</code></p>
            </div>
            
            <div class="col-md-6 camera-container">
                <label>Camera Preview</label>
                <div id="my_camera"></div>
                <button type="button" class="btn btn-info btn-sm mt-2" id="take_snapshot">
                    <i class="fa fa-camera"></i> Ambil Foto
                </button>
                <label>Hasil Foto</label>
                <div id="result_snapshot"></div>
                <input type="hidden" name="file_foto" id="file_foto">   
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
