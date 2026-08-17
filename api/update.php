<?php
/**
 * update.php — dedicated update endpoint.
 * Accepts the same POST fields as save.php but requires an existing id.
 * (save.php also handles updates when an id is present; this is kept
 * separate to match the required API folder structure and to allow
 * status-only quick updates from the table view.)
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/fields.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid method'], 405);
}

$module = $_POST['module'] ?? '';
$id = $_POST['id'] ?? '';

if (!$module || !$id) {
    json_response(['success' => false, 'message' => 'Missing module or id'], 400);
}

$existing = find_record($module, $id);
if (!$existing) {
    json_response(['success' => false, 'message' => 'Record not found'], 404);
}

// Quick single-field update (e.g. status change from table view)
if (isset($_POST['field']) && isset($_POST['value']) && count($_POST) <= 4) {
    $data = ['id' => $id, $_POST['field'] => $_POST['value']];
    $result = save_record($module, $data);
    json_response($result);
}

// Full update — same handling as save.php
$fields = get_fields($module);
$data = ['id' => $id];
foreach ($fields as $f) {
    if ($f['type'] === 'file') continue;
    $data[$f['name']] = isset($_POST[$f['name']]) ? trim($_POST[$f['name']]) : ($existing[$f['name']] ?? '');
}

$uploaded = handle_upload('photo', $module);
$data['photo'] = $uploaded ?: ($_POST['existing_photo'] ?? $existing['photo'] ?? '');

$result = save_record($module, $data);
json_response($result);
