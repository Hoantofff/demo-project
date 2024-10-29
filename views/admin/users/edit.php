<?php
if(isset($_SESSION['success'])){
    $class = $_SESSION['success'] ? 'alert-success' : 'alert-danger';
    
    echo "<div class='alert $class'>{$_SESSION['msg']}</div>";

    unset($_SESSION['success']);
    unset($_SESSION['msg']);
}
?>
<?php if(!empty($_SESSION['error'])):?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach($_SESSION['error'] as $err):  ?>
                <li><?= $err ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['error']) ?>
<?php endif; ?>

<form class="border p-4" action="<?= BASE_URL_ADMIN . '&act=users-update&id=' . $user['id'] ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
        <label for="name">Tên Người dùng</label>
        <input type="text" name="name" class="form-control" value="<?= $user['name'] ?? null ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control" value="<?= $user['email'] ?? null ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="password">Mật Khẩu</label>
        <input type="password" name="password" class="form-control" value="<?= $user['password'] ?? null ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="avatar">Ảnh Người dùng</label>
        <input type="file" name="avatar" class="form-control">

        <div class="mt-3">
        <?php if(!empty($user['avatar'])): ?>
            <img src="<?= BASE_ASSETS_UPLOADS . $user['avatar'] ?>" width="100px">
        <?php endif; ?>
        </div>
    </div>
    <div class="mb-3 mt-3">
        <button class="btn btn-dark " type="submit">Submit</button>
    <a class="btn btn-success" href="<?= BASE_URL_ADMIN ?>&act=users-index">Quay lại trang danh sách</a>

    </div>
</form>

<?php unset($_SESSION['data']) ?>
