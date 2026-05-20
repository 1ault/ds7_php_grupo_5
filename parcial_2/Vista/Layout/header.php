<?php
// header.php — layout de cabecera compartido
// Variables esperadas:
//   $pageTitle  (string) — título de la pestaña
//   $layoutRole (string) — 'aspirante' | 'admin'
//   $cssExtra   (string, opcional) — ruta CSS adicional
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'RH System', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/Assets/css/index.css">
    <?php if (!empty($cssExtra)): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($cssExtra, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
</head>
<body class="layout-<?= htmlspecialchars($layoutRole ?? 'default', ENT_QUOTES, 'UTF-8') ?>">

<nav class="navbar navbar-<?= htmlspecialchars($layoutRole ?? 'default', ENT_QUOTES, 'UTF-8') ?>">
    <div class="navbar-brand">
        <?php if (($layoutRole ?? '') === 'admin'): ?>
            🏢 Panel de Administración — RH
        <?php else: ?>
            📋 Portal del Aspirante — RH
        <?php endif; ?>
    </div>
    <div class="navbar-info">
        <?php if (!empty($_SESSION['usuario_nombre'])): ?>
            <span>👤 <?= htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
        <a href="/post/usuario/logout" class="btn-logout">Cerrar sesión</a>
    </div>
</nav>

<main class="main-content">
