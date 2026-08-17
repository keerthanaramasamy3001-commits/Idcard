/* ===========================================================
   module.js — powers school.php / college.php / office.php / hospital.php
   Relies on globals injected by module-page.php: MODULE_KEY, MODULE_LABEL,
   FIELDS, COLUMNS, INITIAL_RECORDS
   =========================================================== */

let records = INITIAL_RECORDS.slice();
let table;

function statusBadge(status) {
  const s = (status || '').toLowerCase();
  const cls = s === 'active' ? 'badge-active' : s === 'expired' ? 'badge-expired' : 'badge-inactive';
  return `<span class="badge ${cls}">${status || 'N/A'}</span>`;
}

function renderRow(r) {
  const cells = COLUMNS.map(col => {
    if (col === 'photo') {
      return r.photo
        ? `<td><img src="${r.photo}" class="row-photo"></td>`
        : `<td><div class="row-photo" style="display:flex;align-items:center;justify-content:center;background:var(--bg);color:var(--text-soft);"><i class="bi bi-person-fill"></i></div></td>`;
    }
    if (col === 'status') return `<td>${statusBadge(r.status)}</td>`;
    if (col === 'id') return `<td style="font-family:'JetBrains Mono',monospace;font-weight:600;">${r.id}</td>`;
    return `<td>${r[col] ?? ''}</td>`;
  }).join('');

  return `<tr>
    ${cells}
    <td>
      <div class="row-actions">
        <button class="btn btn-ghost btn-icon" title="View" onclick="viewRecord('${r.id}')"><i class="bi bi-eye"></i></button>
        <button class="btn btn-ghost btn-icon" title="Edit" onclick="editRecord('${r.id}')"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-ghost btn-icon" title="Generate ID Card" onclick="location.href='id-generator.php?module=${MODULE_KEY}&id=${r.id}'"><i class="bi bi-credit-card-2-front"></i></button>
        <button class="btn btn-ghost btn-icon" title="Delete" onclick="deleteRecord('${r.id}')" style="color:var(--danger);"><i class="bi bi-trash"></i></button>
      </div>
    </td>
  </tr>`;
}

document.addEventListener('DOMContentLoaded', () => {
  table = new DataTableController({
    records,
    pageSize: 8,
    renderRow,
    tbodyId: 'tableBody',
    searchInputId: 'tableSearch',
    statusFilterId: 'statusFilter',
    sortSelectId: 'sortSelect',
    paginationId: 'pagination',
  });

  bindPhotoPreview('photoInput', 'photoPreview');

  document.getElementById('addBtn').addEventListener('click', openAddModal);
  document.getElementById('cancelForm').addEventListener('click', closeFormModal);
  document.getElementById('recordForm').addEventListener('submit', submitForm);
  document.getElementById('exportBtn').addEventListener('click', exportJson);
  document.getElementById('importInput').addEventListener('change', importJson);
});

function openAddModal() {
  document.getElementById('formModalTitle').innerHTML = `<i class="bi bi-person-plus-fill"></i> New ${MODULE_LABEL} Registration`;
  document.getElementById('recordForm').reset();
  document.getElementById('recordId').value = '';
  document.getElementById('existingPhoto').value = '';
  document.getElementById('photoPreview').src = placeholderPhoto();
  document.getElementById('recordModal').classList.add('show');
}

function closeFormModal() {
  document.getElementById('recordModal').classList.remove('show');
}

function placeholderPhoto() {
  return 'data:image/svg+xml;utf8,' + encodeURIComponent(
    `<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"><rect width="64" height="64" rx="14" fill="#e2e8f0"/><text x="32" y="40" font-size="26" text-anchor="middle" fill="#94a3b8" font-family="sans-serif">👤</text></svg>`
  );
}

function editRecord(id) {
  const r = records.find(x => x.id === id);
  if (!r) return;
  document.getElementById('formModalTitle').innerHTML = `<i class="bi bi-pencil-square"></i> Edit ${MODULE_LABEL} Registration`;
  document.getElementById('recordForm').reset();
  document.getElementById('recordId').value = r.id;
  document.getElementById('existingPhoto').value = r.photo || '';
  document.getElementById('photoPreview').src = r.photo || placeholderPhoto();

  FIELDS.forEach(f => {
    if (f.type === 'file') return;
    const el = document.getElementById('f_' + f.name);
    if (el) el.value = r[f.name] || '';
  });

  document.getElementById('recordModal').classList.add('show');
}

async function viewRecord(id) {
  const r = records.find(x => x.id === id);
  if (!r) return;
  const rows = FIELDS.filter(f => f.type !== 'file').map(f =>
    `<div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:13px;">
      <span style="color:var(--text-muted);">${f.label}</span>
      <span style="font-weight:600;text-align:right;">${r[f.name] || '—'}</span>
    </div>`
  ).join('');

  document.getElementById('viewModalContent').innerHTML = `
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
      <img src="${r.photo || placeholderPhoto()}" style="width:60px;height:60px;border-radius:14px;object-fit:cover;background:var(--bg);">
      <div>
        <h3 style="margin:0;">${r.name || 'Unnamed'}</h3>
        <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text-muted);">${r.id}</span>
      </div>
    </div>
    <div>${rows}</div>
    <div class="form-actions">
      <button class="btn btn-ghost" onclick="document.getElementById('viewModal').classList.remove('show')">Close</button>
      <button class="btn btn-primary" onclick="location.href='id-generator.php?module=${MODULE_KEY}&id=${r.id}'"><i class="bi bi-credit-card-2-front-fill"></i> Generate Card</button>
    </div>
  `;
  document.getElementById('viewModal').classList.add('show');
}

async function submitForm(e) {
  e.preventDefault();
  const form = document.getElementById('recordForm');
  const saveBtn = document.getElementById('saveBtn');
  saveBtn.disabled = true;
  saveBtn.innerHTML = '<span class="spinner"></span> Saving...';

  try {
    const formData = new FormData(form);
    const result = await apiPost('api/save.php', formData);
    if (result.success) {
      showToast(`Record ${form.recordId?.value ? 'updated' : 'saved'} successfully`);
      closeFormModal();
      await refreshRecords();
    } else {
      showToast(result.message || 'Save failed', 'error');
    }
  } catch (err) {
    showToast('Network error while saving', 'error');
  } finally {
    saveBtn.disabled = false;
    saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> Save Record';
  }
}

async function deleteRecord(id) {
  const ok = await confirmAction('Delete this record?', 'This will permanently remove the record and cannot be undone.');
  if (!ok) return;

  const fd = new FormData();
  fd.append('module', MODULE_KEY);
  fd.append('id', id);
  const result = await apiPost('api/delete.php', fd);
  if (result.success) {
    showToast('Record deleted');
    await refreshRecords();
  } else {
    showToast(result.message || 'Delete failed', 'error');
  }
}

async function refreshRecords() {
  const result = await apiGet(`api/read.php?module=${MODULE_KEY}`);
  if (result.success) {
    records = result.records.slice().reverse();
    table.setRecords(records);
  }
}

function exportJson() {
  const blob = new Blob([JSON.stringify(records, null, 2)], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `${MODULE_KEY}_export_${Date.now()}.json`;
  a.click();
  URL.revokeObjectURL(url);
}

async function importJson(e) {
  const file = e.target.files[0];
  if (!file) return;
  try {
    const text = await file.text();
    const imported = JSON.parse(text);
    if (!Array.isArray(imported)) throw new Error('Invalid format');

    for (const rec of imported) {
      const fd = new FormData();
      fd.append('module', MODULE_KEY);
      Object.entries(rec).forEach(([k, v]) => {
        if (k !== 'photo') fd.append(k, v ?? '');
      });
      if (rec.photo) fd.append('existing_photo', rec.photo);
      await apiPost('api/save.php', fd);
    }
    showToast(`Imported ${imported.length} record(s)`);
    await refreshRecords();
  } catch (err) {
    showToast('Import failed — invalid JSON file', 'error');
  } finally {
    e.target.value = '';
  }
}
