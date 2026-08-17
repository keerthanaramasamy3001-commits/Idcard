/* id-generator.js — record selection + live card preview + export */

let currentRecords = [];
let currentRecord = null;
const LAST_SELECTION_KEY = 'id_generator_last_selection';

function getLastSelection() {
  try {
    return JSON.parse(localStorage.getItem(LAST_SELECTION_KEY) || 'null');
  } catch (error) {
    return null;
  }
}

function rememberSelection(moduleKey, id) {
  localStorage.setItem(LAST_SELECTION_KEY, JSON.stringify({ moduleKey, id }));
}

async function loadRecordsForModule(moduleKey) {
  const sel = document.getElementById('genRecord');
  sel.innerHTML = '<option>Loading...</option>';
  const result = await apiGet(`api/read.php?module=${moduleKey}`);
  currentRecords = result.success ? result.records : [];

  if (!currentRecords.length) {
    sel.innerHTML = '<option value="">No records found</option>';
    showEmptyState();
    return;
  }
  sel.innerHTML = currentRecords.map(r => `<option value="${r.id}">${r.name || 'Unnamed'} — ${r.id}</option>`).join('');

  const lastSelection = getLastSelection();
  const requestedRecordExists = PRESET_ID && currentRecords.some(r => r.id === PRESET_ID);
  const savedRecordExists = lastSelection?.moduleKey === moduleKey
    && currentRecords.some(r => r.id === lastSelection.id);

  if (requestedRecordExists) {
    sel.value = PRESET_ID;
  } else if (savedRecordExists) {
    sel.value = lastSelection.id;
  } else {
    // Records are saved chronologically, so use the most recently added one by default.
    sel.value = currentRecords[currentRecords.length - 1].id;
  }
  onRecordChange();
}

function showEmptyState() {
  document.getElementById('noRecordMsg').style.display = 'block';
  document.getElementById('cardActions').style.display = 'none';
  document.getElementById('cardPreviewWrap').innerHTML = '';
  document.getElementById('saveCardBtn').disabled = true;
}

function onRecordChange() {
  const id = document.getElementById('genRecord').value;
  currentRecord = currentRecords.find(r => r.id === id);
  if (!currentRecord) { showEmptyState(); return; }

  document.getElementById('noRecordMsg').style.display = 'none';
  document.getElementById('cardActions').style.display = 'block';
  document.getElementById('saveCardBtn').disabled = false;
  rememberSelection(document.getElementById('genModule').value, currentRecord.id);
  renderPreview();
}

function templatePreset(name) {
  const presets = {
    professional: { primaryColor: '#4f46e5', secondaryColor: '#06b6d4' },
    corporate:    { primaryColor: '#1e293b', secondaryColor: '#475569' },
    school:       { primaryColor: '#6366f1', secondaryColor: '#8b5cf6' },
    college:      { primaryColor: '#0ea5e9', secondaryColor: '#06b6d4' },
    hospital:     { primaryColor: '#ec4899', secondaryColor: '#f43f5e' },
    office:       { primaryColor: '#f59e0b', secondaryColor: '#ea580c' },
    emerald:      { primaryColor: '#10b981', secondaryColor: '#059669' },
    royalgold:    { primaryColor: '#d97706', secondaryColor: '#b45309' },
    cyberpunk:    { primaryColor: '#8b5cf6', secondaryColor: '#d946ef' },
    crimson:      { primaryColor: '#dc2626', secondaryColor: '#991b1b' },
    oceanic:      { primaryColor: '#2563eb', secondaryColor: '#0284c7' },
    obsidian:     { primaryColor: '#111827', secondaryColor: '#f59e0b' },
  };
  return presets[name] || null;
}

function applyGenTheme(primary, secondary, tName) {
  if (tName) {
    const tSel = document.getElementById('genTemplate');
    if (tSel) tSel.value = tName;
  }
  const pInput = document.getElementById('genPrimaryColor');
  const sInput = document.getElementById('genSecondaryColor');
  if (pInput) pInput.value = primary;
  if (sInput) sInput.value = secondary;
  renderPreview();
}

function renderPreview() {
  if (!currentRecord) return;
  const moduleKey = document.getElementById('genModule').value;
  const pColorInput = document.getElementById('genPrimaryColor');
  const sColorInput = document.getElementById('genSecondaryColor');

  // Start with saved design settings as baseline
  let settings = { ...ALL_SETTINGS };

  if (pColorInput && pColorInput.value) {
    settings.primaryColor = pColorInput.value;
  }
  if (sColorInput && sColorInput.value) {
    settings.secondaryColor = sColorInput.value;
  }

  const { front, back } = renderIdCard(settings, currentRecord, moduleKey);
  const wrap = document.getElementById('cardPreviewWrap');
  wrap.innerHTML = settings.cardSideMode === 'single'
    ? `<div id="cardFront">${front}</div>`
    : `<div id="cardFront">${front}</div><div id="cardBack">${back}</div>`;
  drawCodes(settings, currentRecord);
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('genModule').addEventListener('change', (e) => loadRecordsForModule(e.target.value));
  document.getElementById('genRecord').addEventListener('change', onRecordChange);
  
  const genTemplate = document.getElementById('genTemplate');
  if (genTemplate) {
    genTemplate.addEventListener('change', (e) => {
      const preset = templatePreset(e.target.value);
      if (preset) {
        document.getElementById('genPrimaryColor').value = preset.primaryColor;
        document.getElementById('genSecondaryColor').value = preset.secondaryColor;
      }
      renderPreview();
    });
  }

  const pColor = document.getElementById('genPrimaryColor');
  const sColor = document.getElementById('genSecondaryColor');
  if (pColor) { pColor.value = ALL_SETTINGS.primaryColor; pColor.addEventListener('input', renderPreview); pColor.addEventListener('change', renderPreview); }
  if (sColor) { sColor.value = ALL_SETTINGS.secondaryColor; sColor.addEventListener('input', renderPreview); sColor.addEventListener('change', renderPreview); }

  loadRecordsForModule(PRESET_MODULE);
});

function printSide(side) {
  const target = side === 'front' ? document.getElementById('cardFront') : document.getElementById('cardBack');
  if (!target) return;
  const w = window.open('', '_blank');
  w.document.write(`<html><head><title>Print ID Card</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>body{display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}</style>
    </head><body>${target.innerHTML}</body></html>`);
  w.document.close();
  setTimeout(() => { w.print(); }, 400);
}

async function downloadCard(format) {
  const target = document.getElementById('cardFront');
  if (!target || !window.html2canvas) { showToast('Export library not loaded', 'error'); return; }

  const canvas = await html2canvas(target, { backgroundColor: null, scale: 3 });
  if (format === 'pdf') {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: 'landscape', unit: 'px', format: [canvas.width, canvas.height] });
    pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, canvas.width, canvas.height);
    pdf.save(`${currentRecord.id}_id_card.pdf`);
  } else {
    const mime = format === 'jpg' ? 'image/jpeg' : 'image/png';
    const link = document.createElement('a');
    link.download = `${currentRecord.id}_id_card.${format}`;
    link.href = canvas.toDataURL(mime, 0.95);
    link.click();
  }
}

async function saveCardFile() {
  const target = document.getElementById('cardFront');
  if (!target || !currentRecord || !window.html2canvas || !window.jspdf) {
    showToast('Export library not loaded', 'error');
    return;
  }

  const canvas = await html2canvas(target, { backgroundColor: null, scale: 3 });
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF({ orientation: 'landscape', unit: 'px', format: [canvas.width, canvas.height] });
  pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, canvas.width, canvas.height);
  const fileName = `${currentRecord.id}_id_card.pdf`;

  if (window.showSaveFilePicker) {
    try {
      const handle = await window.showSaveFilePicker({
        suggestedName: fileName,
        types: [{ description: 'PDF document', accept: { 'application/pdf': ['.pdf'] } }],
      });
      const writable = await handle.createWritable();
      await writable.write(pdf.output('blob'));
      await writable.close();
      showToast('ID card saved successfully');
    } catch (error) {
      if (error.name !== 'AbortError') showToast('Could not save the file', 'error');
    }
    return;
  }

  pdf.save(fileName);
}
