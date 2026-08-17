<?php
$pageTitle = 'ID Card Templates';
$activePage = 'templates';
require_once __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/header.php';

$settings = read_settings();
$templates = [
    ['key' => 'professional', 'name' => 'Professional', 'desc' => 'Clean gradient layout for any organization', 'colors' => ['#4f46e5', '#06b6d4']],
    ['key' => 'corporate',    'name' => 'Corporate',    'desc' => 'Sharp, minimal, dark tones for offices', 'colors' => ['#1e293b', '#475569']],
    ['key' => 'school',       'name' => 'School',       'desc' => 'Bright, playful, rounded corners', 'colors' => ['#6366f1', '#8b5cf6']],
    ['key' => 'college',      'name' => 'College',      'desc' => 'Modern academic blue-cyan gradient', 'colors' => ['#0ea5e9', '#06b6d4']],
    ['key' => 'hospital',     'name' => 'Hospital',     'desc' => 'Soft, minimal, medical pink-red accents', 'colors' => ['#ec4899', '#f43f5e']],
    ['key' => 'office',       'name' => 'Office',       'desc' => 'Bold rounded design with warm tones', 'colors' => ['#f59e0b', '#ea580c']],
];
?>

<div class="page-head">
  <div>
    <h1>ID Card Templates</h1>
    <p>Six ready-made templates — each with front & back layouts, portrait/landscape, and rounded/minimal styles.</p>
  </div>
</div>

<div class="glass-card panel">
  <div class="template-grid">
    <?php foreach ($templates as $t): ?>
      <div class="template-card <?= $settings['template'] === $t['key'] ? 'selected' : '' ?>" onclick="selectTemplate('<?= $t['key'] ?>', this)">
        <div class="template-swatch" style="background:linear-gradient(135deg, <?= $t['colors'][0] ?>, <?= $t['colors'][1] ?>);"></div>
        <div class="tname"><?= $t['name'] ?></div>
        <div class="hint"><?= $t['desc'] ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="glass-card panel" style="margin-top:20px;">
  <h2><i class="bi bi-info-circle"></i> Template Features</h2>
  <p class="hint" style="font-size:12.5px;">Every template supports: Front Side · Back Side · Portrait & Landscape orientation · Rounded / Modern / Minimal border styles — all configurable from the <a href="settings.php" style="color:var(--primary);font-weight:600;">Settings</a> page. Selecting a template here sets it as your default; you can still override colors per-card in the <a href="id-generator.php" style="color:var(--primary);font-weight:600;">ID Card Generator</a>.</p>
</div>

<script>
async function selectTemplate(key, el) {
  document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  const fd = new FormData();
  fd.append('template', key);
  const result = await apiPost('api/settings.php', fd);
  if (result.success) showToast(`"${key}" set as default template`);
}
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js"></script>
<script src="assets/js/card-render.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
