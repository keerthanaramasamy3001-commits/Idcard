<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['backup'])) {
    json_response(['success' => false, 'message' => 'No backup file provided'], 400);
}

$content = file_get_contents($_FILES['backup']['tmp_name']);
$data = json_decode($content, true);
if (!is_array($data)) {
    json_response(['success' => false, 'message' => 'Invalid backup file']);
}

global $MODULES;
foreach ($MODULES as $key => $mod) {
    if (isset($data[$key]) && is_array($data[$key])) {
        write_json($mod['file'], $data[$key]);
    }
}
if (isset($data['settings']) && is_array($data['settings'])) {
    save_settings($data['settings']);
}

json_response(['success' => true, 'message' => 'Restore complete']);
