<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Assets/css/index.css">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Libros' ?></title>
</head>
<body>
    <header>
        <?= isset($header) ? $header : '' ?>
    </header>
    <main>
        <?= isset($main) ? $main : '' ?>
    </main>
    <footer>
        <?= isset($footer) ? $footer : '' ?>
    </footer>
    <script type="module" src="/Assets/js/index.js"></script>
</body>
</html>
