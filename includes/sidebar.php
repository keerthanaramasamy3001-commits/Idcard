<?php if (!isset($activePage)) $activePage = ''; ?>
<aside class="sidebar" id="sidebar">
  <div class="brand">
    <div class="brand-mark"><i class="bi bi-person-badge-fill"></i></div>
    <div class="brand-text">
      <span class="brand-title">Smart ID</span>
      <span class="brand-sub">Card Management</span>
    </div>
  </div>

  <nav class="side-nav">
    <a href="dashboard.php" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
    </a>
    <div class="nav-section">Registrations</div>
    <a href="school.php" class="nav-item <?= $activePage === 'school' ? 'active' : '' ?>">
      <i class="bi bi-mortarboard-fill"></i><span>School</span>
    </a>
    <a href="college.php" class="nav-item <?= $activePage === 'college' ? 'active' : '' ?>">
      <i class="bi bi-bank2"></i><span>College</span>
    </a>
    <a href="office.php" class="nav-item <?= $activePage === 'office' ? 'active' : '' ?>">
      <i class="bi bi-briefcase-fill"></i><span>Office</span>
    </a>
    <a href="hospital.php" class="nav-item <?= $activePage === 'hospital' ? 'active' : '' ?>">
      <i class="bi bi-heart-pulse-fill"></i><span>Hospital</span>
    </a>
    <div class="nav-section">ID Cards</div>
    <a href="id-generator.php" class="nav-item <?= $activePage === 'id-generator' ? 'active' : '' ?>">
      <i class="bi bi-credit-card-2-front-fill"></i><span>ID Card Generator</span>
    </a>
    <a href="design.php" class="nav-item <?= $activePage === 'design' ? 'active' : '' ?>">
      <i class="bi bi-brush-fill"></i><span>ID Card Design</span>
    </a>
    <a href="templates.php" class="nav-item <?= $activePage === 'templates' ? 'active' : '' ?>">
      <i class="bi bi-palette-fill"></i><span>ID Card Templates</span>
    </a>
    <div class="nav-section">System</div>
    <a href="settings.php" class="nav-item <?= $activePage === 'settings' ? 'active' : '' ?>">
      <i class="bi bi-gear-fill"></i><span>Settings</span>
    </a>
    <a href="api/backup.php" class="nav-item">
      <i class="bi bi-cloud-arrow-down-fill"></i><span>Backup JSON</span>
    </a>
    <a href="index.php" class="nav-item">
      <i class="bi bi-box-arrow-right"></i><span>Logout</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="storage-pill">
      <i class="bi bi-hdd-fill"></i>
      <span>JSON File Storage · No SQL</span>
    </div>
  </div>
</aside>
