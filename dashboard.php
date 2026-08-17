<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/header.php';

$stats = get_stats();
$moduleLabels = ['school' => 'School', 'college' => 'College', 'office' => 'Office', 'hospital' => 'Hospital'];
$moduleIcons = ['school' => 'bi-mortarboard-fill', 'college' => 'bi-bank2', 'office' => 'bi-briefcase-fill', 'hospital' => 'bi-heart-pulse-fill'];
?>

<div class="page-head">
  <div>
    <h1>Welcome back, Admin 👋</h1>
    <p>Here's what's happening across all your organizations today.</p>
  </div>
  <a href="id-generator.php" class="btn btn-primary"><i class="bi bi-credit-card-2-front-fill"></i> Generate ID Card</a>
</div>

<div class="stat-grid">
  <div class="glass-card stat-card grad-1">
    <div class="stat-icon"><i class="bi bi-mortarboard-fill"></i></div>
    <p class="stat-value"><?= $stats['school'] ?></p>
    <span class="stat-label">Total Schools</span>
  </div>
  <div class="glass-card stat-card grad-2">
    <div class="stat-icon"><i class="bi bi-bank2"></i></div>
    <p class="stat-value"><?= $stats['college'] ?></p>
    <span class="stat-label">Total Colleges</span>
  </div>
  <div class="glass-card stat-card grad-3">
    <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
    <p class="stat-value"><?= $stats['office'] ?></p>
    <span class="stat-label">Total Offices</span>
  </div>
  <div class="glass-card stat-card grad-4">
    <div class="stat-icon"><i class="bi bi-heart-pulse-fill"></i></div>
    <p class="stat-value"><?= $stats['hospital'] ?></p>
    <span class="stat-label">Total Hospitals</span>
  </div>
  <div class="glass-card stat-card grad-5">
    <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
    <p class="stat-value"><?= $stats['total'] ?></p>
    <span class="stat-label">Total Registered People</span>
  </div>
</div>

<div class="dash-grid">
  <div class="glass-card panel">
    <h2><i class="bi bi-clock-history"></i> Recent Registrations</h2>
    <?php if (empty($stats['recent'])): ?>
      <div class="empty-state"><i class="bi bi-inbox"></i><p>No registrations yet. Add your first record to get started.</p></div>
    <?php else: ?>
      <?php foreach ($stats['recent'] as $r): ?>
        <div class="activity-row">
          <?php if (!empty($r['photo'])): ?>
            <img src="<?= htmlspecialchars($r['photo']) ?>" class="activity-thumb">
          <?php else: ?>
            <div class="activity-thumb"><i class="bi bi-person-fill"></i></div>
          <?php endif; ?>
          <div class="activity-meta">
            <div class="name"><?= htmlspecialchars($r['name'] ?? 'Unnamed') ?></div>
            <div class="sub"><?= htmlspecialchars($r['id']) ?> · <?= htmlspecialchars($r['created_at'] ?? '') ?></div>
          </div>
          <span class="badge badge-<?= $r['_module'] ?>"><?= $moduleLabels[$r['_module']] ?></span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="glass-card panel">
    <h2><i class="bi bi-pie-chart-fill"></i> Organization Breakdown</h2>
    <?php foreach ($moduleLabels as $key => $label):
      $pct = $stats['total'] > 0 ? round(($stats[$key] / $stats['total']) * 100) : 0; ?>
      <div style="margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:6px;">
          <span><i class="bi <?= $moduleIcons[$key] ?>"></i> <?= $label ?></span>
          <span style="color:var(--text-muted)"><?= $stats[$key] ?> (<?= $pct ?>%)</span>
        </div>
        <div style="height:8px;border-radius:99px;background:var(--bg);overflow:hidden;">
          <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:99px;"></div>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="section-divider"></div>
    <a href="api/backup.php" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;"><i class="bi bi-cloud-arrow-down-fill"></i> Backup All JSON Data</a>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
