<?php
// Expects $pageTitle and $activePage to be set by including page
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}
if (!isset($pageTitle)) $pageTitle = 'Dashboard';
if (!isset($activePage)) $activePage = 'dashboard';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> · Smart ID Card Management</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
  <?php require __DIR__ . '/sidebar.php'; ?>

  <div class="main-area">
    <header class="topbar">
      <button class="icon-btn sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar"><i class="bi bi-list"></i></button>

      <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="globalSearch" placeholder="Search records, IDs, names...">
      </div>

      <div class="topbar-actions">
        <button class="icon-btn" id="darkModeToggle" title="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
        <button class="icon-btn" id="notifBtn" title="Notifications">
          <i class="bi bi-bell"></i>
          <span class="notif-dot"></span>
        </button>
        <div class="profile-chip">
          <div class="avatar">A</div>
          <div class="profile-meta">
            <span class="profile-name">Admin</span>
            <span class="profile-role">Super Admin</span>
          </div>
        </div>
      </div>
    </header>

    <main class="page-content">
