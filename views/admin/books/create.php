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

<form class="border p-4" action="<?= BASE_URL_ADMIN ?>&act=books-store" method="POST" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control" value="<?= $_SESSION['data']['title'] ?? null ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="category_id">Category</label>
        <select name="category_id" class="form-control">
            <?php foreach($categoryPluck as $id => $name): ?>
                <option value="<?= $id ?>"><?= $name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3 mt-3">
        <label for="author">Author</label>
        <input type="text" name="author" class="form-control" value="<?= $_SESSION['data']['author'] ?? null ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="published_year">Published year</label>
        <input type="number" name="published_year" class="form-control" value="<?= $_SESSION['data']['published_year'] ?? null ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="img_cover">Ảnh Book</label>
        <input type="file" name="img_cover" class="form-control">
    </div>
    <div class="mb-3 mt-3">
        <button class="btn btn-dark " type="submit">Submit</button>
    <a class="btn btn-success" href="<?= BASE_URL_ADMIN ?>&act=books-index">Quay lại trang danh sách</a>

    </div>
</form>

<?php unset($_SESSION['data']) ?>
