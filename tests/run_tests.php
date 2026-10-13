<?php

require_once __DIR__ . '/../core/bootstrap.php';

echo "========================================\n";
echo "   SIDORA-k Architecture Test Suite\n";
echo "========================================\n\n";

$passed = 0;
$failed = 0;

function assertTest(string $name, bool $condition, string $details = '') {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] {$name}\n";
        $passed++;
    } else {
        echo "❌ [FAIL] {$name} - {$details}\n";
        $failed++;
    }
}

// ----------------------------------------------------
// Test 1: Logger
// ----------------------------------------------------
echo "\n--- Testing Logger ---\n";
Log::info("Test info message", ['module' => 'test_suite']);
Log::warning("Test warning message", ['attempt' => 1]);
Log::error("Test error message", ['code' => 'TEST_ERR']);

$today = date('Y-m-d');
$logFile = __DIR__ . "/../storage/logs/sidora-{$today}.log";
assertTest("Log file exists", file_exists($logFile), "Path: {$logFile}");

if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    assertTest("Log contains info message", str_contains($content, 'Test info message'));
    assertTest("Log contains warning message", str_contains($content, 'Test warning message'));
    assertTest("Log contains context JSON", str_contains($content, '"module":"test_suite"'));
}

// ----------------------------------------------------
// Test 2: Validator
// ----------------------------------------------------
echo "\n--- Testing Validator ---\n";

$dataValid = [
    'nama'     => 'Dr. Budi Santoso',
    'email'    => 'budi@rsud.co.id',
    'golongan' => 'A',
    'jumlah'   => 5,
];

$v1 = Validator::make($dataValid, [
    'nama'     => 'required|min:3',
    'email'    => 'required|email',
    'golongan' => 'required|in:A,B,AB,O',
    'jumlah'   => 'required|numeric|min:1',
]);

assertTest("Valid data passes validator", $v1->passes());
assertTest("Valid data returns correct validated array", $v1->validated()['email'] === 'budi@rsud.co.id');

$dataInval = [
    'nama'     => '',
    'email'    => 'bukan-email',
    'golongan' => 'X',
    'jumlah'   => -2,
];

$v2 = Validator::make($dataInval, [
    'nama'     => 'required|min:3',
    'email'    => 'required|email',
    'golongan' => 'required|in:A,B,AB,O',
    'jumlah'   => 'required|numeric|min:1',
]);

assertTest("Invalid data fails validator", $v2->fails());
assertTest("Error on nama required", isset($v2->errors()['nama']));
assertTest("Error on email format", isset($v2->errors()['email']));
assertTest("Error on golongan invalid", isset($v2->errors()['golongan']));
assertTest("Error on jumlah min", isset($v2->errors()['jumlah']));
assertTest("Indonesian error message", str_contains($v2->first('nama'), 'wajib diisi'));

// ----------------------------------------------------
// Test 3: Notifications / Flash Messages
// ----------------------------------------------------
echo "\n--- Testing Notifications ---\n";

flash('success', 'Operasi donor darah berhasil!');
flash('error', 'Terjadi kesalahan sistem.');

assertTest("Flash stored in session", !empty($_SESSION['flash']['success']));

ob_start();
render_alerts();
$alertsHtml = ob_get_clean();

assertTest("Render alerts produces HTML", !empty($alertsHtml));
assertTest("Render alerts contains success message", str_contains($alertsHtml, 'Operasi donor darah berhasil!'));
assertTest("Render alerts contains error message", str_contains($alertsHtml, 'Terjadi kesalahan sistem.'));
assertTest("Session flash cleared after render", empty($_SESSION['flash']));

// ----------------------------------------------------
// Test 4: Helpers (old, error, has_error)
// ----------------------------------------------------
echo "\n--- Testing Form Helpers ---\n";

$_SESSION['old'] = ['nama' => 'John Doe'];
$_SESSION['errors'] = ['email' => ['Format Email tidak valid.']];

assertTest("old() retrieves previous input", old('nama') === 'John Doe');
assertTest("old() fallback works", old('nonexistent', 'default') === 'default');
assertTest("has_error() returns true for field with error", has_error('email') === true);
assertTest("has_error() returns false for valid field", has_error('nama') === false);
assertTest("error() returns error text", error('email') === 'Format Email tidak valid.');

// Clean test session
unset($_SESSION['old'], $_SESSION['errors']);

// ----------------------------------------------------
// Test 5: Error Views Rendering
// ----------------------------------------------------
echo "\n--- Testing Error Views ---\n";

$errorViews = [403, 404, 419, 500];
foreach ($errorViews as $code) {
    $viewPath = __DIR__ . "/../views/errors/{$code}.php";
    assertTest("View file exists for {$code}", file_exists($viewPath));
    
    // Test rendering
    ob_start();
    $message = "Test message for {$code}";
    include $viewPath;
    $output = ob_get_clean();
    assertTest("View {$code} renders properly", str_contains($output, "KODE ERROR {$code}"));
}

// ----------------------------------------------------
// Test Summary
// ----------------------------------------------------
echo "\n========================================\n";
echo "Test Summary: Passed: {$passed}, Failed: {$failed}\n";
echo "========================================\n";

if ($failed > 0) {
    exit(1);
}
