<a class="btn btn-primary w-100" href="<?= BASE_URL_ADMIN . '&act=books-create' ?>">Thêm Mới</a>
<?php
if (isset($_SESSION['success'])) {
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
            <th>IMG COVER</th>
            <th>TITLE</th>
            <th>AUTHOR</th>
            <th>YEAR</th>
            <th>Created At</th>
            <th>Update At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $book): ?>
            <tr>
                <td><?= $book['b_id'] ?></td>
                <td><?php if(!empty($book['b_img_cover'])): ?>
                        <img src="<?= BASE_ASSETS_UPLOADS . $book['b_img_cover'] ?>" width="100px">
                    <?php endif; ?></td>
                <td><?= $book['b_title'] ?></td>
                <td><?= $book['b_author'] ?></td>
                <td><?= $book['b_published_year'] ?></td>
                <td><?= $book['b_created_at'] ?></td>
                <td><?= $book['b_updated_at'] ?></td>
                <td>
                    <a class="btn btn-success" href="<?= BASE_URL_ADMIN . '&act=books-show&id=' . $book['b_id'] ?>">Show</a>
                    <a class="btn btn-info" href="<?= BASE_URL_ADMIN . '&act=books-edit&id=' . $book['b_id'] ?>">Update</a>
                    <a class="btn btn-danger" href="<?= BASE_URL_ADMIN . '&act=books-delete&id=' . $book['b_id'] ?>"
                        onclick="return confirm('Bạn có chắc muốn xóa hay không?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>