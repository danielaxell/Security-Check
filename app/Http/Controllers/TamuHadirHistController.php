<?php

namespace App\Http\Controllers;

use App\Models\TamuHadirHist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TamuHadirHistController extends Controller
{
    private $tamuhadirhist_model;
    private $tamuhadirhist;
    private $data;

    public function __construct()
    {
        $this->tamuhadirhist_model = new TamuHadirHist();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Histori Kehadiran Tamu';
        echo view('tamuhadirhist.index', $this->data);
    }

    public function getTamuHadir(Request $request)
    {
        if ($request->id_tamuhadir) {
            $this->tamuhadirhist = $this->tamuhadirhist_model->getTamuHadir($request->id_tamuhadir);
        } elseif ($request->tgl_cari) {
            //rubah format tanggal jadi Y-m-d
            $tgl_awal = date("Y-m-d", strtotime(str_replace('/', '-', substr($request->tgl_cari, 0, 10))));
            $tgl_akhir = date("Y-m-d", strtotime(str_replace('/', '-', substr($request->tgl_cari, 13, 10))));
            $this->tamuhadirhist = $this->tamuhadirhist_model->getTamuHadir(false, $tgl_awal, $tgl_akhir);
        }
        return json_encode($this->tamuhadirhist);
    }

    public function getVisitorStats(Request $request)
    {
        try {
            $query = DB::table('TB_TAMUHADIR_HIST')
                ->select(
                    DB::raw('TRUNC(TGL_DATANG) as tanggal'),
                    DB::raw('COUNT(*) as jumlah')
                );

            // Filter tanggal jika ada
            if ($request->has('start') && $request->has('end')) {
                $query->whereBetween(DB::raw('TRUNC(TGL_DATANG)'), [
                    $request->start, 
                    $request->end
                ]);
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
                if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                    $formattedData['labels'][] = $date->isoFormat('dddd, D MMM');
                    $formattedData['values'][] = $stat->jumlah;
                }
            }

            return response()->json($formattedData);

        } catch (\Exception $e) {
            \Log::error('Error in getVisitorStats: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
