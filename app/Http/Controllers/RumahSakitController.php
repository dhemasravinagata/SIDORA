<?php

namespace App\Http\Controllers;

use App\Models\PermintaanDarah;
use App\Models\PermintaanDarahDetail;
use App\Models\StokDarah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RumahSakitController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $stokList = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();
        $permintaanColl = PermintaanDarah::with('details')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $statistics = [
            'total_permintaan'    => $permintaanColl->count(),
            'permintaan_ditinjau' => $permintaanColl->where('status', 'Pending')->count(),
            'disetujui'           => $permintaanColl->whereIn('status', ['Disetujui', 'Dikirim'])->count(),
            'permintaan_ditolak'  => $permintaanColl->where('status', 'Ditolak')->count(),
        ];

        $permintaanList = $permintaanColl->all();

        return view('rumahsakit.dashboard', compact('stokList', 'permintaanList', 'statistics'));
    }

    public function permintaan()
    {
        $stokList = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();
        return view('rumahsakit.permintaan-darah', compact('stokList'));
    }

    public function permintaanStore(Request $request)
    {
        $userId = Auth::id();
        $tujuan = trim($request->input('tujuan_permintaan', 'pasien'));

        $rules = [
            'golongan'  => 'required|in:A,B,AB,O',
            'rhesus'    => 'required|in:+,-',
            'jumlah'    => 'required|integer|min:1',
            'prioritas' => 'required|in:Biasa,Penting,Darurat,Sangat Mendesak',
        ];

        $messages = [
            'golongan.required'  => 'Golongan darah wajib dipilih.',
            'rhesus.required'    => 'Rhesus darah wajib dipilih.',
            'jumlah.required'    => 'Jumlah kantong darah wajib diisi angka positif.',
            'prioritas.required' => 'Tingkat prioritas permintaan wajib ditentukan.',
        ];

        if ($tujuan === 'pasien') {
            $rules['nama_pasien'] = 'required|string|min:3|max:100';
            $rules['no_pasien']   = 'required|string|min:2|max:50';
            $rules['ruangan']     = 'required|string|min:2|max:50';
            $rules['dokter']      = 'required|string|min:3|max:100';

            $messages['nama_pasien.required'] = 'Nama pasien penerima darah wajib diisi.';
            $messages['no_pasien.required']   = 'Nomor Rekam Medis (RM) pasien wajib diisi.';
            $messages['ruangan.required']     = 'Ruangan/Kamar perawatan pasien wajib diisi.';
            $messages['dokter.required']      = 'Nama dokter penanggung jawab wajib diisi.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $golongan  = trim($request->input('golongan'));
        $rhesus    = trim($request->input('rhesus'));
        $jumlah    = (int) $request->input('jumlah');
        $prioritas = trim($request->input('prioritas'));
        $catatan   = trim($request->input('keterangan', ''));

        if ($tujuan === 'stok') {
            $keterangan = sprintf(
                'Tujuan: stok. Keperluan: Penambahan Stok BDRS (Dropping). Catatan: %s',
                $catatan ?: '-'
            );
        } else {
            $namaPasien = trim($request->input('nama_pasien'));
            $noPasien   = trim($request->input('no_pasien'));
            $ruangan    = trim($request->input('ruangan'));
            $dokter     = trim($request->input('dokter'));

            $keterangan = sprintf(
                'Tujuan: pasien. Pasien: %s (No RM: %s). Ruangan: %s. Dokter: %s. Catatan: %s',
                $namaPasien,
                $noPasien,
                $ruangan,
                $dokter,
                $catatan ?: '-'
            );
        }

        try {
            $permintaanId = DB::transaction(function () use ($userId, $prioritas, $keterangan, $golongan, $rhesus, $jumlah) {
                $permintaan = PermintaanDarah::create([
                    'user_id'    => $userId,
                    'status'     => 'Pending',
                    'prioritas'  => $prioritas,
                    'keterangan' => $keterangan,
                ]);

                PermintaanDarahDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'golongan'      => $golongan,
                    'rhesus'        => $rhesus,
                    'jumlah'        => $jumlah,
                ]);

                Log::info("Rumah Sakit ID #{$userId} mengajukan permintaan darah baru #REQ-{$permintaan->id}: {$golongan}{$rhesus} ({$jumlah} kantong, Prioritas: {$prioritas})");

                return $permintaan->id;
            });

            return redirect()->route('rs.history-permintaan')
                ->with('success', "Permintaan darah (#REQ-{$permintaanId}) berhasil diajukan dan sedang menunggu konfirmasi admin.");
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mengajukan permintaan darah: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat mengajukan permintaan.');
        }
    }

    public function historyPermintaan()
    {
        $userId = Auth::id();
        $permintaanList = PermintaanDarah::with('details')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('rumahsakit.history-permintaan', compact('permintaanList'));
    }

    public function stokDarah()
    {
        $stokList = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();
        return view('rumahsakit.stok-darah', compact('stokList'));
    }

    public function profil()
    {
        $user = Auth::user();
        return view('rumahsakit.profil', compact('user'));
    }

    public function profilUpdate(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|min:3|max:150',
            'username' => "nullable|string|min:3|max:50|unique:users,username,{$user->id}",
            'telepon'  => 'nullable|string|min:6|max:25',
        ], [
            'nama.required'   => 'Nama Rumah Sakit wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan akun lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $user->update([
            'name'      => trim($request->input('nama')),
            'username'  => trim($request->input('username')) ?: null,
            'alamat'    => trim($request->input('alamat')) ?: null,
            'telepon'   => trim($request->input('telepon')) ?: null,
            'kontak'    => trim($request->input('kontak')) ?: null,
            'desa'      => trim($request->input('desa')) ?: null,
            'kecamatan' => trim($request->input('kecamatan')) ?: null,
            'provinsi'  => trim($request->input('provinsi')) ?: null,
            'kode_pos'  => trim($request->input('kode_pos')) ?: null,
            'tipe_rs'   => trim($request->input('tipe_rs', $user->tipe_rs)) ?: null,
            'no_izin'   => trim($request->input('no_izin', $user->no_izin)) ?: null,
        ]);

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['user'] = $user->toArray();
        }

        Log::info("Rumah Sakit ID #{$user->id} memperbarui informasi profil");
        return redirect()->route('rs.profil')->with('success', 'Profil Rumah Sakit berhasil diperbarui.');
    }

    public function ubahPassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'old_password'     => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required'     => 'Password lama wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal harus 6 karakter.',
            'confirm_password.required' => 'Konfirmasi password baru wajib diisi.',
            'confirm_password.same'     => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        if (! Hash::check($request->input('old_password'), $user->password) && $request->input('old_password') !== $user->password) {
            Log::warning("Gagal mengubah password Rumah Sakit ID #{$user->id}: Password lama salah");
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        Log::info("Rumah Sakit ID #{$user->id} berhasil mengubah password akun");
        return redirect()->route('rs.profil')
            ->with('success', 'Password berhasil diubah. Silakan gunakan password baru pada sesi masuk berikutnya.');
    }
}
