<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    private function getUserColumns()
    {
        global $pdo;
        static $columns = null;

        if ($columns === null) {
            $stmt = $pdo->query('DESCRIBE users');
            $columns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
        }

        return $columns;
    }

    private function onlyExistingUserColumns($data)
    {
        $columns = array_flip($this->getUserColumns());
        return array_filter(
            $data,
            fn($key) => isset($columns[$key]),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function findByEmail($email)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmailOrUsername($identifier)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :identifier OR username = :identifier LIMIT 1");
        $stmt->execute(['identifier' => $identifier]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPetugas()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'petugas' ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingRumahSakit()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'rumahsakit' ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function updateStatusByRole($id, $status, $role)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id AND role = :role");
        $stmt->execute(['status' => $status, 'id' => $id, 'role' => $role]);
        return $stmt->rowCount() > 0;
    }

    public function create($data)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (:name, :email, :password, :role, :status)");
        return $stmt->execute($data);
    }

    public function createPetugas($data)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status, telepon, username) VALUES (:name, :email, :password, :role, :status, :telepon, :username)");
        return $stmt->execute($data);
    }

    public function createRumahSakitPending($data)
    {
        global $pdo;
        $data = $this->onlyExistingUserColumns($data);
        $columns = array_keys($data);
        $placeholders = array_map(fn($column) => ':' . $column, $columns);
        $stmt = $pdo->prepare("INSERT INTO users (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")");
        return $stmt->execute($data);
    }

    public function updatePetugas($id, $data)
    {
        global $pdo;
        $data = array_filter(
            $data,
            fn($value) => $value !== null,
        );
        $sets = array_map(fn($column) => $column . ' = :' . $column, array_keys($data));
        $stmt = $pdo->prepare("UPDATE users SET " . implode(', ', $sets) . " WHERE id = :id AND role = 'petugas'");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function deletePetugas($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role = 'petugas'");
        return $stmt->execute(['id' => $id]);
    }

    public function deleteRumahSakit($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND role = 'rumahsakit'");
        return $stmt->execute(['id' => $id]);
    }

    public function updateProfil($id, $data)
    {
        global $pdo;
        $data = $this->onlyExistingUserColumns($data);
        $sets = array_map(fn($column) => $column . ' = :' . $column, array_keys($data));
        $stmt = $pdo->prepare("UPDATE users SET " . implode(', ', $sets) . " WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function updatePassword($id, $hashedPassword)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute(['password' => $hashedPassword, 'id' => $id]);
    }

    public function approveRumahSakit($id)
    {
        return $this->updateStatus($id, 'aktif');
    }

    public function rejectRumahSakit($id)
    {
        return $this->updateStatus($id, 'ditolak');
    }
}
