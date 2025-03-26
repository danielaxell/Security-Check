@extends('_layout')
@section('main_view')

<!-- Diagram Statistik -->
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">Statistik Tamu Harian</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="width: auto;">
                            <input type="date" id="startDate" class="form-control" style="width: 120px;">
                        </div>
                        <div class="input-group input-group-sm" style="width: auto;">
                            <input type="date" id="endDate" class="form-control" style="width: 120px;">
                        </div>
                        <button id="filterBtn" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div style="height: 230px;">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Histori Kehadiran Tamu</h4>
                <hr>
                <form id="cari_form" action="#" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="float-right">Range Tanggal</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="tgl_cari" class="form-control tgl_cari rangedate"
                                        placeholder="Format (DD/MM/YYYY)" autocomplete="off" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="ti-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <button type="reset" class="btn waves-effect waves-light btn-secondary">Reset</button>
                            <button type="submit" class="btn waves-effect waves-light btn-primary"
                                id="btn-cari">Search</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive" style="display:none">
                    <table id="tabledata" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Terminal</th>
                                <th>Divisi</th>
                                <th>NIPP</th>
                                <th>Nama Pegawai</th>
                                <th>No.KTP Tamu</th>
                                <th>Nama Tamu</th>
                                <th>Tanggal Datang</th>
                                <th>Tanggal Pulang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal View -->
<div class="modal fade" id="viewModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Kehadiran Tamu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('tamuhadirhist.view')
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ url('custom/js/tamuhadirhist.js') }}"></script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
<script>
let visitorChart = null;

const hariIndonesia = {
    'Sunday': 'Min',
    'Monday': 'Sen',
    'Tuesday': 'Sel',
    'Wednesday': 'Rab',
    'Thursday': 'Kam',
    'Friday': 'Jum',
    'Saturday': 'Sab'
};

function setDefaultDates() {
    const today = new Date();
    const monday = new Date(today);
    monday.setDate(today.getDate() - today.getDay() + 1);
    
    const friday = new Date(today);
    friday.setDate(today.getDate() - today.getDay() + 5);

    document.getElementById('startDate').value = monday.toISOString().split('T')[0];
    document.getElementById('endDate').value = friday.toISOString().split('T')[0];
}

function updateChart() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    if (!startDate || !endDate) {
        alert('Mohon pilih rentang tanggal');
        return;
    }

    fetch(`{{ url("get-visitor-stats") }}?start=${startDate}&end=${endDate}`)
        .then(response => response.json())
        .then(data => {
            if (visitorChart) {
                visitorChart.destroy();
            }

            const indonesianLabels = data.labels.map(label => {
                const [hari, tanggal] = label.split(',');
                const [tgl, bln] = tanggal.trim().split(' ');
                return `${hariIndonesia[hari.trim()]}, ${tgl} ${bln}`;
            });

            const ctx = document.getElementById('visitorChart').getContext('2d');
            visitorChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: indonesianLabels,
                    datasets: [{
                        label: 'Jumlah Tamu',
                        data: data.values,
                        backgroundColor: 'rgba(53, 162, 235, 0.6)',
                        borderColor: 'rgba(53, 162, 235, 1)',
                        borderWidth: 0,
                        borderRadius: 4,
                        barThickness: 35,
                        maxBarThickness: 45
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                fontSize: 11,
                                padding: 5,
                                maxTicksLimit: 5
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontSize: 11,
                                padding: 5,
                                maxRotation: 0
                            },
                            categoryPercentage: 0.8,
                            barPercentage: 0.9
                        }]
                    },
                    layout: {
                        padding: {
                            left: 5,
                            right: 5,
                            top: 15,
                            bottom: 5
                        }
                    },
                    tooltips: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFontSize: 11,
                        bodyFontSize: 11,
                        xPadding: 8,
                        yPadding: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.value + ' Tamu';
                            }
                        }
                    },
                    legend: {
                        display: false
                    },
                    animation: {
                        duration: 500
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data');
        });
}

document.addEventListener('DOMContentLoaded', function() {
    setDefaultDates();
    updateChart();

    document.getElementById('filterBtn').addEventListener('click', updateChart);
});
</script>
@endpush

@endSection