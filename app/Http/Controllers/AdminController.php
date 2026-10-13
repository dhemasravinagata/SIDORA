<?php

namespace App\Http\Controllers;

use App\Models\JadwalDonor;
use App\Models\Pendonor;
use App\Models\PermintaanDarah;
use App\Models\RiwayatDonasi;
use App\Models\StokDarah;
use App\Models\StokDarahHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    private function csvDateText($value): string
    {
        return $value ? "\t" . $value : '';
    }

    public function dashboard()
    {
        $stokList = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();
        $stokTotal = (int) $stokList->sum('jumlah');

        $permintaanCollection = PermintaanDarah::with(['user', 'details'])->orderBy('created_at', 'desc')->get();
        $permintaanPending = $permintaanCollection->where('status', 'Pending')->count();

        $users = User::all();
        $petugasCount = $users->where('role', 'petugas')->count();
        $rsCount      = $users->where('role', 'rumahsakit')->count();

        $statistics = [
            'pendonor'   => Pendonor::count(),
            'stok_total' => $stokTotal,
            'permintaan' => $permintaanPending,
            'jadwal'     => JadwalDonor::count(),
            'petugas'    => $petugasCount,
            'rumahsakit' => $rsCount,
        ];

        $permintaan = $permintaanCollection->all();
        $stokDarah  = $stokList->all();

        return view('admin.dashboard', compact('stokList', 'stokDarah', 'permintaan', 'statistics'));
    }

    public function stokDarah()
    {
        $stokList = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();

        $stokTotal  = 0;
        $stokAman   = 0;
        $stokRendah = 0;
        $stokKritis = 0;

        $stockMap = [];
        foreach ($stokList as $stok) {
            $label = $stok->label;
            $qty = (int) $stok->jumlah;
            $stockMap[$label] = $qty;
            $stokTotal += $qty;
        }

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($bloodTypes as $bt) {
            $qty = $stockMap[$bt] ?? 0;
            if ($qty >= 50) {
                $stokAman++;
            } elseif ($qty >= 30) {
                $stokRendah++;
            } else {
                $stokKritis++;
            }
        }

        $statistics = [
            'stok_total'  => $stokTotal,
            'stok_aman'   => $stokAman,
            'stok_rendah' => $stokRendah,
            'stok_kritis' => $stokKritis,
        ];

        // Riwayat update stok 7 hari terakhir
        $sevenDaysAgo = now()->subDays(7)->startOfDay();
        $stokHistory = StokDarahHistory::where('created_at', '>=', $sevenDaysAgo)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal'    => $item->created_at->format('Y-m-d H:i'),
                    'golongan'   => $item->golongan ?? '-',
                    'rhesus'     => $item->rhesus ?? '',
                    'masuk'      => $item->masuk,
                    'keluar'     => $item->keluar,
                    'stok_akhir' => $item->stok_akhir,
                    'catatan'    => $item->catatan,
                ];
            });

        return view('admin.stok-darah', compact('stokList', 'statistics', 'stokHistory'));
    }

    public function alertStok()
    {
        Log::info("Admin mengecek dan mencatat alert stok darah kritis.");
        return redirect()->route('admin.stok-darah')
            ->with('success', 'Peringatan status stok kritis berhasil diverifikasi.');
    }

    public function pesanStok(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'golongan' => 'required|string',
            'jumlah'   => 'required|integer|min:1',
        ], [
            'golongan.required' => 'Golongan darah wajib dipilih.',
            'jumlah.required'   => 'Jumlah kantong darah wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $golongan = trim($request->input('golongan'));
        $jumlah   = (int) $request->input('jumlah');

        Log::info("Admin memesan penambahan stok darah {$golongan} sebanyak {$jumlah} kantong");
        return redirect()->route('admin.stok-darah')
            ->with('success', "Permintaan tambahan stok golongan {$golongan} sebanyak {$jumlah} kantong berhasil dicatat.");
    }

    public function permintaanDarah()
    {
        $permintaan = PermintaanDarah::with(['user', 'details'])->orderBy('created_at', 'desc')->get();
        return view('admin.permintaan-darah', compact('permintaan'));
    }

    public function terimaPermintaan(Request $request, $id = null)
    {
        $id = $id ?: $request->query('id', $request->input('id'));
        if (! $id) {
            return redirect()->route('admin.permintaan-darah')->with('error', 'Permintaan darah tidak valid.');
        }

        try {
            DB::transaction(function () use ($id) {
                /** @var PermintaanDarah $permintaan */
                $permintaan = PermintaanDarah::with('details')->lockForUpdate()->find($id);

                if (! $permintaan) {
                    throw new \InvalidArgumentException('Permintaan darah tidak ditemukan.');
                }

                if ($permintaan->status !== 'Pending') {
                    throw new \InvalidArgumentException('Hanya permintaan berstatus Pending yang dapat disetujui.');
                }

                if ($permintaan->details->isEmpty()) {
                    throw new \InvalidArgumentException('Detail permintaan darah kosong.');
                }

                // Cek ketersediaan seluruh item sebelum pengurangan
                foreach ($permintaan->details as $det) {
                    $stok = StokDarah::where('golongan_darah', $det->golongan)
                        ->where('rhesus', $det->rhesus)
                        ->lockForUpdate()
                        ->first();

                    if (! $stok || $stok->jumlah < $det->jumlah) {
                        $label = "{$det->golongan}{$det->rhesus}";
                        throw new \RuntimeException("Stok darah {$label} tidak mencukupi (Tersedia: " . ($stok?->jumlah ?? 0) . ", Diminta: {$det->jumlah}).");
                    }
                }

                // Pengurangan stok secara aman
                foreach ($permintaan->details as $det) {
                    $stok = StokDarah::where('golongan_darah', $det->golongan)
                        ->where('rhesus', $det->rhesus)
                        ->lockForUpdate()
                        ->first();

                    $sebelum = $stok->jumlah;
                    $stok->jumlah = max(0, $stok->jumlah - $det->jumlah);
                    $stok->save();

                    StokDarahHistory::create([
                        'golongan'   => $det->golongan,
                        'rhesus'     => $det->rhesus,
                        'masuk'      => 0,
                        'keluar'     => $det->jumlah,
                        'stok_akhir' => $stok->jumlah,
                        'catatan'    => "Penyaluran darah untuk RS {$permintaan->rumah_sakit} (#REQ-{$permintaan->id})",
                    ]);
                }

                $permintaan->status = 'Disetujui';
                $permintaan->save();

                Log::info("Admin menyetujui permintaan darah #REQ-{$permintaan->id} untuk RS: {$permintaan->rumah_sakit}");
            });

            return redirect()->route('admin.permintaan-darah')
                ->with('success', "Permintaan darah #REQ-{$id} berhasil disetujui. Stok darah telah dialokasikan.");
        } catch (\InvalidArgumentException $ie) {
            return redirect()->route('admin.permintaan-darah')->with('warning', $ie->getMessage());
        } catch (\RuntimeException $re) {
            return redirect()->route('admin.permintaan-darah')->with('error', $re->getMessage());
        } catch (\Exception $e) {
            Log::error("Gagal menyetujui permintaan darah #REQ-{$id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.permintaan-darah')->with('error', 'Terjadi kesalahan sistem saat menyetujui permintaan.');
        }
    }

    public function tolakPermintaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permintaan_id' => 'required|integer',
            'alasan'        => 'required|string|min:4|max:255',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi untuk transparansi ke rumah sakit.',
            'alasan.min'      => 'Alasan penolakan minimal 4 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $id     = (int) $request->input('permintaan_id');
        $alasan = trim($request->input('alasan'));

        try {
            DB::transaction(function () use ($id, $alasan) {
                /** @var PermintaanDarah $permintaan */
                $permintaan = PermintaanDarah::lockForUpdate()->find($id);

                if (! $permintaan) {
                    throw new \InvalidArgumentException('Permintaan darah tidak ditemukan.');
                }

                if ($permintaan->status !== 'Pending') {
                    throw new \InvalidArgumentException('Hanya permintaan berstatus Pending yang dapat ditolak.');
                }

                $permintaan->update([
                    'status'        => 'Ditolak',
                    'alasan_tolak'  => $alasan,
                    'tanggal_tolak' => now()->toDateString(),
                ]);

                Log::info("Admin menolak permintaan darah #REQ-{$id}. Alasan: {$alasan}");
            });

            return redirect()->route('admin.permintaan-darah')
                ->with('info', "Permintaan darah #REQ-{$id} telah ditolak.");
        } catch (\InvalidArgumentException $ie) {
            return redirect()->route('admin.permintaan-darah')->with('warning', $ie->getMessage());
        } catch (\Exception $e) {
            Log::error("Gagal menolak permintaan darah #REQ-{$id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.permintaan-darah')->with('error', 'Terjadi kesalahan sistem saat memproses penolakan.');
        }
    }

    public function kirimPermintaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permintaan_id' => 'required|integer',
            'kurir'         => 'required|string|min:3|max:100',
        ], [
            'kurir.required' => 'Nama kurir / ekspedisi pengantar darah wajib diisi.',
            'kurir.min'      => 'Nama kurir minimal 3 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $id    = (int) $request->input('permintaan_id');
        $kurir = trim($request->input('kurir'));

        try {
            DB::transaction(function () use ($id, $kurir) {
                /** @var PermintaanDarah $permintaan */
                $permintaan = PermintaanDarah::lockForUpdate()->find($id);

                if (! $permintaan) {
                    throw new \InvalidArgumentException('Permintaan darah tidak ditemukan.');
                }

                if ($permintaan->status !== 'Disetujui') {
                    throw new \InvalidArgumentException('Permintaan hanya dapat dikirim jika sudah berstatus Disetujui.');
                }

                $permintaan->update([
                    'status'        => 'Dikirim',
                    'kurir'         => $kurir,
                    'tanggal_kirim' => now()->toDateString(),
                ]);

                Log::info("Admin memperbarui pengiriman permintaan darah #REQ-{$id} dengan kurir: {$kurir}");
            });

            return redirect()->route('admin.permintaan-darah')
                ->with('success', "Permintaan #REQ-{$id} telah ditandai sedang dikirim via {$kurir}.");
        } catch (\InvalidArgumentException $ie) {
            return redirect()->route('admin.permintaan-darah')->with('warning', $ie->getMessage());
        } catch (\Exception $e) {
            Log::error("Gagal mengirim permintaan darah #REQ-{$id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.permintaan-darah')->with('error', 'Terjadi kesalahan sistem saat memperbarui status pengiriman.');
        }
    }

    public function kelolaPetugas()
    {
        $users = User::all();
        $petugasList = $users->where('role', 'petugas')->values();
        $rumahSakitList = $users->where('role', 'rumahsakit')->where('status', '!=', 'ditolak')->values();

        return view('admin.kelola-petugas', compact('petugasList', 'rumahSakitList'));
    }

    public function formPetugas()
    {
        return view('admin.form-petugas');
    }

    public function tambahPetugasProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|min:3|max:150',
            'email'    => 'required|email|max:150|unique:users,email',
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'telepon'  => 'nullable|string|min:6|max:20',
        ], [
            'nama.required'     => 'Nama petugas wajib diisi.',
            'email.required'    => 'Email petugas wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'username.required' => 'Username petugas wajib diisi.',
            'username.unique'   => 'Username sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 6 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $nama     = trim($request->input('nama'));
        $username = trim($request->input('username'));

        User::create([
            'name'     => $nama,
            'email'    => trim($request->input('email')),
            'username' => $username,
            'password' => Hash::make($request->input('password')),
            'role'     => 'petugas',
            'status'   => $request->input('status', 'aktif'),
            'telepon'  => trim($request->input('telepon')),
        ]);

        Log::info("Admin menambahkan petugas baru: {$nama} ({$username})");
        return redirect()->route('admin.kelola-petugas')
            ->with('success', "Akun petugas '{$nama}' berhasil ditambahkan.");
    }

    public function editPetugasProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id');
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|min:3|max:150',
            'email'    => "required|email|max:150|unique:users,email,{$user->id}",
            'username' => "required|string|min:3|max:50|unique:users,username,{$user->id}",
            'telepon'  => 'nullable|string|min:6|max:20',
        ], [
            'nama.required'     => 'Nama petugas wajib diisi.',
            'email.required'    => 'Email petugas wajib diisi.',
            'email.unique'      => 'Email sudah digunakan akun lain.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan akun lain.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $data = [
            'name'     => trim($request->input('nama')),
            'email'    => trim($request->input('email')),
            'username' => trim($request->input('username')),
            'telepon'  => trim($request->input('telepon')),
            'status'   => $request->input('status', 'aktif'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        Log::info("Admin memperbarui data petugas ID #{$user->id} ({$user->name})");
        return redirect()->route('admin.kelola-petugas')
            ->with('success', "Data petugas '{$user->name}' berhasil diperbarui.");
    }

    public function hapusPetugasProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id', $request->query('id'));
        $user = User::findOrFail($id);
        $nama = $user->name;
        $user->delete();

        Log::info("Admin menghapus akun petugas ID #{$id} ({$nama})");
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Akun petugas berhasil dihapus dari sistem.');
    }

    public function hapusRsProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id', $request->query('id'));
        $rs = User::findOrFail($id);
        $nama = $rs->name;
        $rs->delete();

        Log::info("Admin menghapus akun Rumah Sakit ID #{$id} ({$nama})");
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Akun rumah sakit berhasil dihapus.');
    }

    public function approveRs(Request $request)
    {
        $id     = (int) $request->input('id');
        $status = $request->input('status');

        if ($id && in_array($status, ['aktif', 'ditolak', 'nonaktif', 'pending'], true)) {
            $user = User::find($id);
            if (! $user || $user->role !== 'rumahsakit') {
                return redirect()->route('admin.kelola-petugas')->with('error', 'Data rumah sakit tidak ditemukan.');
            }

            $user->status = $status;
            $user->save();

            Log::info("Admin memperbarui status Rumah Sakit #{$id} ({$user->name}) menjadi {$status}");
            return redirect()->route('admin.kelola-petugas')
                ->with('success', "Status akun rumah sakit '{$user->name}' berhasil diperbarui menjadi '{$status}'.");
        }

        return redirect()->route('admin.kelola-petugas')->with('error', 'Parameter verifikasi status tidak valid.');
    }

    public function jadwalDonor()
    {
        $jadwalList = JadwalDonor::orderBy('tanggal', 'desc')->get();

        $currentMonth = date('Y-m');
        $jadwalBulanIni = $jadwalList->filter(fn($j) => str_starts_with($j->tanggal?->format('Y-m-d') ?? '', $currentMonth))->count();
        $totalPeserta = (int) $jadwalList->sum('terdaftar');

        $statistics = [
            'jadwal'       => $jadwalList->count(),
            'jadwal_bulan' => $jadwalBulanIni,
            'peserta'      => $totalPeserta,
        ];

        return view('admin.jadwal-donor', compact('jadwalList', 'statistics'));
    }

    public function formJadwal()
    {
        return view('admin.form-jadwal');
    }

    public function formJadwalProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi'  => 'required|string|min:3|max:200',
            'tanggal' => 'required|date',
            'target'  => 'required|integer|min:1',
        ], [
            'lokasi.required'  => 'Lokasi kegiatan donor darah wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan donor darah wajib diisi.',
            'target.required'  => 'Target jumlah pendonor wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $lokasi       = trim($request->input('lokasi'));
        $tanggal      = $request->input('tanggal');
        $target       = (int) $request->input('target');
        $waktuMulai   = $request->input('waktu_mulai', $request->input('jam_mulai', '08:00'));
        $waktuSelesai = $request->input('waktu_selesai', $request->input('jam_selesai', '14:00'));
        $status       = $request->input('status', 'Akan Datang');
        $catatan      = trim($request->input('catatan', ''));

        JadwalDonor::create([
            'lokasi'        => $lokasi,
            'tanggal'       => $tanggal,
            'target'        => $target,
            'waktu_mulai'   => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'status'        => $status,
            'catatan'       => $catatan,
        ]);

        Log::info("Admin menambahkan jadwal donor baru di {$lokasi} tanggal {$tanggal}");
        return redirect()->route('admin.jadwal-donor')
            ->with('success', 'Jadwal donor darah baru berhasil ditambahkan.');
    }

    public function editJadwalProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id');
        $jadwal = JadwalDonor::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'lokasi'  => 'required|string|min:3|max:200',
            'tanggal' => 'required|date',
            'target'  => 'required|integer|min:1',
        ], [
            'lokasi.required'  => 'Lokasi kegiatan donor darah wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan donor darah wajib diisi.',
            'target.required'  => 'Target jumlah pendonor wajib diisi angka positif.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
        }

        $jadwal->update([
            'lokasi'        => trim($request->input('lokasi')),
            'tanggal'       => $request->input('tanggal'),
            'target'        => (int) $request->input('target'),
            'waktu_mulai'   => $request->input('waktu_mulai', $request->input('jam_mulai', '08:00')),
            'waktu_selesai' => $request->input('waktu_selesai', $request->input('jam_selesai', '14:00')),
            'status'        => $request->input('status', 'Akan Datang'),
            'catatan'       => trim($request->input('catatan', '')),
        ]);

        Log::info("Admin memperbarui jadwal donor ID #{$id} di {$jadwal->lokasi}");
        return redirect()->route('admin.jadwal-donor')
            ->with('success', 'Jadwal donor darah berhasil diperbarui.');
    }

    public function hapusJadwalProcess(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id', $request->query('id'));
        $jadwal = JadwalDonor::findOrFail($id);
        $jadwal->delete();

        Log::info("Admin menghapus jadwal donor ID #{$id}");
        return redirect()->route('admin.jadwal-donor')
            ->with('success', 'Jadwal donor darah berhasil dihapus.');
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
            $query->where('golongan', $request->input('golongan'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pendonorList = $query->orderBy('nama')->get();

        return view('admin.daftar-pendonor-admin', compact('pendonorList'));
    }

    public function exportSemuaLaporan(): StreamedResponse
    {
        Log::info("Admin mengekspor seluruh laporan operasional SIDORA ke format CSV");

        $users      = User::all();
        $petugas    = $users->where('role', 'petugas')->values();
        $rumahSakit = $users->where('role', 'rumahsakit')->where('status', '!=', 'ditolak')->values();

        $pendonor   = Pendonor::all();
        $permintaan = PermintaanDarah::with(['user', 'details'])->orderBy('created_at', 'desc')->get();
        $stokDarah  = StokDarah::orderBy('golongan_darah')->orderBy('rhesus')->get();
        $riwayatDonasi = RiwayatDonasi::with('pendonor')->orderBy('tanggal', 'desc')->get();
        $jadwalDonor = JadwalDonor::orderBy('tanggal', 'desc')->get();

        $stokTotal = (int) $stokDarah->sum('jumlah');
        $permintaanPending = $permintaan->where('status', 'Pending')->count();
        $permintaanDisetujui = $permintaan->whereIn('status', ['Disetujui', 'Dikirim'])->count();
        $permintaanDitolak = $permintaan->where('status', 'Ditolak')->count();

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="Laporan_SIDORA_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use (
            $petugas, $rumahSakit, $pendonor, $stokTotal, $riwayatDonasi, $jadwalDonor,
            $permintaan, $permintaanPending, $permintaanDisetujui, $permintaanDitolak, $stokDarah
        ) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($out, ['=== RINGKASAN OPERASIONAL SIDORA ===']);
            fputcsv($out, ['Item', 'Jumlah']);
            fputcsv($out, ['Total Petugas', $petugas->count()]);
            fputcsv($out, ['Total Rumah Sakit Terverifikasi/Pending', $rumahSakit->count()]);
            fputcsv($out, ['Total Pendonor', $pendonor->count()]);
            fputcsv($out, ['Total Stok Darah', $stokTotal]);
            fputcsv($out, ['Total Riwayat Donasi', $riwayatDonasi->count()]);
            fputcsv($out, ['Total Jadwal Donor', $jadwalDonor->count()]);
            fputcsv($out, ['Total Permintaan Darah', $permintaan->count()]);
            fputcsv($out, ['Permintaan Pending', $permintaanPending]);
            fputcsv($out, ['Permintaan Disetujui/Dikirim', $permintaanDisetujui]);
            fputcsv($out, ['Permintaan Ditolak', $permintaanDitolak]);
            fputcsv($out, []);

            fputcsv($out, ['=== DATA PETUGAS ===']);
            fputcsv($out, ['Nama', 'Email', 'Username', 'Telepon', 'Status', 'Tanggal Bergabung']);
            foreach ($petugas as $p) {
                fputcsv($out, [$p->name, $p->email, $p->username, $p->telepon, $p->status, $this->csvDateText($p->created_at?->format('Y-m-d'))]);
            }
            fputcsv($out, []);

            fputcsv($out, ['=== DATA RUMAH SAKIT ===']);
            fputcsv($out, ['Nama Rumah Sakit', 'Email', 'Username', 'Tipe Rumah Sakit', 'No. Izin Operasional', 'Telepon', 'Kontak / PIC', 'Alamat', 'Kelurahan / Desa', 'Kecamatan', 'Provinsi', 'Kode Pos', 'Status', 'Tanggal Daftar']);
            foreach ($rumahSakit as $rs) {
                fputcsv($out, [$rs->name, $rs->email, $rs->username, $rs->tipe_rs, $rs->no_izin, $rs->telepon, $rs->kontak, $rs->alamat, $rs->desa, $rs->kecamatan, $rs->provinsi, $rs->kode_pos, $rs->status, $this->csvDateText($rs->created_at?->format('Y-m-d'))]);
            }
            fputcsv($out, []);

            fputcsv($out, ['=== DATA PENDONOR ===']);
            fputcsv($out, ['Nama', 'Golongan Darah', 'NIK', 'Telepon', 'Email', 'Alamat', 'Kota', 'Provinsi', 'Status']);
            foreach ($pendonor as $pd) {
                fputcsv($out, [$pd->nama, $pd->golongan . $pd->rhesus, $pd->nik, $pd->telepon, $pd->email, $pd->alamat, $pd->kota, $pd->provinsi, $pd->status]);
            }
            fputcsv($out, []);

            fputcsv($out, ['=== RIWAYAT DONASI ===']);
            fputcsv($out, ['Tanggal Donasi', 'Nama Pendonor', 'NIK', 'Golongan Darah', 'Jumlah Kantong', 'Tekanan Darah', 'Status', 'Telepon', 'Alamat']);
            foreach ($riwayatDonasi as $r) {
                fputcsv($out, [$this->csvDateText($r->tanggal?->format('Y-m-d')), $r->nama_pendonor, $r->nik, $r->label, $r->jumlah, $r->tekanan_darah, $r->status, $r->telepon, $r->alamat]);
            }
            fputcsv($out, []);

            fputcsv($out, ['=== STOK DARAH SAAT INI ===']);
            fputcsv($out, ['Golongan Darah', 'Rhesus', 'Jumlah Kantong', 'Minimum Stok', 'Status Stok']);
            foreach ($stokDarah as $stok) {
                $statusStok = $stok->jumlah >= 50 ? 'Aman' : ($stok->jumlah >= 30 ? 'Rendah' : 'Kritis');
                fputcsv($out, [$stok->golongan_darah, $stok->rhesus, $stok->jumlah, $stok->min_stock, $statusStok]);
            }
            fputcsv($out, []);

            fputcsv($out, ['=== JADWAL DONOR ===']);
            fputcsv($out, ['Tanggal', 'Waktu Mulai', 'Waktu Selesai', 'Lokasi', 'Target', 'Terdaftar', 'Status', 'Catatan']);
            foreach ($jadwalDonor as $j) {
                fputcsv($out, [$this->csvDateText($j->tanggal?->format('Y-m-d')), $j->waktu_mulai, $j->waktu_selesai, $j->lokasi, $j->target, $j->terdaftar, $j->status, $j->catatan]);
            }
            fputcsv($out, []);

            fputcsv($out, ['=== DATA PERMINTAAN ===']);
            fputcsv($out, ['Rumah Sakit', 'Golongan', 'Jumlah', 'Prioritas', 'Status', 'Tanggal Permintaan', 'Alasan Tolak', 'Tanggal Tolak', 'Tanggal Kirim', 'Kurir', 'Keterangan']);
            foreach ($permintaan as $req) {
                fputcsv($out, [$req->rumah_sakit, $req->golongan . $req->rhesus, $req->detail_jumlah, $req->prioritas, $req->status, $this->csvDateText($req->created_at?->format('Y-m-d')), $req->alasan_tolak, $this->csvDateText($req->tanggal_tolak?->format('Y-m-d')), $this->csvDateText($req->tanggal_kirim?->format('Y-m-d')), $req->kurir, $req->keterangan]);
            }

            fclose($out);
        }, 200, $headers);
    }

    public function exportPermintaanLaporan(): StreamedResponse
    {
        Log::info("Admin mengekspor laporan permintaan darah ke format CSV");

        $permintaan = PermintaanDarah::with(['user', 'details'])->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="Permintaan_Darah_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($permintaan) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($out, ['No', 'Rumah Sakit', 'Golongan', 'Jumlah', 'Prioritas', 'Status', 'Tanggal', 'Kurir']);
            $no = 1;
            foreach ($permintaan as $req) {
                fputcsv($out, [
                    $no++,
                    $req->rumah_sakit,
                    $req->golongan . $req->rhesus,
                    $req->detail_jumlah,
                    $req->prioritas,
                    $req->status,
                    $this->csvDateText($req->created_at?->format('Y-m-d')),
                    $req->kurir,
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }
}
