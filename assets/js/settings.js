/* settings.js — live preview + save for settings.php */

window.__existingLogo = document.getElementById('logoHint')?.dataset?.logo || '';
window.__existingBg = '';
const LAST_SELECTION_KEY = 'id_generator_last_selection';
let previewRecord = null;
let previewModule = 'school';
const LAYOUT_MODELS = {
  school: [
    { name: 'Classic Blue', primary: '#1d4ed8', secondary: '#38bdf8', photoPosition: 'left', orientation: 'portrait', radius: 18, style: 'wave' },
    { name: 'Playful Purple', primary: '#7c3aed', secondary: '#ec4899', photoPosition: 'center', orientation: 'portrait', radius: 28, style: 'badge' },
    { name: 'Campus Green', primary: '#047857', secondary: '#22c55e', photoPosition: 'left', orientation: 'landscape', radius: 12, style: 'split' },
    { name: 'ID Holder Wave', primary: '#343944', secondary: '#f59e0b', photoPosition: 'center', orientation: 'portrait', radius: 18, style: 'wave', layoutStyle: 'holder-wave' },
  ],
  college: [
    { name: 'Academic Navy', primary: '#0f172a', secondary: '#2563eb', photoPosition: 'left', orientation: 'landscape', radius: 10, style: 'split' },
    { name: 'Modern Cyan', primary: '#0369a1', secondary: '#06b6d4', photoPosition: 'center', orientation: 'portrait', radius: 18, style: 'wave' },
    { name: 'Scholar Maroon', primary: '#9f1239', secondary: '#e11d48', photoPosition: 'right', orientation: 'landscape', radius: 12, style: 'diagonal' },
    { name: 'Minimal Slate', primary: '#334155', secondary: '#94a3b8', photoPosition: 'left', orientation: 'portrait', radius: 8, style: 'minimal' },
  ],
  office: [
    { name: 'Executive Blue', primary: '#1e3a8a', secondary: '#3b82f6', photoPosition: 'left', orientation: 'landscape', radius: 10, style: 'split' },
    { name: 'Gold Standard', primary: '#92400e', secondary: '#f59e0b', photoPosition: 'right', orientation: 'landscape', radius: 14, style: 'wave' },
    { name: 'Black Minimal', primary: '#111827', secondary: '#64748b', photoPosition: 'left', orientation: 'portrait', radius: 6, style: 'minimal' },
    { name: 'Creative Violet', primary: '#5b21b6', secondary: '#a855f7', photoPosition: 'center', orientation: 'portrait', radius: 26, style: 'badge' },
  ],
  hospital: [
    { name: 'Medical Blue', primary: '#0369a1', secondary: '#22d3ee', photoPosition: 'left', orientation: 'landscape', radius: 14, style: 'wave' },
    { name: 'Care Pink', primary: '#be185d', secondary: '#fb7185', photoPosition: 'center', orientation: 'portrait', radius: 22, style: 'badge' },
    { name: 'Health Green', primary: '#047857', secondary: '#34d399', photoPosition: 'right', orientation: 'landscape', radius: 10, style: 'split' },
    { name: 'Clean White', primary: '#0f766e', secondary: '#5eead4', photoPosition: 'left', orientation: 'portrait', radius: 8, style: 'minimal' },
  ],
  oneSide: [
    { name: 'Student Classic', primary: '#1d4ed8', secondary: '#60a5fa', photoPosition: 'center', orientation: 'portrait', radius: 18, style: 'wave', cardSideMode: 'single' },
    { name: 'Simple White', primary: '#334155', secondary: '#94a3b8', photoPosition: 'left', orientation: 'portrait', radius: 8, style: 'minimal', cardSideMode: 'single' },
    { name: 'Bold Identity', primary: '#7c2d12', secondary: '#fb923c', photoPosition: 'center', orientation: 'portrait', radius: 24, style: 'badge', cardSideMode: 'single' },
    { name: 'Modern Teal', primary: '#0f766e', secondary: '#2dd4bf', photoPosition: 'right', orientation: 'portrait', radius: 14, style: 'diagonal', cardSideMode: 'single' },
  ],
  qrCode: [
    { name: 'QR School Left', primary: '#0f766e', secondary: '#22d3ee', photoPosition: 'right', orientation: 'portrait', radius: 14, style: 'wave', qrPosition: 'body-left', showQrCode: true },
    { name: 'QR Profile Right', primary: '#1d4ed8', secondary: '#60a5fa', photoPosition: 'left', orientation: 'portrait', radius: 18, style: 'split', qrPosition: 'body-right', showQrCode: true },
    { name: 'QR Corporate', primary: '#111827', secondary: '#14b8a6', photoPosition: 'center', orientation: 'portrait', radius: 10, style: 'minimal', qrPosition: 'body-left', showQrCode: true },
    { name: 'QR Footer', primary: '#7c3aed', secondary: '#ec4899', photoPosition: 'center', orientation: 'portrait', radius: 22, style: 'badge', qrPosition: 'footer', showQrCode: true },
  ],
};
let selectedLayoutCategory = 'school';

function renderLayoutModels(category) {
  const grid = document.getElementById('layoutModelGrid');
  if (!grid) return;
  const models = LAYOUT_MODELS[category] || [];
  grid.innerHTML = models.map((model, index) => `
    <button type="button" class="layout-model-card" data-layout-model="${index}" onclick="applyLayoutModel('${category}', ${index}, this)">
      <span class="layout-mini-card model-${model.style}" style="--model-primary:${model.primary};--model-secondary:${model.secondary};">
        <span class="mini-avatar"></span><span class="mini-title"></span><span class="mini-lines"></span>
      </span>
      <span class="layout-model-name">${model.name}</span>
    </button>`).join('');
}

function applyLayoutModel(category, index, button) {
  const model = LAYOUT_MODELS[category]?.[index];
  if (!model) return;
  document.getElementById('s_primaryColor').value = model.primary;
  document.getElementById('s_secondaryColor').value = model.secondary;
  document.getElementById('s_photoPosition').value = model.photoPosition;
  document.getElementById('s_orientation').value = model.orientation;
  document.getElementById('s_borderRadius').value = model.radius;
  window.__layoutStyle = model.layoutStyle || '';
  window.__cardSideMode = model.cardSideMode || 'double';
  window.__qrPosition = model.qrPosition || 'footer';
  if (model.showQrCode) document.getElementById('s_showQrCode').checked = true;

  const width = document.getElementById('s_cardWidth');
  const height = document.getElementById('s_cardHeight');
  if (width && height) {
    width.value = model.orientation === 'landscape' ? 420 : 340;
    height.value = model.orientation === 'landscape' ? 250 : 214;
  }
  document.querySelectorAll('.layout-model-card').forEach(card => card.classList.remove('selected'));
  button.classList.add('selected');
  updatePreview();
  showToast(`${model.name} layout applied`);
}

function setLogoMode(position, button) {
  const select = document.getElementById('s_logoPosition');
  if (!select) return;
  select.value = position;
  document.querySelectorAll('.logo-mode-btn').forEach(item => item.classList.remove('selected'));
  button.classList.add('selected');
  updatePreview();
}

function getLastSelection() {
  try {
    return JSON.parse(localStorage.getItem(LAST_SELECTION_KEY) || 'null');
  } catch (error) {
    return null;
  }
}

function updatePreview() {
  if (!previewRecord) return;
  const settings = currentSettingsFromForm();
  const { front, back } = renderIdCard(settings, previewRecord, previewModule);
  const wrap = document.getElementById('previewWrap');
  // Keep the Design and Settings previews identical to the ID Generator preview.
  wrap.innerHTML = settings.cardSideMode === 'single'
    ? `<div id="cardFront">${front}</div>`
    : `<div id="cardFront">${front}</div><div id="cardBack">${back}</div>`;
  drawCodes(settings, previewRecord);
  document.getElementById('primaryHex').textContent = settings.primaryColor;
  document.getElementById('secondaryHex').textContent = settings.secondaryColor;

  const recordName = document.getElementById('previewRecordName');
  if (recordName) recordName.textContent = `Previewing: ${previewRecord.name || 'Selected record'} (${previewRecord.id || 'No ID'})`;
}

async function loadPreviewRecord() {
  const lastSelection = getLastSelection();

  if (lastSelection?.moduleKey && lastSelection?.id) {
    try {
      const result = await apiGet(`api/read.php?module=${encodeURIComponent(lastSelection.moduleKey)}&id=${encodeURIComponent(lastSelection.id)}`);
      if (result.success && result.record) {
        previewRecord = result.record;
        previewModule = lastSelection.moduleKey;
        updatePreview();
        return;
      }
    } catch (error) { /* Fall back to the newest school record. */ }
  }

  try {
    const result = await apiGet('api/read.php?module=school');
    if (result.success && result.records.length) {
      previewRecord = result.records[result.records.length - 1];
    }
  } catch (error) { /* Use a blank preview if records cannot be loaded. */ }

  previewRecord = previewRecord || { id: '', name: 'No record selected' };
  previewModule = 'school';
  updatePreview();
}

document.addEventListener('DOMContentLoaded', () => {
  loadPreviewRecord();

  const categoryButtons = document.querySelectorAll('[data-layout-category]');
  if (categoryButtons.length) {
    renderLayoutModels(selectedLayoutCategory);
    categoryButtons.forEach(button => {
      button.addEventListener('click', () => {
        selectedLayoutCategory = button.dataset.layoutCategory;
        categoryButtons.forEach(item => item.classList.toggle('active', item === button));
        renderLayoutModels(selectedLayoutCategory);
      });
    });
  }

  const inputs = document.querySelectorAll('#s_primaryColor, #s_secondaryColor, #s_fontFamily, #s_fontSize, #s_showQrCode, #s_showBarcode, #s_barcodePosition, #s_barcodeOrientation, #s_barcodeOffsetX, #s_barcodeOffsetY, #s_photoSize, #s_borderRadius, #s_shadow, #s_cardWidth, #s_cardHeight, #s_orientation, #s_photoPosition, #s_logoPosition, #s_companyName, #s_instituteName, #s_address, #s_website, #s_email, #s_phone, #s_cardHeading, #s_footerText');
  inputs.forEach(el => {
    if (!el) return;
    el.addEventListener('input', updatePreview);
    el.addEventListener('change', updatePreview);
  });

  const logoPosition = document.getElementById('s_logoPosition');
  if (logoPosition) {
    logoPosition.addEventListener('change', () => {
      document.querySelectorAll('.logo-mode-btn').forEach(button => {
        button.classList.toggle('selected', button.textContent.trim().toLowerCase() === logoPosition.value);
      });
    });
  }

  const orientSelect = document.getElementById('s_orientation');
  if (orientSelect) {
    orientSelect.addEventListener('change', () => {
      const wEl = document.getElementById('s_cardWidth');
      const hEl = document.getElementById('s_cardHeight');
      if (wEl && hEl) {
        if (orientSelect.value === 'landscape') {
          if (parseInt(wEl.value) < parseInt(hEl.value) || (parseInt(wEl.value) === 340 && parseInt(hEl.value) === 214)) {
            wEl.value = 420;
            hEl.value = 250;
          }
        } else {
          if (parseInt(wEl.value) > parseInt(hEl.value) || (parseInt(wEl.value) === 420 && parseInt(hEl.value) === 250)) {
            wEl.value = 340;
            hEl.value = 214;
          }
        }
      }
      updatePreview();
    });
  }

  const logoInput = document.getElementById('s_logo_file');
  if (logoInput) {
    logoInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (evt) {
          window.__existingLogo = evt.target.result;
          updatePreview();
        };
        reader.readAsDataURL(file);
      }
    });
  }

  document.getElementById('saveSettingsBtn').addEventListener('click', saveSettings);
});

async function saveSettings() {
  const btn = document.getElementById('saveSettingsBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Saving...';

  const fd = new FormData();
  const settings = currentSettingsFromForm();
  Object.entries(settings).forEach(([k, v]) => fd.append(k, v));

  const logoFile = document.getElementById('s_logo_file').files[0];
  if (logoFile) fd.append('logo_file', logoFile);
  const bgFile = document.getElementById('s_background_file').files[0];
  if (bgFile) fd.append('background_file', bgFile);

  try {
    const result = await apiPost('api/settings.php', fd);
    if (result.success) {
      showToast('Settings saved successfully');
    } else {
      showToast(result.message || 'Failed to save settings', 'error');
    }
  } catch (e) {
    showToast('Network error while saving settings', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-lg"></i> Save Settings';
  }
}
