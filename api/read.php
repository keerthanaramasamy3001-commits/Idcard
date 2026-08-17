<?php
require_once __DIR__ . '/../includes/functions.php';

$module = $_GET['module'] ?? '';
$mod = get_module($module);
if (!$mod) {
    json_response(['success' => false, 'message' => 'Invalid module'], 400);
}

$records = read_json($mod['file']);

if (!empty($_GET['id'])) {
    foreach ($records as $r) {
        if ($r['id'] === $_GET['id']) {
            json_response(['success' => true, 'record' => $r]);
        }
    }
    json_response(['success' => false, 'message' => 'Record not found'], 404);
}

json_response(['success' => true, 'records' => $records]);
