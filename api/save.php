<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/fields.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid method'], 405);
}

$module = $_POST['module'] ?? '';
$fields = get_fields($module);
if (!$fields) {
    json_response(['success' => false, 'message' => 'Invalid module'], 400);
}

$data = [];
if (!empty($_POST['id'])) {
    $data['id'] = trim($_POST['id']);
}

foreach ($fields as $f) {
    if ($f['type'] === 'file') continue;
    $data[$f['name']] = isset($_POST[$f['name']]) ? trim($_POST[$f['name']]) : '';
}

// required validation
foreach ($fields as $f) {
    if (!empty($f['required']) && empty($data[$f['name']])) {
        json_response(['success' => false, 'message' => $f['label'] . ' is required']);
    }
}

// photo upload
$uploaded = handle_upload('photo', $module);
if ($uploaded) {
    $data['photo'] = $uploaded;
} elseif (!empty($_POST['existing_photo'])) {
    $data['photo'] = $_POST['existing_photo'];
} else {
    $data['photo'] = '';
}

$result = save_record($module, $data);
json_response($result);
