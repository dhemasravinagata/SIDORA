<?php
require_once __DIR__ . '/../config/database.php';

class JadwalDonor
{
    public function getAll()
    {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM jadwal_donor ORDER BY tanggal ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM jadwal_donor WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO jadwal_donor (lokasi, tanggal, target, waktu_mulai, waktu_selesai, status, catatan) VALUES (:lokasi, :tanggal, :target, :waktu_mulai, :waktu_selesai, :status, :catatan)');
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE jadwal_donor SET lokasi = :lokasi, tanggal = :tanggal, target = :target, waktu_mulai = :waktu_mulai, waktu_selesai = :waktu_selesai, status = :status, catatan = :catatan WHERE id = :id');
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM jadwal_donor WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
