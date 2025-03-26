<?php

namespace App\Http\Controllers;

use App\Models\PaketMasukHist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaketMasukHistController extends Controller
{
    private $paketmasukhist_model;
    private $paketmasukhists;
    private $data;

    public function __construct()
    {
        $this->paketmasukhist_model = new PaketMasukHist();
    }


    public function index()
    {
        // cek apakah sdh login, cek nipp dan role
        // is_logged_in();

        $this->data['title'] = 'Histori Paket Masuk';
        echo view('paketmasukhist.index', $this->data);
    }

    // get info paket masuk
    public function getPaketMasuk(Request $request)
    {
        if ($request->id_paketmasuk) {
            $this->paketmasukhists = $this->paketmasukhist_model->getPaketMasuk($request->id_paketmasuk);
        } elseif ($request->tgl_cari) {
            //rubah format tanggal jadi Y-m-d
            $tgl_awal = date("Y-m-d", strtotime(str_replace('/', '-', substr($request->tgl_cari, 0, 10))));
            $tgl_akhir = date("Y-m-d", strtotime(str_replace('/', '-', substr($request->tgl_cari, 13, 10))));
            $this->paketmasukhists = $this->paketmasukhist_model->getPaketMasuk(false, $tgl_awal, $tgl_akhir);
        }
        return json_encode($this->paketmasukhists);
    }

    public function getPaketStats(Request $request)
    {
        try {
            $query = DB::table('TB_PAKETMASUK_HIST')
                ->select(
                    DB::raw('TRUNC(TGL_CREATED) as tanggal'),
                    DB::raw('COUNT(*) as jumlah')
                );

            // Filter tanggal jika ada
            if ($request->has('start') && $request->has('end')) {
                $query->whereBetween(DB::raw('TRUNC(TGL_CREATED)'), [
                    $request->start, 
                    $request->end
                ]);
            }

            $stats = $query->groupBy(DB::raw('TRUNC(TGL_CREATED)'))
                ->orderBy('tanggal')
                ->get();

            // Debug
            \Log::info('Query result:', ['stats' => $stats]);

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
            \Log::error('Error in getPaketStats: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
