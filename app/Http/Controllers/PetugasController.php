<?php

namespace App\Http\Controllers;

use App\Models\JadwalDonor;
use App\Models\Pendonor;
use App\Models\RiwayatDonasi;
use App\Models\StokDarah;
use App\Models\StokDarahHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $stokList    = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();
        $jadwalColl  = JadwalDonor::orderBy('tanggal', 'desc')->get();
        $riwayatColl = RiwayatDonasi::with('pendonor')->orderBy('tanggal', 'desc')->get();

        $stokTotal = (int) $stokList->sum('jumlah');
        $today = date('Y-m-d');
        $donasiHariIni = $riwayatColl->filter(fn($r) => $r->tanggal?->format('Y-m-d') === $today)->count();

        $statistics = [
            'pendonor'        => Pendonor::count(),
            'donasi_hari_ini' => $donasiHariIni,
            'stok_total'      => $stokTotal,
            'jadwal'          => $jadwalColl->count(),
        ];

        $jadwalList  = $jadwalColl->all();
        $riwayatList = $riwayatColl->all();

        return view('petugas.dashboard', compact('stokList', 'jadwalList', 'riwayatList', 'statistics'));
    }

    public function daftarPendonor(Request $request)
    {
        $query = Pendonor::query();

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ilike', $search)
                  ->orWhere('nik', 'ilike', $search)
                  ->orWhere('telepon', 'ilike', $search);
            });
        }

        if ($request->filled('golongan')) {
            $gol = trim($request->input('golongan'));
            if (in_array(strtoupper($gol), ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])) {
                $base = substr($gol, 0, -1);
                $rh = substr($gol, -1);
                $query->where('golongan', $base)->where('rhesus', $rh);
            } else {
                $query->where('golongan', $gol);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pendonorList = $query->orderBy('nama')->get();

        return view('petugas.daftar-pendonor', compact('pendonorList'));
    }

    public function formPendonor()
    {
        return view('petugas.form-pendonor');
    }

    public function tambahPendonorProcess(Request $request)
    {
        if ($request->has('nik') && ! $request->has('no_identitas')) {
            $request->merge(['no_identitas' => $request->input('nik')]);
        }
        if ($request->has('no_identitas') && ! $request->has('nik')) {
            $request->merge(['nik' => $request->input('no_identitas')]);
        }

        if ($request->has('golongan_darah')) {
            $rawGol = trim($request->input('golongan_darah'));
            if (in_array(strtoupper($rawGol), ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])) {
                $base = substr($rawGol, 0, -1);
                $rh = substr($rawGol, -1);
                $request->merge([
                    'golongan_darah' => $base,
                    'rhesus'         => $request->filled('rhesus') ? $request->input('rhesus') : $rh,
                ]);
            }
        }

        if ($request->has('email')) {
            $rawEmail = trim((string) $request->input('email'));
            $request->merge(['email' => $rawEmail !== '' ? strtolower($rawEmail) : null]);
        }

        $validator = Validator::make($request->all(), [
            'nama'           => 'required|string|min:3|max:150',
            'no_identitas'   => 'required|string|min:8|max:30|unique:pendonor,nik',
            'golongan_darah' => 'required|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-,a,b,ab,o,a+,a-,b+,b-,ab+,ab-,o+,o-',
            'rhesus'         => 'required|in:+,-',
            'telepon'        => 'required|string|min:6|max:20',
            'email'          => 'nullable|string|email:rfc,filter|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'jenis_kelamin'  => 'nullable|string|max:20',
            'alamat'         => 'nullable|string|max:255',
            'kota'           => 'nullable|string|max:100',
            'provinsi'       => 'nullable|string|max:100',
            'pekerjaan'      => 'nullable|string|max:100',
            'status'         => 'nullable|string|in:aktif,nonaktif',
            'tekanan_darah'  => 'nullable|string|max:20',
            'detak_jantung'  => 'nullable|string|max:20',
            'hemoglobin'     => 'nullable|string|max:20',
            'suhu_tubuh'     => 'nullable|string|max:20',
            'kesehatan_umum' => 'nullable|string|max:50',
            'catatan'        => 'nullable|string',
            'jumlah'         => 'nullable|integer|min:0',
            'status_donasi'  => 'nullable|string|in:Berhasil,Ditolak',
        ], [
            'nama.required'           => 'Nama lengkap pendonor wajib diisi.',
            'no_identitas.required'   => 'Nomor identitas (NIK) wajib diisi.',
            'no_identitas.unique'     => 'NIK pendonor ini sudah terdaftar dalam sistem.',
            'golongan_darah.required' => 'Golongan darah wajib dipilih (A, B, AB, O, atau A+, B+, dll).',
            'rhesus.required'         => 'Rhesus darah wajib dipilih (+ atau -).',
            'telepon.required'        => 'Nomor telepon wajib diisi.',
            'email.email'             => 'Format email tidak valid (contoh: pendonor@gmail.com).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($request) {
                $statusDonasi  = trim($request->input('status_donasi', 'Berhasil'));
                $jumlahKantong = (int) $request->input('jumlah', 1);
                if ($statusDonasi !== 'Berhasil') {
                    $jumlahKantong = 0;
                }

                $tekananDarah = trim($request->input('tekanan_darah')) ?: '120/80';
                $detakJantung = trim($request->input('detak_jantung')) ?: '72';
                $hemoglobin   = trim($request->input('hemoglobin')) ?: '14.5';
                $suhuTubuh    = trim($request->input('suhu_tubuh')) ?: '36.5';
                $kesehatan    = trim($request->input('kesehatan_umum')) ?: 'Sehat Baik';
                $catatan      = trim($request->input('catatan')) ?: null;

                $pendonor = Pendonor::create([
                    'nama'           => trim($request->input('nama')),
                    'nik'            => trim($request->input('no_identitas')),
                    'golongan'       => trim($request->input('golongan_darah')),
                    'rhesus'         => trim($request->input('rhesus')),
                    'telepon'        => trim($request->input('telepon')),
                    'email'          => trim($request->input('email')) ?: null,
                    'tanggal_lahir'  => $request->input('tanggal_lahir') ?: null,
                    'jenis_kelamin'  => trim($request->input('jenis_kelamin')) ?: null,
                    'alamat'         => trim($request->input('alamat')) ?: null,
                    'kota'           => trim($request->input('kota')) ?: null,
                    'provinsi'       => trim($request->input('provinsi')) ?: null,
                    'pekerjaan'      => trim($request->input('pekerjaan')) ?: null,
                    'status'         => $request->input('status') === 'nonaktif' ? 'nonaktif' : 'aktif',
                    'tgl_donor'      => $statusDonasi === 'Berhasil' ? now()->toDateString() : null,
                    'tekanan_darah'  => $tekananDarah,
                    'detak_jantung'  => $detakJantung,
                    'hemoglobin'     => $hemoglobin,
                    'suhu_tubuh'     => $suhuTubuh,
                    'kesehatan_umum' => $kesehatan,
                    'catatan'        => $catatan,
                ]);

                RiwayatDonasi::create([
                    'pendonor_id'    => $pendonor->id,
                    'golongan'       => $pendonor->golongan,
                    'rhesus'         => $pendonor->rhesus,
                    'jumlah'         => $jumlahKantong,
                    'tanggal'        => now()->toDateString(),
                    'tekanan_darah'  => $tekananDarah,
                    'detak_jantung'  => $detakJantung,
                    'hemoglobin'     => $hemoglobin,
                    'suhu_tubuh'     => $suhuTubuh,
                    'kesehatan_umum' => $kesehatan,
                    'catatan'        => $catatan,
                    'status'         => $statusDonasi,
                ]);

                if ($statusDonasi === 'Berhasil' && $jumlahKantong > 0) {
                    $stok = StokDarah::where('golongan_darah', $pendonor->golongan)
                        ->where('rhesus', $pendonor->rhesus)
                        ->lockForUpdate()
                        ->first();

                    if (! $stok) {
                        $stok = StokDarah::create([
                            'golongan_darah' => $pendonor->golongan,
                            'rhesus'         => $pendonor->rhesus,
                            'jumlah'         => 0,
                            'min_stock'      => 10,
                        ]);
                    }

                    $stok->jumlah += $jumlahKantong;
                    $stok->save();

                    StokDarahHistory::create([
                        'golongan'   => $pendonor->golongan,
                        'rhesus'     => $pendonor->rhesus,
                        'masuk'      => $jumlahKantong,
                        'keluar'     => 0,
                        'stok_akhir' => $stok->jumlah,
                        'catatan'    => "Penambahan stok dari donor baru: {$pendonor->nama}",
                    ]);

                    Log::info("Petugas mencatat penambahan stok darah {$pendonor->golongan}{$pendonor->rhesus} (+{$jumlahKantong} kantong) dari pendonor {$pendonor->nama}");
                }

                Log::info("Petugas mendaftarkan pendonor baru: {$pendonor->nama} (NIK: {$pendonor->nik})");
            });

            return redirect()->route('petugas.daftar-pendonor')
                ->with('success', "Pendonor berhasil didaftarkan dan data donasi berhasil dicatat.");
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mendaftarkan pendonor: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat mendaftarkan pendonor.');
        }
    }

    public function editPendonorProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id');
        $pendonor = Pendonor::findOrFail($id);

        if ($request->has('nik') && ! $request->has('no_identitas')) {
            $request->merge(['no_identitas' => $request->input('nik')]);
        }
        if ($request->has('no_identitas') && ! $request->has('nik')) {
            $request->merge(['nik' => $request->input('no_identitas')]);
        }

        if ($request->has('golongan_darah')) {
            $rawGol = trim($request->input('golongan_darah'));
            if (in_array(strtoupper($rawGol), ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])) {
                $base = substr($rawGol, 0, -1);
                $rh = substr($rawGol, -1);
                $request->merge([
                    'golongan_darah' => $base,
                    'rhesus'         => $request->filled('rhesus') ? $request->input('rhesus') : $rh,
                ]);
            }
        }

        if ($request->has('email')) {
            $rawEmail = trim((string) $request->input('email'));
            $request->merge(['email' => $rawEmail !== '' ? strtolower($rawEmail) : null]);
        }

        $validator = Validator::make($request->all(), [
            'nama'           => 'required|string|min:3|max:150',
            'nik'            => "required|string|min:8|max:30|unique:pendonor,nik,{$pendonor->id}",
            'golongan_darah' => 'required|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-,a,b,ab,o,a+,a-,b+,b-,ab+,ab-,o+,o-',
            'rhesus'         => 'required|in:+,-',
            'telepon'        => 'required|string|min:6|max:20',
            'email'          => 'nullable|string|email:rfc,filter|max:100',
            'tanggal_lahir'  => 'nullable|date',
        ], [
            'nama.required' => 'Nama lengkap pendonor wajib diisi.',
            'nik.required'  => 'NIK pendonor wajib diisi.',
            'nik.unique'    => 'NIK ini sudah digunakan pendonor lain.',
            'email.email'   => 'Format email tidak valid (contoh: pendonor@gmail.com).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $pendonor->update([
            'nama'           => trim($request->input('nama')),
            'nik'            => trim($request->input('nik')),
            'golongan'       => trim($request->input('golongan_darah')),
            'rhesus'         => trim($request->input('rhesus')),
            'telepon'        => trim($request->input('telepon')),
            'email'          => trim($request->input('email')) ?: null,
            'tanggal_lahir'  => $request->input('tanggal_lahir') ?: null,
            'jenis_kelamin'  => trim($request->input('jenis_kelamin')) ?: null,
            'alamat'         => trim($request->input('alamat')) ?: null,
            'kota'           => trim($request->input('kota')) ?: null,
            'provinsi'       => trim($request->input('provinsi')) ?: null,
            'pekerjaan'      => trim($request->input('pekerjaan')) ?: null,
            'status'         => $request->input('status') === 'aktif' ? 'aktif' : 'nonaktif',
            'tekanan_darah'  => $request->filled('tekanan_darah') ? trim($request->input('tekanan_darah')) : $pendonor->tekanan_darah,
            'detak_jantung'  => $request->filled('detak_jantung') ? trim($request->input('detak_jantung')) : $pendonor->detak_jantung,
            'hemoglobin'     => $request->filled('hemoglobin') ? trim($request->input('hemoglobin')) : $pendonor->hemoglobin,
            'suhu_tubuh'     => $request->filled('suhu_tubuh') ? trim($request->input('suhu_tubuh')) : $pendonor->suhu_tubuh,
            'kesehatan_umum' => $request->filled('kesehatan_umum') ? trim($request->input('kesehatan_umum')) : $pendonor->kesehatan_umum,
            'catatan'        => $request->filled('catatan') ? trim($request->input('catatan')) : $pendonor->catatan,
        ]);

        Log::info("Petugas memperbarui data pendonor ID #{$pendonor->id} ({$pendonor->nama})");
        return redirect()->route('petugas.daftar-pendonor')
            ->with('success', "Data pendonor '{$pendonor->nama}' berhasil diperbarui.");
    }

    public function hapusPendonorProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id', $request->query('id'));
        $pendonor = Pendonor::findOrFail($id);
        $nama = $pendonor->nama;
        $pendonor->delete();

        Log::info("Petugas menghapus data pendonor ID #{$id} ({$nama})");
        return redirect()->route('petugas.daftar-pendonor')
            ->with('success', 'Data pendonor berhasil dihapus.');
    }

    public function riwayatDonasi(Request $request)
    {
        $query = RiwayatDonasi::with('pendonor');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $riwayatList = $query->orderBy('tanggal', 'desc')->get();

        return view('petugas.riwayat-donasi', compact('riwayatList'));
    }

    public function formRiwayat()
    {
        $pendonorList = Pendonor::where('status', 'aktif')->orderBy('nama')->get();
        return view('petugas.form-riwayat', compact('pendonorList'));
    }

    public function formRiwayatProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pendonor_id' => 'required|exists:pendonor,id',
            'golongan'    => 'required|in:A,B,AB,O',
            'rhesus'      => 'required|in:+,-',
            'jumlah'      => 'required|integer|min:1',
            'tanggal'     => 'required|date',
            'status'      => 'nullable|in:Berhasil,Ditolak',
        ], [
            'pendonor_id.required' => 'Pilih pendonor yang melakukan donor darah.',
            'golongan.required'    => 'Golongan darah wajib diisi.',
            'rhesus.required'      => 'Rhesus darah wajib diisi.',
            'jumlah.required'      => 'Jumlah kantong darah wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($request) {
                $status   = trim($request->input('status', 'Berhasil'));
                $golongan = trim($request->input('golongan'));
                $rhesus   = trim($request->input('rhesus'));
                $jumlah   = (int) $request->input('jumlah');

                $riwayat = RiwayatDonasi::create([
                    'pendonor_id'    => $request->input('pendonor_id'),
                    'golongan'       => $golongan,
                    'rhesus'         => $rhesus,
                    'jumlah'         => $status === 'Berhasil' ? $jumlah : 0,
                    'tanggal'        => $request->input('tanggal'),
                    'tekanan_darah'  => trim($request->input('tekanan_darah')) ?: '120/80',
                    'detak_jantung'  => trim($request->input('detak_jantung')) ?: '72',
                    'hemoglobin'     => trim($request->input('hemoglobin')) ?: '14.5',
                    'suhu_tubuh'     => trim($request->input('suhu_tubuh')) ?: '36.5',
                    'kesehatan_umum' => trim($request->input('kesehatan_umum')) ?: 'Sehat Baik',
                    'catatan'        => trim($request->input('catatan')) ?: null,
                    'status'         => $status,
                ]);

                if ($status === 'Berhasil') {
                    $stok = StokDarah::where('golongan_darah', $golongan)
                        ->where('rhesus', $rhesus)
                        ->lockForUpdate()
                        ->first();

                    if (! $stok) {
                        $stok = StokDarah::create([
                            'golongan_darah' => $golongan,
                            'rhesus'         => $rhesus,
                            'jumlah'         => 0,
                            'min_stock'      => 10,
                        ]);
                    }

                    $sebelum = $stok->jumlah;
                    $stok->jumlah += $jumlah;
                    $stok->save();

                    StokDarahHistory::create([
                        'golongan'   => $golongan,
                        'rhesus'     => $rhesus,
                        'masuk'      => $jumlah,
                        'keluar'     => 0,
                        'stok_akhir' => $stok->jumlah,
                        'catatan'    => "Penambahan stok dari donasi donor #{$riwayat->pendonor_id}",
                    ]);

                    // Update tgl_donor pada pendonor
                    Pendonor::where('id', $request->input('pendonor_id'))->update(['tgl_donor' => $request->input('tanggal')]);
                    Log::info("Petugas menambahkan riwayat donasi donor #{$riwayat->pendonor_id} ({$golongan}{$rhesus}, {$jumlah} kantong, stok bertambah)");
                } else {
                    Log::info("Petugas mencatat riwayat donasi ditolak untuk donor #{$riwayat->pendonor_id}");
                }
            });

            return redirect()->route('petugas.riwayat-donasi')
                ->with('success', 'Riwayat donasi darah berhasil dicatat.');
        } catch (\Exception $e) {
            Log::error("Kesalahan saat menambah riwayat donasi: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat mencatat riwayat donasi.');
        }
    }

    public function editRiwayatProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id');
        $riwayat = RiwayatDonasi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'golongan' => 'required|in:A,B,AB,O',
            'rhesus'   => 'required|in:+,-',
            'jumlah'   => 'required|integer|min:1',
            'status'   => 'required|in:Berhasil,Ditolak',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($request, $riwayat) {
                // Revert previous stock if old status was Berhasil
                if ($riwayat->status === 'Berhasil') {
                    $oldStok = StokDarah::where('golongan_darah', $riwayat->golongan)
                        ->where('rhesus', $riwayat->rhesus)
                        ->lockForUpdate()
                        ->first();
                    if ($oldStok) {
                        $oldStok->jumlah = max(0, $oldStok->jumlah - $riwayat->jumlah);
                        $oldStok->save();
                    }
                }

                $newStatus   = trim($request->input('status'));
                $newGolongan = trim($request->input('golongan'));
                $newRhesus   = trim($request->input('rhesus'));
                $newJumlah   = (int) $request->input('jumlah');

                $riwayat->update([
                    'pendonor_id'   => $request->input('pendonor_id', $riwayat->pendonor_id),
                    'golongan'      => $newGolongan,
                    'rhesus'        => $newRhesus,
                    'jumlah'        => $newStatus === 'Berhasil' ? $newJumlah : 0,
                    'tanggal'       => $request->input('tanggal', $riwayat->tanggal),
                    'tekanan_darah' => trim($request->input('tekanan_darah', $riwayat->tekanan_darah)),
                    'status'        => $newStatus,
                ]);

                if ($newStatus === 'Berhasil') {
                    $newStok = StokDarah::where('golongan_darah', $newGolongan)
                        ->where('rhesus', $newRhesus)
                        ->lockForUpdate()
                        ->first();
                    if ($newStok) {
                        $newStok->jumlah += $newJumlah;
                        $newStok->save();
                    }
                }

                Log::info("Petugas memperbarui riwayat donasi ID #{$riwayat->id}");
            });

            return redirect()->route('petugas.riwayat-donasi')
                ->with('success', 'Riwayat donasi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Kesalahan update riwayat donasi: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui riwayat.');
        }
    }

    public function hapusRiwayatProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id', $request->query('id'));
        $riwayat = RiwayatDonasi::findOrFail($id);

        try {
            DB::transaction(function () use ($riwayat, $id) {
                if ($riwayat->status === 'Berhasil') {
                    $stok = StokDarah::where('golongan_darah', $riwayat->golongan)
                        ->where('rhesus', $riwayat->rhesus)
                        ->lockForUpdate()
                        ->first();
                    if ($stok) {
                        $stok->jumlah = max(0, $stok->jumlah - $riwayat->jumlah);
                        $stok->save();
                    }
                }
                $riwayat->delete();
                Log::info("Petugas menghapus riwayat donasi ID #{$id}");
            });

            return redirect()->route('petugas.riwayat-donasi')
                ->with('success', 'Riwayat donasi berhasil dihapus dan stok telah disesuaikan.');
        } catch (\Exception $e) {
            Log::error("Kesalahan hapus riwayat donasi: " . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Gagal menghapus riwayat donasi.');
        }
    }

    public function jadwalDonor()
    {
        $jadwalList = JadwalDonor::orderBy('tanggal', 'desc')->get();
        return view('petugas.jadwal-donor', compact('jadwalList'));
    }

    public function stokDarah()
    {
        $stokList = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();
        return view('petugas.stok-darah', compact('stokList'));
    }
}
