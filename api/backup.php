<?php
require_once __DIR__ . '/../includes/functions.php';

global $MODULES;
$backup = ['generated_at' => date('Y-m-d H:i:s')];
foreach ($MODULES as $key => $mod) {
    $backup[$key] = read_json($mod['file']);
}
$backup['settings'] = read_settings();

$filename = 'idcard_backup_' . date('Ymd_His') . '.json';
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
