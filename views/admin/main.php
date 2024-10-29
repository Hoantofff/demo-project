<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'admin Dashboard' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-xxl bg-light justify-content-center">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= BASE_URL_ADMIN ?>">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= BASE_URL_ADMIN ?>&act=users-index"><b>Quản lý user</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= BASE_URL_ADMIN ?>&act=books-index"><b>Quản lý Books</b></a>
            </li>
            <?php if (!empty($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a class="nav-link text-uppercase text-danger"
                        href="<?= BASE_URL_ADMIN . '&action=logout' ?>"
                        onclick="return confirm('Có chắc chắn đăng xuất?')"> <b>Đăng xuất</b> </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">
        <h1 class="mt-3 mb-3"><?= $title ?? 'admin Dashboard' ?></h1>

        <div class="row">
            <?php
                if(isset($view)){
                    require_once PATH_VIEW_ADMIN . $view . '.php';
                }
            ?>
        </div>
    </div>
</body>

</html>