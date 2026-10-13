<?php
require_once __DIR__ . '/../config/database.php';

class StokDarah
{
    private function ensureHistoryTable()
    {
        global $pdo;
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS stok_darah_history (
                id INT AUTO_INCREMENT PRIMARY KEY,
                golongan VARCHAR(10) NOT NULL,
                rhesus VARCHAR(5) NOT NULL,
                masuk INT NOT NULL DEFAULT 0,
                keluar INT NOT NULL DEFAULT 0,
                stok_akhir INT NOT NULL DEFAULT 0,
                catatan TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM stok_darah ORDER BY golongan_darah ASC, rhesus ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM stok_darah WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByGolonganRhesus($golongan, $rhesus)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM stok_darah WHERE golongan_darah = :golongan AND rhesus = :rhesus LIMIT 1");
        $stmt->execute(['golongan' => $golongan, 'rhesus' => $rhesus]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isStockAvailable($golongan, $rhesus, $jumlah)
    {
        $stok = $this->getByGolonganRhesus($golongan, $rhesus);
        return $stok && (int)$stok['jumlah'] >= (int)$jumlah;
    }

    public function addStock($golongan, $rhesus, $jumlah, $catatan = 'Stok masuk dari donasi darah')
    {
        return $this->updateStockByGolongan($golongan, $rhesus, abs($jumlah), $catatan);
    }

    public function reduceStock($golongan, $rhesus, $jumlah, $catatan = 'Stok keluar untuk permintaan darah')
    {
        return $this->updateStockByGolongan($golongan, $rhesus, -abs($jumlah), $catatan);
    }

    public function updateQuantity($id, $jumlah)
    {
        global $pdo;
        $old = $this->getById($id);
        $stmt = $pdo->prepare('UPDATE stok_darah SET jumlah = :jumlah WHERE id = :id');
        $updated = $stmt->execute(['jumlah' => $jumlah, 'id' => $id]);

        if ($updated && $old) {
            $diff = (int)$jumlah - (int)$old['jumlah'];
            if ($diff !== 0) {
                $this->recordHistory(
                    $old['golongan_darah'],
                    $old['rhesus'],
                    max($diff, 0),
                    abs(min($diff, 0)),
                    (int)$jumlah,
                    'Penyesuaian stok manual'
                );
            }
        }

        return $updated;
    }

    public function updateStockByGolongan($golongan, $rhesus, $jumlah_tambah, $catatan = null)
    {
        global $pdo;
        $old = $this->getByGolonganRhesus($golongan, $rhesus);
        if (!$old) {
            return false;
        }

        $stmt = $pdo->prepare('UPDATE stok_darah SET jumlah = GREATEST(0, jumlah + :jumlah) WHERE golongan_darah = :golongan AND rhesus = :rhesus');
        $updated = $stmt->execute(['jumlah' => $jumlah_tambah, 'golongan' => $golongan, 'rhesus' => $rhesus]);

        if ($updated) {
            $new = $this->getByGolonganRhesus($golongan, $rhesus);
            $stokAwal = (int)$old['jumlah'];
            $stokAkhir = (int)($new['jumlah'] ?? $stokAwal);
            $diff = $stokAkhir - $stokAwal;

            if ($diff !== 0) {
                $this->recordHistory(
                    $golongan,
                    $rhesus,
                    max($diff, 0),
                    abs(min($diff, 0)),
                    $stokAkhir,
                    $catatan
                );
            }
        }

        return $updated;
    }

    public function recordHistory($golongan, $rhesus, $masuk, $keluar, $stokAkhir, $catatan = null)
    {
        global $pdo;
        $this->ensureHistoryTable();
        $stmt = $pdo->prepare('
            INSERT INTO stok_darah_history (golongan, rhesus, masuk, keluar, stok_akhir, catatan)
            VALUES (:golongan, :rhesus, :masuk, :keluar, :stok_akhir, :catatan)
        ');
        return $stmt->execute([
            'golongan' => $golongan,
            'rhesus' => $rhesus,
            'masuk' => (int)$masuk,
            'keluar' => (int)$keluar,
            'stok_akhir' => (int)$stokAkhir,
            'catatan' => $catatan,
        ]);
    }

    public function getHistoryLast7Days()
    {
        global $pdo;
        $this->ensureHistoryTable();
        $stmt = $pdo->query("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') AS tanggal,
                golongan,
                rhesus,
                masuk,
                keluar,
                stok_akhir,
                catatan
            FROM stok_darah_history
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ORDER BY created_at DESC, id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
