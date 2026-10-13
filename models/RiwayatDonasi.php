<?php
require_once __DIR__ . '/../config/database.php';

class RiwayatDonasi
{
    public function getAll()
    {
        global $pdo;
        $stmt = $pdo->query('SELECT r.*, p.nama AS nama_pendonor, p.nik, p.jenis_kelamin, p.tanggal_lahir, p.telepon, p.alamat, p.pekerjaan FROM riwayat_donasi r LEFT JOIN pendonor p ON r.pendonor_id = p.id ORDER BY r.created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT r.*, p.nama AS nama_pendonor FROM riwayat_donasi r LEFT JOIN pendonor p ON r.pendonor_id = p.id WHERE r.id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByPendonor($pendonorId)
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM riwayat_donasi WHERE pendonor_id = :pendonor_id ORDER BY tanggal DESC');
        $stmt->execute(['pendonor_id' => $pendonorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO riwayat_donasi (pendonor_id, golongan, rhesus, jumlah, tanggal, tekanan_darah, status) VALUES (:pendonor_id, :golongan, :rhesus, :jumlah, :tanggal, :tekanan_darah, :status)');
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE riwayat_donasi SET pendonor_id = :pendonor_id, golongan = :golongan, rhesus = :rhesus, jumlah = :jumlah, tanggal = :tanggal, tekanan_darah = :tekanan_darah, status = :status WHERE id = :id');
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM riwayat_donasi WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
