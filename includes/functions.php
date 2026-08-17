<?php
/**
 * Smart ID Card Management System
 * Core helper functions: JSON storage CRUD, ID generation, uploads
 */

define('JSON_DIR', __DIR__ . '/../json/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', 'uploads/');

$MODULES = [
    'school'   => ['file' => 'school.json',   'prefix' => 'SCH', 'label' => 'School',   'nameField' => 'name'],
    'college'  => ['file' => 'college.json',  'prefix' => 'COL', 'label' => 'College',  'nameField' => 'name'],
    'office'   => ['file' => 'office.json',   'prefix' => 'EMP', 'label' => 'Office',   'nameField' => 'name'],
    'hospital' => ['file' => 'hospital.json', 'prefix' => 'PAT', 'label' => 'Hospital', 'nameField' => 'name'],
];

/** Ensure json storage files exist */
function ensure_storage() {
    global $MODULES;
    if (!is_dir(JSON_DIR)) mkdir(JSON_DIR, 0777, true);
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0777, true);
    foreach ($MODULES as $m) {
        $path = JSON_DIR . $m['file'];
        if (!file_exists($path)) file_put_contents($path, json_encode([]));
    }
    if (!file_exists(JSON_DIR . 'settings.json')) {
        file_put_contents(JSON_DIR . 'settings.json', json_encode(default_settings(), JSON_PRETTY_PRINT));
    }
}

function default_settings() {
    return [
        'logo' => '',
        'background' => '',
        'primaryColor' => '#4f46e5',
        'secondaryColor' => '#06b6d4',
        'fontFamily' => 'Poppins, sans-serif',
        'fontSize' => 14,
        'showQrCode' => true,
        'showBarcode' => true,
        'photoSize' => 90,
        'borderRadius' => 16,
        'shadow' => true,
        'cardWidth' => 340,
        'cardHeight' => 214,
        'orientation' => 'portrait',
        'companyName' => 'Smart ID Systems',
        'instituteName' => 'Greenfield Institute',
        'address' => '123 Main Street, City',
        'website' => 'www.example.com',
        'email' => 'info@example.com',
        'phone' => '+91 90000 00000',
        'footerText' => 'This card is property of the issuing organization. If found, please return.',
        'cardHeading' => 'IDENTITY CARD',
        'photoPosition' => 'center',
        'template' => 'professional'
    ];
}

/** Read a JSON storage file, returns array of records */
function read_json($file) {
    $path = JSON_DIR . $file;
    if (!file_exists($path)) return [];
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : [];
}

/** Write array of records back to a JSON storage file (atomic-ish via temp file) */
function write_json($file, $data) {
    $path = JSON_DIR . $file;
    $tmp = $path . '.tmp';
    file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    rename($tmp, $path);
}

/** Get module config by key, or null */
function get_module($key) {
    global $MODULES;
    return $MODULES[$key] ?? null;
}

/** Generate the next auto-incrementing ID for a module, e.g. SCH0001 */
function generate_id($moduleKey) {
    $mod = get_module($moduleKey);
    if (!$mod) return null;
    $records = read_json($mod['file']);
    $max = 0;
    foreach ($records as $r) {
        if (isset($r['id']) && preg_match('/^' . $mod['prefix'] . '(\d+)$/', $r['id'], $m)) {
            $num = (int)$m[1];
            if ($num > $max) $max = $num;
        }
    }
    $next = $max + 1;
    return $mod['prefix'] . str_pad($next, 4, '0', STR_PAD_LEFT);
}

/** Find a record by id within a module */
function find_record($moduleKey, $id) {
    $mod = get_module($moduleKey);
    if (!$mod) return null;
    $records = read_json($mod['file']);
    foreach ($records as $r) {
        if ($r['id'] === $id) return $r;
    }
    return null;
}

/** Save (create or update) a record */
function save_record($moduleKey, $data) {
    $mod = get_module($moduleKey);
    if (!$mod) return ['success' => false, 'message' => 'Invalid module'];

    $records = read_json($mod['file']);
    $now = date('Y-m-d H:i:s');

    if (!empty($data['id'])) {
        // update existing
        $found = false;
        foreach ($records as &$r) {
            if ($r['id'] === $data['id']) {
                $data['created_at'] = $r['created_at'] ?? $now;
                $data['updated_at'] = $now;
                $r = array_merge($r, $data);
                $found = true;
                break;
            }
        }
        unset($r);
        if (!$found) return ['success' => false, 'message' => 'Record not found'];
    } else {
        // create new
        $data['id'] = generate_id($moduleKey);
        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        $records[] = $data;
    }

    write_json($mod['file'], $records);
    return ['success' => true, 'record' => $data];
}

/** Delete a record by id */
function delete_record($moduleKey, $id) {
    $mod = get_module($moduleKey);
    if (!$mod) return ['success' => false, 'message' => 'Invalid module'];

    $records = read_json($mod['file']);
    $before = count($records);
    $records = array_values(array_filter($records, fn($r) => $r['id'] !== $id));
    write_json($mod['file'], $records);

    if (count($records) < $before) {
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Record not found'];
}

/** Handle a single file upload, returns relative path or null */
function handle_upload($fieldName, $prefix = 'img') {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0777, true);
    $filename = $prefix . '_' . uniqid() . '.' . $ext;
    $dest = UPLOAD_DIR . $filename;
    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $dest)) {
        return UPLOAD_URL . $filename;
    }
    return null;
}

/** Read settings.json */
function read_settings() {
    $path = JSON_DIR . 'settings.json';
    if (!file_exists($path)) return default_settings();
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? array_merge(default_settings(), $data) : default_settings();
}

/** Save settings.json */
function save_settings($data) {
    $settings = array_merge(read_settings(), $data);
    file_put_contents(JSON_DIR . 'settings.json', json_encode($settings, JSON_PRETTY_PRINT));
    return $settings;
}

/** JSON response helper */
function json_response($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/** Get dashboard stats across all modules */
function get_stats() {
    global $MODULES;
    $stats = [];
    $total = 0;
    $recent = [];
    foreach ($MODULES as $key => $mod) {
        $records = read_json($mod['file']);
        $stats[$key] = count($records);
        $total += count($records);
        foreach ($records as $r) {
            $r['_module'] = $key;
            $recent[] = $r;
        }
    }
    usort($recent, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
    $stats['total'] = $total;
    $stats['recent'] = array_slice($recent, 0, 8);
    return $stats;
}

ensure_storage();
