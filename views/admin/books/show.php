<table class="table border">
    <thead>
        <tr>
            <th class="border bg-secondary text-light">Trường Dữ Liệu</th>
            <th class="border bg-secondary text-light">Dự Liệu</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($book as $key => $value): ?>
            <tr>
                <td class="border"><?= $key ?></td>
                <td class="border">
                    <?php 
                        switch($key){
                            case 'img_cover':
                            if( (!empty($value))){
                                $link = BASE_ASSETS_UPLOADS . $value;
                                echo "<img src='$link' width='100px'>";
                            }
                            break;
                            default: 
                            echo $value;
                            break;
                        }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a class="btn btn-success w-100" href="<?= BASE_URL_ADMIN ?>&act=books-index">Quay lại trang danh sách</a>
