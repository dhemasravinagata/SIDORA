<?php

namespace Core;

use Core\Exceptions\ValidationException;
use PDO;

class Validator
{
    protected array $data = [];
    protected array $rules = [];
    protected array $customMessages = [];
    protected array $customAttributes = [];
    protected array $errors = [];
    protected array $validatedData = [];

    protected array $defaultAttributes = [
        'nama'            => 'Nama',
        'nama_rs'         => 'Nama Rumah Sakit',
        'email'           => 'Email',
        'password'        => 'Password',
        'password_konfirm'=> 'Konfirmasi Password',
        'username'        => 'Username',
        'nik'             => 'NIK',
        'no_identitas'    => 'Nomor Identitas',
        'golongan'        => 'Golongan Darah',
        'golongan_darah'  => 'Golongan Darah',
        'rhesus'          => 'Rhesus',
        'telepon'         => 'Nomor Telepon',
        'kontak'          => 'Kontak Person',
        'alamat'          => 'Alamat',
        'desa'            => 'Desa / Kelurahan',
        'kecamatan'       => 'Kecamatan',
        'kota'            => 'Kota / Kabupaten',
        'provinsi'        => 'Provinsi',
        'kode_pos'        => 'Kode Pos',
        'pekerjaan'       => 'Pekerjaan',
        'tanggal_lahir'   => 'Tanggal Lahir',
        'jenis_kelamin'   => 'Jenis Kelamin',
        'tipe_rs'         => 'Tipe Rumah Sakit',
        'no_izin'         => 'Nomor Izin Operasional',
        'jumlah'          => 'Jumlah Kantong Darah',
        'prioritas'       => 'Prioritas',
        'keterangan'      => 'Keterangan',
        'tanggal'         => 'Tanggal',
        'lokasi'          => 'Lokasi',
        'target'          => 'Target Pendonor',
        'waktu_mulai'     => 'Waktu Mulai',
        'waktu_selesai'   => 'Waktu Selesai',
        'alasan_tolak'    => 'Alasan Penolakan',
        'kurir'           => 'Nama Kurir',
    ];

    public function __construct(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->customMessages = $messages;
        $this->customAttributes = array_merge($this->defaultAttributes, $customAttributes);
        $this->run();
    }

    public static function make(array $data, array $rules, array $messages = [], array $customAttributes = []): self
    {
        return new self($data, $rules, $messages, $customAttributes);
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function first(?string $key = null): ?string
    {
        if ($key === null) {
            foreach ($this->errors as $messages) {
                return $messages[0] ?? null;
            }
            return null;
        }

        return $this->errors[$key][0] ?? null;
    }

    public function validated(): array
    {
        return $this->validatedData;
    }

    /**
     * Throw ValidationException if validation fails, exactly like Laravel's $request->validate()
     */
    public function validate(?string $redirectTo = null): array
    {
        if ($this->fails()) {
            throw new ValidationException($this->errors(), $redirectTo);
        }

        return $this->validatedData;
    }

    protected function run(): void
    {
        foreach ($this->rules as $field => $ruleDefinition) {
            $rulesList = is_array($ruleDefinition) ? $ruleDefinition : explode('|', $ruleDefinition);
            $value = $this->data[$field] ?? null;

            foreach ($rulesList as $ruleItem) {
                $ruleItem = trim($ruleItem);
                if ($ruleItem === '') continue;

                $parameters = [];
                if (str_contains($ruleItem, ':')) {
                    [$ruleName, $paramStr] = explode(':', $ruleItem, 2);
                    $parameters = str_getcsv($paramStr);
                } else {
                    $ruleName = $ruleItem;
                }

                $method = 'validate' . str_replace('_', '', ucwords($ruleName, '_'));

                if (method_exists($this, $method)) {
                    $passed = $this->$method($field, $value, $parameters);
                    if (!$passed) {
                        $this->addError($field, $ruleName, $parameters);
                        // Stop checking subsequent rules for this field once one fails
                        break;
                    }
                }
            }

            if (!isset($this->errors[$field]) && array_key_exists($field, $this->data)) {
                $this->validatedData[$field] = $value;
            }
        }
    }

    protected function validateRequired(string $field, mixed $value, array $params): bool
    {
        if ($value === null) return false;
        if (is_string($value) && trim($value) === '') return false;
        if (is_array($value) && empty($value)) return false;
        return true;
    }

    protected function validateEmail(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function validateNumeric(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        return is_numeric($value);
    }

    protected function validateInteger(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    protected function validateMin(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        $min = (int)($params[0] ?? 0);

        if (is_numeric($value)) {
            return (float)$value >= $min;
        }

        return mb_strlen((string)$value) >= $min;
    }

    protected function validateMax(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        $max = (int)($params[0] ?? 0);

        if (is_numeric($value)) {
            return (float)$value <= $max;
        }

        return mb_strlen((string)$value) <= $max;
    }

    protected function validateIn(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        return in_array((string)$value, $params, true);
    }

    protected function validateSame(string $field, mixed $value, array $params): bool
    {
        $otherField = $params[0] ?? '';
        $otherValue = $this->data[$otherField] ?? null;
        return $value === $otherValue;
    }

    protected function validateDate(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        return strtotime((string)$value) !== false;
    }

    protected function validateUnique(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        global $pdo;
        if (!$pdo) return true;

        $table = $params[0] ?? '';
        $column = $params[1] ?? $field;
        $ignoreId = $params[2] ?? null;
        $idColumn = $params[3] ?? 'id';

        if (!$table || !$column) return true;

        try {
            $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :val";
            $bindings = ['val' => $value];

            if ($ignoreId !== null && $ignoreId !== '') {
                $sql .= " AND `{$idColumn}` != :ignore_id";
                $bindings['ignore_id'] = $ignoreId;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($bindings);
            return (int)$stmt->fetchColumn() === 0;
        } catch (\Throwable $e) {
            Logger::error("Validation unique query failed: " . $e->getMessage());
            return true;
        }
    }

    protected function validateExists(string $field, mixed $value, array $params): bool
    {
        if ($value === null || $value === '') return true;
        global $pdo;
        if (!$pdo) return true;

        $table = $params[0] ?? '';
        $column = $params[1] ?? $field;

        if (!$table || !$column) return true;

        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :val");
            $stmt->execute(['val' => $value]);
            return (int)$stmt->fetchColumn() > 0;
        } catch (\Throwable $e) {
            Logger::error("Validation exists query failed: " . $e->getMessage());
            return true;
        }
    }

    protected function addError(string $field, string $rule, array $params): void
    {
        $attributeName = $this->customAttributes[$field] ?? ucwords(str_replace('_', ' ', $field));

        // Check custom message: field.rule or rule
        $customKey = "{$field}.{$rule}";
        if (isset($this->customMessages[$customKey])) {
            $message = $this->customMessages[$customKey];
        } elseif (isset($this->customMessages[$rule])) {
            $message = $this->customMessages[$rule];
        } else {
            $message = $this->getDefaultMessage($rule);
        }

        // Replace placeholders
        $message = str_replace(':attribute', $attributeName, $message);
        if (isset($params[0])) {
            $message = str_replace(':min', $params[0], $message);
            $message = str_replace(':max', $params[0], $message);
            $message = str_replace(':other', $this->customAttributes[$params[0]] ?? $params[0], $message);
        }

        $this->errors[$field][] = $message;
    }

    protected function getDefaultMessage(string $rule): string
    {
        return match ($rule) {
            'required' => ':attribute wajib diisi.',
            'email'    => 'Format :attribute tidak valid.',
            'numeric'  => ':attribute harus berupa angka.',
            'integer'  => ':attribute harus berupa bilangan bulat.',
            'min'      => ':attribute minimal :min karakter/nilai.',
            'max'      => ':attribute maksimal :max karakter/nilai.',
            'in'       => ':attribute yang dipilih tidak valid.',
            'same'     => ':attribute harus sama dengan :other.',
            'date'     => ':attribute bukan format tanggal yang valid.',
            'unique'   => ':attribute sudah digunakan dalam sistem.',
            'exists'   => ':attribute yang dipilih tidak ditemukan.',
            default    => ':attribute tidak valid.',
        };
    }
}
