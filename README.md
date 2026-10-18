### 1. Menghapus Akses Kolaborator (Collaborator / Outside Collaborator)

  Jika orang tersebut diberi akses ke repository tetapi bukan bagian dari tim/organisasi:

  #### A. Pada Repository Akun Pribadi (Personal)

  1. Buka repository Anda di web GitHub.
  2. Masuk ke tab Settings (Pengaturan) di bagian atas.
  3. Di bilah sisi kiri, pilih menu Collaborators.
  4. Cari nama pengguna (username) orang tersebut.
  5. Klik tombol Remove (atau ikon tempat sampah) untuk mencabut aksesnya.

  #### B. Pada Repository Organisasi (Organization)

  1. Buka repository di GitHub.
  2. Masuk ke tab Settings > Collaborators and teams (atau Manage access).
  3. Cari bagian Outside collaborators atau Direct access.
  4. Klik tanda titik tiga (…) atau tombol Remove di samping namanya, lalu konfirmasi penghapusan.

  │ Note
  │ Setelah dihapus dari Collaborator, orang tersebut tidak akan bisa lagi melakukan push atau mengedit repository secara langsung.
  ──────
  ### 2. Jika Orang Tersebut Muncul di Daftar "Contributors"

  Jika orang tersebut bukan kolaborator tetapi namanya tetap muncul di halaman depan repository pada bagian Contributors:

  • GitHub otomatis menampilkan siapa saja yang memiliki riwayat commit di branch utama (main/master).
  • Penting: Muncul di daftar kontributor bukan berarti dia memiliki akses edit/write ke repository Anda.
  • Jika Anda benar-benar ingin menghilangkan namanya dari daftar kontributor, riwayat commit orang tersebut harus dihapus atau di-rewrite dari
  riwayat git (misalnya menggunakan git filter-repo atau interactive rebase) kemudian di-force push.
