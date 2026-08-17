<?php
$pageTitle = 'ID Card Design Studio';
$activePage = 'design';
require_once __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/header.php';

$settings = read_settings();
?>

<div class="page-head">
  <div>
    <h1><i class="bi bi-brush-fill" style="color:var(--primary);"></i> ID Card Design Studio</h1>
    <p>Customize card layout, colors, typography, orientation, logo, and background images in real-time.</p>
  </div>
  <button class="btn btn-primary" id="saveSettingsBtn"><i class="bi bi-check-lg"></i> Save Design Settings</button>
</div>

<div class="settings-layout">
  <div>
    <!-- CARD LAYOUT PICKER -->
    <div class="glass-card panel" style="margin-bottom:20px;">
      <h2><i class="bi bi-grid-3x3-gap-fill"></i> Card Layout Models</h2>
      <p class="hint" style="margin:0 0 14px;">Choose a category, then select one of the four card models to apply it to the live design.</p>
      <div class="layout-category-tabs" role="tablist" aria-label="Card layout categories">
        <button type="button" class="layout-category-btn active" data-layout-category="school"><i class="bi bi-mortarboard-fill"></i> School</button>
        <button type="button" class="layout-category-btn" data-layout-category="college"><i class="bi bi-bank2"></i> College</button>
        <button type="button" class="layout-category-btn" data-layout-category="office"><i class="bi bi-briefcase-fill"></i> Office</button>
        <button type="button" class="layout-category-btn" data-layout-category="hospital"><i class="bi bi-heart-pulse-fill"></i> Hospital</button>
        <button type="button" class="layout-category-btn" data-layout-category="oneSide"><i class="bi bi-credit-card"></i> One Side</button>
        <button type="button" class="layout-category-btn" data-layout-category="qrCode"><i class="bi bi-qr-code"></i> QR Code</button>
      </div>
      <div class="layout-model-grid" id="layoutModelGrid"></div>
    </div>

    <!-- 1. COLORS & TYPOGRAPHY -->
    <div class="glass-card panel" style="margin-bottom:20px;">
      <h2><i class="bi bi-palette-fill"></i> Colors & Typography</h2>
      <div class="form-grid">
        <div class="form-field">
          <label>Primary Color</label>
          <div class="color-row">
            <input type="color" id="s_primaryColor" value="<?= htmlspecialchars($settings['primaryColor']) ?>">
            <span id="primaryHex"><?= htmlspecialchars($settings['primaryColor']) ?></span>
          </div>
        </div>
        <div class="form-field">
          <label>Secondary Color</label>
          <div class="color-row">
            <input type="color" id="s_secondaryColor" value="<?= htmlspecialchars($settings['secondaryColor']) ?>">
            <span id="secondaryHex"><?= htmlspecialchars($settings['secondaryColor']) ?></span>
          </div>
        </div>
        <div class="form-field">
          <label>Font Family</label>
          <select id="s_fontFamily">
            <?php foreach ([
              'Poppins, sans-serif',
              'Inter, sans-serif',
              'Roboto, sans-serif',
              'Montserrat, sans-serif',
              'Playfair Display, serif',
              'JetBrains Mono, monospace'
            ] as $font): ?>
              <option value="<?= $font ?>" <?= $settings['fontFamily'] === $font ? 'selected' : '' ?>><?= explode(',', $font)[0] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-field">
          <label>Font Size (px)</label>
          <input type="number" id="s_fontSize" min="10" max="20" value="<?= (int)$settings['fontSize'] ?>">
        </div>

        <div class="form-field full" style="margin-top:8px;">
          <label>Quick Preset Themes (Click to apply style)</label>
          <div class="template-grid" style="grid-template-columns: repeat(auto-fill, minmax(105px, 1fr)); gap: 10px;">
            <div class="template-card" onclick="applyPresetTheme('#6366f1', '#8b5cf6', 'school', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #6366f1, #8b5cf6);"></div><div class="tname" style="font-size:11px;">School</div></div>
            <div class="template-card" onclick="applyPresetTheme('#0ea5e9', '#06b6d4', 'college', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #0ea5e9, #06b6d4);"></div><div class="tname" style="font-size:11px;">College</div></div>
            <div class="template-card" onclick="applyPresetTheme('#ec4899', '#f43f5e', 'hospital', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #ec4899, #f43f5e);"></div><div class="tname" style="font-size:11px;">Hospital</div></div>
            <div class="template-card" onclick="applyPresetTheme('#f59e0b', '#ea580c', 'office', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #f59e0b, #ea580c);"></div><div class="tname" style="font-size:11px;">Office</div></div>
            <div class="template-card" onclick="applyPresetTheme('#1e293b', '#475569', 'corporate', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #1e293b, #475569);"></div><div class="tname" style="font-size:11px;">Corporate</div></div>
            <div class="template-card" onclick="applyPresetTheme('#4f46e5', '#06b6d4', 'professional', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #4f46e5, #06b6d4);"></div><div class="tname" style="font-size:11px;">Professional</div></div>
            <div class="template-card" onclick="applyPresetTheme('#10b981', '#059669', 'emerald', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #10b981, #059669);"></div><div class="tname" style="font-size:11px;">Emerald</div></div>
            <div class="template-card" onclick="applyPresetTheme('#d97706', '#b45309', 'royalgold', this)"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #d97706, #b45309);"></div><div class="tname" style="font-size:11px;">Royal Gold</div></div>
            <div class="template-card" onclick="applyPresetTheme('#8b5cf6', '#d946ef', 'cyberpunk')"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #8b5cf6, #d946ef);"></div><div class="tname" style="font-size:11px;">Neon Cyber</div></div>
            <div class="template-card" onclick="applyPresetTheme('#dc2626', '#991b1b', 'crimson')"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #dc2626, #991b1b);"></div><div class="tname" style="font-size:11px;">Crimson</div></div>
            <div class="template-card" onclick="applyPresetTheme('#2563eb', '#0284c7', 'oceanic')"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #2563eb, #0284c7);"></div><div class="tname" style="font-size:11px;">Ocean Blue</div></div>
            <div class="template-card" onclick="applyPresetTheme('#111827', '#f59e0b', 'obsidian')"><div class="template-swatch" style="height:28px;background:linear-gradient(135deg, #111827, #f59e0b);"></div><div class="tname" style="font-size:11px;">Obsidian</div></div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. BRANDING & IMAGES -->
    <div class="glass-card panel" style="margin-bottom:20px;">
      <h2><i class="bi bi-image-fill"></i> Branding & Images</h2>
      <div class="form-grid">
        <div class="form-field">
          <label>Logo Image Upload</label>
          <input type="file" id="s_logo_file" accept="image/*">
          <span class="hint" id="logoHint" data-logo="<?= htmlspecialchars($settings['logo']) ?>"><?= $settings['logo'] ? 'Current: ' . basename($settings['logo']) : 'No logo uploaded' ?></span>
        </div>
        <div class="form-field">
          <label>Background Image Upload</label>
          <input type="file" id="s_background_file" accept="image/*">
          <span class="hint" id="bgHint"><?= $settings['background'] ? 'Current: ' . basename($settings['background']) : 'No background uploaded' ?></span>
        </div>
      </div>
    </div>

    <!-- 3. HEADINGS & ORGANIZATIONS -->
    <div class="glass-card panel" style="margin-bottom:20px;">
      <h2><i class="bi bi-building-fill"></i> Organization & Headings</h2>
      <div class="form-grid">
        <div class="form-field"><label>Company / Organization Name</label><input type="text" id="s_companyName" value="<?= htmlspecialchars($settings['companyName']) ?>"></div>
        <div class="form-field"><label>Institute / School / College Name</label><input type="text" id="s_instituteName" value="<?= htmlspecialchars($settings['instituteName']) ?>"></div>
        <div class="form-field full"><label>ID Card Heading / Subtitle</label><input type="text" id="s_cardHeading" value="<?= htmlspecialchars($settings['cardHeading'] ?? 'IDENTITY CARD') ?>" placeholder="e.g. IDENTITY CARD / STUDENT ID CARD"></div>
        <div class="form-field"><label>Address</label><input type="text" id="s_address" value="<?= htmlspecialchars($settings['address']) ?>"></div>
        <div class="form-field"><label>Website</label><input type="text" id="s_website" value="<?= htmlspecialchars($settings['website']) ?>"></div>
        <div class="form-field"><label>Email</label><input type="text" id="s_email" value="<?= htmlspecialchars($settings['email']) ?>"></div>
        <div class="form-field"><label>Phone</label><input type="text" id="s_phone" value="<?= htmlspecialchars($settings['phone']) ?>"></div>
        <div class="form-field full"><label>Footer Text</label><input type="text" id="s_footerText" value="<?= htmlspecialchars($settings['footerText']) ?>"></div>
      </div>
    </div>

    <!-- 4. LAYOUT & ORIENTATION -->
    <div class="glass-card panel">
      <h2><i class="bi bi-aspect-ratio-fill"></i> Card Layout & Orientation</h2>
      <div class="form-grid">
        <div class="form-field">
          <label>Orientation</label>
          <select id="s_orientation">
            <option value="portrait" <?= $settings['orientation'] === 'portrait' ? 'selected' : '' ?>>Portrait (Vertical)</option>
            <option value="landscape" <?= $settings['orientation'] === 'landscape' ? 'selected' : '' ?>>Landscape (Horizontal)</option>
          </select>
        </div>
        <div class="form-field">
          <label>Photo Position</label>
          <select id="s_photoPosition">
            <option value="left" <?= ($settings['photoPosition'] ?? 'left') === 'left' ? 'selected' : '' ?>>Left Side</option>
            <option value="center" <?= ($settings['photoPosition'] ?? 'left') === 'center' ? 'selected' : '' ?>>Center (Top)</option>
            <option value="bottom" <?= ($settings['photoPosition'] ?? 'left') === 'bottom' ? 'selected' : '' ?>>Photo Below Details (Kela)</option>
            <option value="right" <?= ($settings['photoPosition'] ?? 'left') === 'right' ? 'selected' : '' ?>>Right Side</option>
          </select>
        </div>
        <div class="form-field">
          <label>Logo Position</label>
          <select id="s_logoPosition">
            <option value="left" <?= ($settings['logoPosition'] ?? 'left') === 'left' ? 'selected' : '' ?>>Left Side</option>
            <option value="center" <?= ($settings['logoPosition'] ?? 'left') === 'center' ? 'selected' : '' ?>>Center Top</option>
            <option value="right" <?= ($settings['logoPosition'] ?? 'left') === 'right' ? 'selected' : '' ?>>Right Side</option>
            <option value="bottom" <?= ($settings['logoPosition'] ?? 'left') === 'bottom' ? 'selected' : '' ?>>Bottom Center</option>
            <option value="hidden" <?= ($settings['logoPosition'] ?? 'left') === 'hidden' ? 'selected' : '' ?>>Hide Logo</option>
          </select>
        </div>
        <div class="form-field full">
          <label>Logo Mode</label>
          <div class="logo-mode-actions">
            <button type="button" class="logo-mode-btn <?= ($settings['logoPosition'] ?? 'left') === 'left' ? 'selected' : '' ?>" onclick="setLogoMode('left', this)"><i class="bi bi-align-start"></i> Left</button>
            <button type="button" class="logo-mode-btn <?= ($settings['logoPosition'] ?? 'left') === 'center' ? 'selected' : '' ?>" onclick="setLogoMode('center', this)"><i class="bi bi-align-center"></i> Center</button>
            <button type="button" class="logo-mode-btn <?= ($settings['logoPosition'] ?? 'left') === 'right' ? 'selected' : '' ?>" onclick="setLogoMode('right', this)"><i class="bi bi-align-end"></i> Right</button>
            <button type="button" class="logo-mode-btn <?= ($settings['logoPosition'] ?? 'left') === 'bottom' ? 'selected' : '' ?>" onclick="setLogoMode('bottom', this)"><i class="bi bi-align-bottom"></i> Bottom</button>
            <button type="button" class="logo-mode-btn <?= ($settings['logoPosition'] ?? 'left') === 'hidden' ? 'selected' : '' ?>" onclick="setLogoMode('hidden', this)"><i class="bi bi-eye-slash"></i> Hide</button>
          </div>
        </div>
        <div class="form-field"><label>Card Width (px)</label><input type="number" id="s_cardWidth" value="<?= (int)$settings['cardWidth'] ?>"></div>
        <div class="form-field"><label>Card Height (px)</label><input type="number" id="s_cardHeight" value="<?= (int)$settings['cardHeight'] ?>"></div>
        <div class="form-field"><label>Photo Size (px)</label><input type="number" id="s_photoSize" value="<?= (int)$settings['photoSize'] ?>"></div>
        <div class="form-field"><label>Border Radius (px)</label><input type="number" id="s_borderRadius" value="<?= (int)$settings['borderRadius'] ?>"></div>
        <div class="form-field">
          <label class="chip-toggle">Shadow
            <span class="switch"><input type="checkbox" id="s_shadow" <?= $settings['shadow'] ? 'checked' : '' ?>><span class="slider-toggle"></span></span>
          </label>
        </div>
        <div class="form-field">
          <label class="chip-toggle">Show QR Code
            <span class="switch"><input type="checkbox" id="s_showQrCode" <?= $settings['showQrCode'] ? 'checked' : '' ?>><span class="slider-toggle"></span></span>
          </label>
        </div>
        <div class="form-field">
          <label class="chip-toggle">Show Barcode
            <span class="switch"><input type="checkbox" id="s_showBarcode" <?= $settings['showBarcode'] ? 'checked' : '' ?>><span class="slider-toggle"></span></span>
          </label>
        </div>
        <div class="form-field">
          <label>Barcode Position (Back)</label>
          <select id="s_barcodePosition">
            <option value="bottom" <?= ($settings['barcodePosition'] ?? 'bottom') === 'bottom' ? 'selected' : '' ?>>Bottom</option>
            <option value="left" <?= ($settings['barcodePosition'] ?? 'bottom') === 'left' ? 'selected' : '' ?>>Left Side</option>
            <option value="right" <?= ($settings['barcodePosition'] ?? 'bottom') === 'right' ? 'selected' : '' ?>>Right Side</option>
          </select>
        </div>
        <div class="form-field">
          <label>Barcode Orientation</label>
          <select id="s_barcodeOrientation">
            <option value="horizontal" <?= ($settings['barcodeOrientation'] ?? 'horizontal') === 'horizontal' ? 'selected' : '' ?>>Horizontal</option>
            <option value="vertical" <?= ($settings['barcodeOrientation'] ?? 'horizontal') === 'vertical' ? 'selected' : '' ?>>Vertical</option>
          </select>
        </div>
        <div class="form-field">
          <label>Barcode Move Left / Right (px)</label>
          <input type="number" id="s_barcodeOffsetX" min="-180" max="180" value="<?= (int)($settings['barcodeOffsetX'] ?? 0) ?>">
        </div>
        <div class="form-field">
          <label>Barcode Move Up / Down (px)</label>
          <input type="number" id="s_barcodeOffsetY" min="-140" max="140" value="<?= (int)($settings['barcodeOffsetY'] ?? 0) ?>">
        </div>
      </div>
    </div>
  </div>

  <!-- STICKY LIVE PREVIEW -->
  <div class="settings-sticky">
    <div class="glass-card panel">
      <h2><i class="bi bi-eye-fill"></i> Live Design Preview</h2>
      <div class="card-preview-wrap" id="previewWrap" style="padding:10px 0;"></div>
      <p class="hint" id="previewRecordName" style="text-align:center;"></p>
      <p class="hint" style="text-align:center;">Preview updates instantly as you tweak fonts, colors, and layout.</p>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js"></script>
<script>
window.__layoutStyle = <?= json_encode($settings['layoutStyle'] ?? '') ?>;
window.__cardSideMode = <?= json_encode($settings['cardSideMode'] ?? 'double') ?>;
window.__qrPosition = <?= json_encode($settings['qrPosition'] ?? 'footer') ?>;
</script>
<script src="assets/js/card-render.js?v=layout9"></script>
<script src="assets/js/settings.js?v=20260722-9"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>