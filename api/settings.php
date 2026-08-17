<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid method'], 405);
}

$data = $_POST;
unset($data['logo_file'], $data['background_file']);

$logo = handle_upload('logo_file', 'logo');
if ($logo) $data['logo'] = $logo;

$bg = handle_upload('background_file', 'bg');
if ($bg) $data['background'] = $bg;

foreach (['showQrCode', 'showBarcode', 'shadow'] as $bool) {
    $data[$bool] = isset($_POST[$bool]) && $_POST[$bool] === 'true';
}

$settings = save_settings($data);
json_response(['success' => true, 'settings' => $settings]);
