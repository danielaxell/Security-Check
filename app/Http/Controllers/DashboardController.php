<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use function App\Helpers\cek_kanpus;
use function App\Helpers\cek_regional;
use function App\Helpers\cek_sessionMenu;

class DashboardController extends Controller
{
    private $sess;
    private $dashboard_model;

    public function __construct()
    {
        $this->dashboard_model = new Dashboard();
    }

    public function index(Request $request)
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        //cek apakah punya role ganti session
        $this->sess = cek_sessionMenu($request->session()->get('id_role'));
        if ($this->sess == true) { // jika ya, tampilkan semua data 
            if (cek_regional() == true) { //jika regional
                $data['paketmasuks'] = $this->dashboard_model->getCountPaketMasukRegional(session()->get('kd_cabang'), session()->get('kd_terminal'));
                $data['paketkeluars'] = $this->dashboard_model->getCountPaketKeluarRegional(session()->get('kd_cabang'), session()->get('kd_terminal'));
                $data['tamuhadir'] = $this->dashboard_model->getCountTamuHadirRegional(session()->get('kd_cabang'), session()->get('kd_terminal'));
            } else { //jika bukan regional, kanpus
                if (cek_kanpus() == true) {
                    $data['paketmasuks'] = $this->dashboard_model->getCountPaketMasuk();
                    $data['paketkeluars'] = $this->dashboard_model->getCountPaketKeluar();
                    $data['tamuhadir'] = $this->dashboard_model->getCountTamuHadir();
                } else {
                    $data['paketmasuks'] = $this->dashboard_model->getCountPaketMasuk(session()->get('kd_cabang'), session()->get('kd_terminal'));
                    $data['paketkeluars'] = $this->dashboard_model->getCountPaketKeluar(session()->get('kd_cabang'), session()->get('kd_terminal'));
                    $data['tamuhadir'] = $this->dashboard_model->getCountTamuHadir(session()->get('kd_cabang'), session()->get('kd_terminal'));
                }
            }
        } else { // jika tidak, tampilkan data sesuai terminalnya
            $data['paketmasuks'] = $this->dashboard_model->getCountPaketMasuk(session()->get('kd_cabang'), session()->get('kd_terminal'));
            $data['paketkeluars'] = $this->dashboard_model->getCountPaketKeluar(session()->get('kd_cabang'), session()->get('kd_terminal'));
            $data['tamuhadir'] = $this->dashboard_model->getCountTamuHadir(session()->get('kd_cabang'), session()->get('kd_terminal'));
        }

        $data["title"] = 'Dashboard';
        echo view('dashboard.index', $data);
    }

    public function getVisitorStats(Request $request)
    {
        try {
            $startDate = $request->get('start');
            $endDate = $request->get('end');

            $query = DB::table('TB_TAMUHADIR_HIST')
                ->select(
                    DB::raw('TRUNC(TGL_DATANG) as tanggal'),
                    DB::raw('COUNT(*) as jumlah')
                );

            // Tambah filter tanggal jika ada
            if ($startDate && $endDate) {
                $query->whereBetween(DB::raw('TRUNC(TGL_DATANG)'), [$startDate, $endDate]);
            }

            $stats = $query->groupBy(DB::raw('TRUNC(TGL_DATANG)'))
                ->orderBy('tanggal')
                ->get();

            $formattedData = [
                'labels' => [],
                'values' => []
            ];

            foreach ($stats as $stat) {
                $date = Carbon::parse($stat->tanggal);
                // Hanya ambil weekday
                if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                    $formattedData['labels'][] = $date->isoFormat('dddd, D MMM Y');
                    $formattedData['values'][] = $stat->jumlah;
                }
            }

            return response()->json($formattedData);

        } catch (\Exception $e) {
            \Log::error('Error in getVisitorStats: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
