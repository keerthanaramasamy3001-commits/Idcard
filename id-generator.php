<?php
$pageTitle = 'ID Card Generator';
$activePage = 'id-generator';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/fields.php';
require __DIR__ . '/includes/header.php';

$settings = read_settings();
$preModule = $_GET['module'] ?? 'school';
$preId = $_GET['id'] ?? '';
$modules = ['school' => 'School', 'college' => 'College', 'office' => 'Office', 'hospital' => 'Hospital'];
?>

<div class="page-head">
  <div>
    <h1>ID Card Generator</h1>
    <p>Select a registered record and generate a print-ready ID card instantly — no manual typing.</p>
  </div>
</div>

<div class="settings-layout">
  <div class="glass-card panel">
    <h2><i class="bi bi-search"></i> Select Record</h2>
    <div class="form-grid" style="margin-bottom:18px;">
      <div class="form-field">
        <label>Organization Type</label>
        <select id="genModule">
          <?php foreach ($modules as $key => $label): ?>
            <option value="<?= $key ?>" <?= $preModule === $key ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-field">
        <label>Select Record</label>
        <select id="genRecord"><option>Loading...</option></select>
      </div>
      <div class="form-field">
        <label>Template</label>
        <select id="genTemplate">
          <option value="professional">Professional</option>
          <option value="corporate">Corporate</option>
          <option value="school">School Classic</option>
          <option value="college">College Modern</option>
          <option value="hospital">Hospital Minimal</option>
          <option value="office">Office Rounded</option>
        </select>
      </div>
    </div>
    <button class="btn btn-primary btn-sm" id="saveCardBtn" type="button" onclick="saveCardFile()" disabled style="margin-bottom:18px;"><i class="bi bi-floppy"></i> Save File</button>

    <div class="empty-state" id="noRecordMsg">
      <i class="bi bi-credit-card-2-front"></i>
      <p>Select an organization and record above to generate the card.</p>
    </div>

    <div id="cardActions" style="display:none;">
      <div class="section-divider"></div>
      <h2><i class="bi bi-printer"></i> Print & Export</h2>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn btn-ghost btn-sm" onclick="printSide('front')"><i class="bi bi-printer"></i> Print Front</button>
        <button class="btn btn-ghost btn-sm" onclick="printSide('back')"><i class="bi bi-printer"></i> Print Back</button>
        <button class="btn btn-ghost btn-sm" onclick="downloadCard('pdf')"><i class="bi bi-file-earmark-pdf"></i> Download PDF</button>
        <button class="btn btn-ghost btn-sm" onclick="downloadCard('png')"><i class="bi bi-file-earmark-image"></i> Download PNG</button>
        <button class="btn btn-ghost btn-sm" onclick="downloadCard('jpg')"><i class="bi bi-file-earmark-image"></i> Download JPG</button>
      </div>
    </div>
  </div>

  <div class="settings-sticky">
    <div class="glass-card panel">
      <h2><i class="bi bi-eye"></i> Card Preview</h2>
      <div class="card-preview-wrap" id="cardPreviewWrap"></div>
    </div>
  </div>
</div>

<script>
const ALL_SETTINGS = <?= json_encode($settings) ?>;
const PRESET_MODULE = <?= json_encode($preModule) ?>;
const PRESET_ID = <?= json_encode($preId) ?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="assets/js/card-render.js?v=layout9"></script>
<script src="assets/js/id-generator.js?v=layout3"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
