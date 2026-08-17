/* ===========================================================
   Smart ID Card Management System — app.js
   Shared behavior: dark mode, toasts, modal, sidebar toggle
   =========================================================== */

/* ---------- Dark mode ---------- */
(function initTheme() {
  const saved = localStorage.getItem('theme') || 'light';
  document.documentElement.setAttribute('data-theme', saved);
})();

document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('darkModeToggle');
  if (toggle) {
    updateThemeIcon();
    toggle.addEventListener('click', () => {
      const current = document.documentElement.getAttribute('data-theme');
      const next = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('theme', next);
      updateThemeIcon();
    });
  }

  const sidebarToggle = document.getElementById('sidebarToggle');
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
      document.getElementById('sidebar').classList.toggle('open');
    });
  }
});

function updateThemeIcon() {
  const toggle = document.getElementById('darkModeToggle');
  if (!toggle) return;
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  toggle.innerHTML = isDark ? '<i class="bi bi-sun-fill"></i>' : '<i class="bi bi-moon-stars"></i>';
}

/* ---------- Toasts ---------- */
function showToast(message, type = 'success') {
  const stack = document.getElementById('toastStack');
  if (!stack) return;
  const el = document.createElement('div');
  el.className = 'toast' + (type === 'error' ? ' error' : '');
  const icon = type === 'error' ? 'bi-x-circle-fill' : 'bi-check-circle-fill';
  el.innerHTML = `<i class="bi ${icon}" style="color:${type === 'error' ? 'var(--danger)' : 'var(--success)'}"></i><span>${message}</span>`;
  stack.appendChild(el);
  setTimeout(() => {
    el.style.opacity = '0';
    el.style.transform = 'translateX(30px)';
    el.style.transition = 'all .2s ease';
    setTimeout(() => el.remove(), 200);
  }, 3200);
}

/* ---------- Confirm modal ---------- */
function confirmAction(title, message) {
  return new Promise((resolve) => {
    const modal = document.getElementById('confirmModal');
    if (!modal) { resolve(window.confirm(message)); return; }
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    modal.classList.add('show');

    const okBtn = document.getElementById('confirmOk');
    const cancelBtn = document.getElementById('confirmCancel');

    const cleanup = (result) => {
      modal.classList.remove('show');
      okBtn.removeEventListener('click', onOk);
      cancelBtn.removeEventListener('click', onCancel);
      resolve(result);
    };
    const onOk = () => cleanup(true);
    const onCancel = () => cleanup(false);

    okBtn.addEventListener('click', onOk);
    cancelBtn.addEventListener('click', onCancel);
  });
}

/* ---------- Fetch helpers ---------- */
async function apiPost(url, formData) {
  const res = await fetch(url, { method: 'POST', body: formData });
  return res.json();
}
async function apiGet(url) {
  const res = await fetch(url);
  return res.json();
}

/* ---------- Generic client-side table controller ----------
   Used by module pages (school/college/office/hospital).
   Expects a global `RECORDS` array injected by the page,
   and renders rows via a page-provided `renderRow(record)` fn.
*/
class DataTableController {
  constructor({ records, pageSize = 8, renderRow, tbodyId, searchInputId, statusFilterId, sortSelectId, paginationId, emptyHtml }) {
    this.all = records;
    this.pageSize = pageSize;
    this.renderRow = renderRow;
    this.tbody = document.getElementById(tbodyId);
    this.searchInput = document.getElementById(searchInputId);
    this.statusFilter = document.getElementById(statusFilterId);
    this.sortSelect = document.getElementById(sortSelectId);
    this.paginationEl = document.getElementById(paginationId);
    this.emptyHtml = emptyHtml || '<div class="empty-state"><i class="bi bi-inbox"></i><p>No records found</p></div>';
    this.page = 1;

    if (this.searchInput) this.searchInput.addEventListener('input', () => { this.page = 1; this.render(); });
    if (this.statusFilter) this.statusFilter.addEventListener('change', () => { this.page = 1; this.render(); });
    if (this.sortSelect) this.sortSelect.addEventListener('change', () => this.render());

    this.render();
  }

  getFiltered() {
    let list = [...this.all];
    const q = (this.searchInput?.value || '').trim().toLowerCase();
    if (q) {
      list = list.filter(r => Object.values(r).some(v => String(v).toLowerCase().includes(q)));
    }
    const status = this.statusFilter?.value;
    if (status && status !== 'all') {
      list = list.filter(r => (r.status || '').toLowerCase() === status.toLowerCase());
    }
    const sort = this.sortSelect?.value;
    if (sort === 'name_asc') list.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
    else if (sort === 'name_desc') list.sort((a, b) => (b.name || '').localeCompare(a.name || ''));
    else if (sort === 'newest') list.sort((a, b) => (b.created_at || '').localeCompare(a.created_at || ''));
    else if (sort === 'oldest') list.sort((a, b) => (a.created_at || '').localeCompare(b.created_at || ''));
    return list;
  }

  render() {
    const filtered = this.getFiltered();
    const totalPages = Math.max(1, Math.ceil(filtered.length / this.pageSize));
    if (this.page > totalPages) this.page = totalPages;
    const start = (this.page - 1) * this.pageSize;
    const pageItems = filtered.slice(start, start + this.pageSize);

    if (!pageItems.length) {
      this.tbody.innerHTML = `<tr><td colspan="20">${this.emptyHtml}</td></tr>`;
    } else {
      this.tbody.innerHTML = pageItems.map(this.renderRow).join('');
    }
    this.renderPagination(totalPages);
  }

  renderPagination(totalPages) {
    if (!this.paginationEl) return;
    if (totalPages <= 1) { this.paginationEl.innerHTML = ''; return; }
    let html = `<button ${this.page === 1 ? 'disabled' : ''} data-page="prev"><i class="bi bi-chevron-left"></i></button>`;
    for (let i = 1; i <= totalPages; i++) {
      html += `<button class="${i === this.page ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }
    html += `<button ${this.page === totalPages ? 'disabled' : ''} data-page="next"><i class="bi bi-chevron-right"></i></button>`;
    this.paginationEl.innerHTML = html;
    this.paginationEl.querySelectorAll('button').forEach(btn => {
      btn.addEventListener('click', () => {
        const p = btn.dataset.page;
        if (p === 'prev') this.page--;
        else if (p === 'next') this.page++;
        else this.page = parseInt(p, 10);
        this.render();
      });
    });
  }

  setRecords(records) {
    this.all = records;
    this.render();
  }
}

/* ---------- Photo preview on file input ---------- */
function bindPhotoPreview(inputId, previewId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  if (!input || !preview) return;
  input.addEventListener('change', () => {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => { preview.src = e.target.result; };
    reader.readAsDataURL(file);
  });
}
