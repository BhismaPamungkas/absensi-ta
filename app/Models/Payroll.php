<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jumlahHadir($user_id, $bulan, $tahun, $status)
    {
       return MappingShift::where('user_id', $user_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status_absen', $status)->count();
    }

    public function jumlahTelat($user_id, $bulan, $tahun)
    {
       $telat = MappingShift::where('user_id', $user_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('telat', '>', 0)->count();
       $pulpat = MappingShift::where('user_id', $user_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('pulang_cepat', '>', 0)->count();
       $jumlah = $telat + $pulpat;
       return $jumlah;
    }

    public static function dataPayroll()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');

        $bulan = request()->input('bulan');
        $tahun = request()->input('tahun');

        $data_payroll = Payroll::when($bulan, function ($query) use ($bulan) {
                                return $query->where('bulan', $bulan);
                            })
                            ->when($tahun, function ($query) use ($tahun) {
                                return $query->where('tahun', $tahun);
                            })
                            ->orderBy('no_gaji', 'DESC');

        return $data_payroll;
    }

}
