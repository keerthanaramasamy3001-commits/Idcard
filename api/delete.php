<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid method'], 405);
}

$module = $_POST['module'] ?? '';
$id = $_POST['id'] ?? '';

if (!$module || !$id) {
    json_response(['success' => false, 'message' => 'Missing module or id'], 400);
}

$result = delete_record($module, $id);
json_response($result);
