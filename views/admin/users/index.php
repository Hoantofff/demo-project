<a class="btn btn-primary w-100" href="<?= BASE_URL_ADMIN . '&act=users-create'?>">Thêm Mới</a>
<?php
if(isset($_SESSION['success'])){
    $class = $_SESSION['success'] ? 'alert-success' : 'alert-danger';
    
    echo "<div class='alert $class'>{$_SESSION['msg']}</div>";

    unset($_SESSION['success']);
    unset($_SESSION['msg']);
}
?>
<table class="table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Avatar</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?php if(!empty($user['avatar'])): ?>
                        <img src="<?= BASE_ASSETS_UPLOADS . $user['avatar'] ?>" width="100px">
                    <?php endif; ?>
                </td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td>
                    <a class="btn btn-success" href="<?= BASE_URL_ADMIN . '&act=users-show&id=' . $user['id'] ?>">Show</a>
                    <a class="btn btn-info" href="<?= BASE_URL_ADMIN . '&act=users-edit&id=' . $user['id'] ?>">Update</a>
                    <a class="btn btn-danger" href="<?= BASE_URL_ADMIN . '&act=users-delete&id=' . $user['id'] ?>" 
                    onclick="return confirm('Bạn có chắc muốn xóa hay không?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>