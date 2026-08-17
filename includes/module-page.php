<?php
/**
 * Expects $moduleKey to be set before including this file.
 */
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/fields.php';

$mod = get_module($moduleKey);
$fields = get_fields($moduleKey);
$columns = get_table_columns($moduleKey);
$records = read_json($mod['file']);
$pageTitle = $mod['label'] . ' Registrations';
$activePage = $moduleKey;

require __DIR__ . '/header.php';
?>

<div class="page-head">
  <div>
    <h1><?= htmlspecialchars($mod['label']) ?> Registrations</h1>
    <p>Manage <?= htmlspecialchars(strtolower($mod['label'])) ?> ID records — create, edit, search, and generate cards.</p>
  </div>
  <button class="btn btn-primary" id="addBtn"><i class="bi bi-plus-lg"></i> New Registration</button>
</div>

<div class="glass-card panel">
  <div class="toolbar">
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="tableSearch" placeholder="Search by name, ID, phone...">
    </div>
    <select id="statusFilter">
      <option value="all">All Status</option>
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
      <option value="Expired">Expired</option>
    </select>
    <select id="sortSelect">
      <option value="newest">Newest First</option>
      <option value="oldest">Oldest First</option>
      <option value="name_asc">Name A–Z</option>
      <option value="name_desc">Name Z–A</option>
    </select>
    <div class="spacer"></div>
    <button class="btn btn-ghost btn-sm" id="exportBtn"><i class="bi bi-download"></i> Export JSON</button>
    <label class="btn btn-ghost btn-sm" style="margin:0;cursor:pointer;">
      <i class="bi bi-upload"></i> Import JSON
      <input type="file" id="importInput" accept=".json" style="display:none;">
    </label>
  </div>

  <div class="table-wrap">
    <table class="data-table">
      <thead>
        <tr>
          <?php foreach ($columns as $c): ?>
            <th><?= $c === 'photo' ? '' : ucwords(str_replace('_', ' ', $c)) ?></th>
          <?php endforeach; ?>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <tr class="loading-row"><td colspan="20">Loading records...</td></tr>
      </tbody>
    </table>
  </div>

  <div class="pagination" id="pagination"></div>
</div>

<!-- Add / Edit Modal -->
<div class="modal-backdrop record-modal" id="recordModal">
  <div class="modal-box">
    <h3 id="formModalTitle" style="margin-bottom:4px;"><i class="bi bi-person-plus-fill"></i> New <?= htmlspecialchars($mod['label']) ?> Registration</h3>
    <p style="color:var(--text-muted);font-size:12.5px;margin-bottom:18px;">Fields marked * are required.</p>
    <form id="recordForm">
      <input type="hidden" name="module" value="<?= $moduleKey ?>">
      <input type="hidden" name="id" id="recordId">
      <input type="hidden" name="existing_photo" id="existingPhoto">

      <div class="form-field full">
        <label>Photo</label>
        <div class="photo-upload">
          <img src="" id="photoPreview" class="photo-preview">
          <div class="upload-actions">
            <label class="btn btn-ghost btn-sm" style="cursor:pointer;">
              <i class="bi bi-upload"></i> Upload
              <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;">
            </label>
          </div>
        </div>
      </div>

      <div class="form-grid">
        <?php foreach ($fields as $f):
          if ($f['type'] === 'file') continue; ?>
          <div class="form-field <?= $f['type'] === 'textarea' ? 'full' : '' ?>">
            <label><?= htmlspecialchars($f['label']) ?><?= !empty($f['required']) ? ' *' : '' ?></label>
            <?php if ($f['type'] === 'select'): ?>
              <select name="<?= $f['name'] ?>" id="f_<?= $f['name'] ?>">
                <option value="">Select...</option>
                <?php foreach ($f['options'] as $opt): ?>
                  <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                <?php endforeach; ?>
              </select>
            <?php elseif ($f['type'] === 'textarea'): ?>
              <textarea name="<?= $f['name'] ?>" id="f_<?= $f['name'] ?>"></textarea>
            <?php else: ?>
              <input type="<?= $f['type'] ?>" name="<?= $f['name'] ?>" id="f_<?= $f['name'] ?>" placeholder="<?= htmlspecialchars($f['placeholder'] ?? '') ?>" <?= !empty($f['required']) ? 'required' : '' ?>>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-ghost" id="cancelForm">Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn"><i class="bi bi-check-lg"></i> Save Record</button>
      </div>
    </form>
  </div>
</div>

<!-- View Modal -->
<div class="modal-backdrop record-modal" id="viewModal">
  <div class="modal-box" id="viewModalContent"></div>
</div>

<script>
const MODULE_KEY = <?= json_encode($moduleKey) ?>;
const MODULE_LABEL = <?= json_encode($mod['label']) ?>;
const FIELDS = <?= json_encode($fields) ?>;
const COLUMNS = <?= json_encode($columns) ?>;
const INITIAL_RECORDS = <?= json_encode(array_reverse($records)) ?>;
</script>
<script src="assets/js/module.js"></script>

<?php require __DIR__ . '/footer.php'; ?>
