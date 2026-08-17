<?php
require_once __DIR__ . '/../includes/functions.php';

$module = $_GET['module'] ?? '';
if (!get_module($module)) {
    json_response(['success' => false, 'message' => 'Invalid module'], 400);
}

json_response(['success' => true, 'id' => generate_id($module)]);
