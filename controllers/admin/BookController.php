<?php

class BookController
{
    private $book;
    private $category;
    public function __construct()
    {
        $this->book = new Book();
        $this->category = new Category();
    }
    public function index()
    {
        $view = 'books/index';
        $title = 'Danh Sách book';
        $data = $this->book->getAll();

        require_once PATH_VIEW_ADMIN_MAIN;
    }
    public function create()
    {
        $view = 'books/create';
        $title = 'Thêm Mới book';

        $category = $this->category->select();
        $categoryPluck = array_column($category, 'name', 'id');
        require_once PATH_VIEW_ADMIN_MAIN;
    }
    public function store()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                throw new Exception('Phương Thức Phải Là POST');
            }
            $data = $_POST + $_FILES;
            $_SESSION['error'] = [];

            // Validate dữ liệu
            if (empty($data['title']) || strlen($data['title']) > 250) {
                $_SESSION['error']['title'] = 'Trường title bắt buộc và độ dài không quá 50 ký tự.';
            }
            if (empty($data['author']) || strlen($data['author']) > 50) {
                $_SESSION['error']['author'] = 'Trường author bắt buộc và độ dài không quá 50 ký tự.';
            }

            if ($data['img_cover']['size'] > 0) {

                if ($data['img_cover']['size'] > 2 * 1024 * 1024) {
                    $_SESSION['error']['img_cover_size'] = 'Trường img_cover có dung lượng tối đa 2MB';
                }

                $fileType = $data['img_cover']['type'];
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error']['img_cover_type'] = 'Xin lỗi, chỉ chấp nhận các loại file JPG, JPEG, PNG, GIF.';
                }
            }

            if (!empty($_SESSION['error'])) {
                $_SESSION['data'] = $data;
                throw new Exception('Dữ Liệu Lỗi');
            }
            if ($data['img_cover']['size'] > 0) {
                $data['img_cover'] = upload_file('books', $data['img_cover']);
            } else {
                $data['img_cover'] = null;
            }
            $rowcount = $this->book->insert($data);

            if ($rowcount > 0) {
                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Thao Tác Thành Công';
            } else {
                throw new Exception('Thao Tác Không Thành Công');
            }
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();
        }
        header('location: ' . BASE_URL_ADMIN . '&act=books-create');
        exit();
    }

    public function edit()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu tham số "id"', 99);
            }

            $id = $_GET['id'];

            $book = $this->book->getByID($id);

            if (empty($book)) {
                throw new Exception("Book có ID = $id KHÔNG TỒN TẠI!");
            }

            $view = 'books/edit';
            $title = "Cập nhật Book có ID = $id";

            $categories = $this->category->select();
            $categoryPluck = array_column($categories, 'name', 'id');

            require_once PATH_VIEW_ADMIN_MAIN;
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();

            header('Location: ' . BASE_URL_ADMIN . '&action=books-index');
            exit();
        }
    }
    public function update()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                throw new Exception('Phương Thức Phải Là POST');
            }
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu tham số id', 99);
            }
            $id = $_GET['id'];

            $book = $this->book->find('*', 'id = :id', ['id' => $id]);

            if (empty($book)) {
                throw new Exception("book có Id = $id Không Tồn Tại");
            }
            $data = $_POST + $_FILES;

            $_SESSION['error'] = [];

            // Validate dữ liệu
            if (empty($data['title']) || strlen($data['title']) > 250) {
                $_SESSION['error']['title'] = 'Trường title bắt buộc và độ dài không quá 50 ký tự.';
            }
            if (empty($data['author']) || strlen($data['author']) > 50) {
                $_SESSION['error']['author'] = 'Trường author bắt buộc và độ dài không quá 50 ký tự.';
            }

            if ($data['img_cover']['size'] > 0) {

                if ($data['img_cover']['size'] > 2 * 1024 * 1024) {
                    $_SESSION['error']['img_cover_size'] = 'Trường img_cover có dung lượng tối đa 2MB';
                }

                $fileType = $data['img_cover']['type'];
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error']['img_cover_type'] = 'Xin lỗi, chỉ chấp nhận các loại file JPG, JPEG, PNG, GIF.';
                }
            }

            $data['updated_at'] = date('Y-m-d h:i:s');

            $rowcount = $this->book->update($data, 'id = :id', ['id' => $id]);

            if ($rowcount > 0) {
                if (
                    (empty($_FILES['img_cover']['size']) || $_FILES['img_cover']['size'] == 0)
                    && !empty($book['img_cover'])
                    && file_exists(PATH_ASSETS_UPLOADS . $book['img_cover'])
                ) {
                    unlink(PATH_ASSETS_UPLOADS . $book['img_cover']);
                }
                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Thao Tác Thành Công';
            } else {
                throw new Exception('Thao Tác Không Thành Công');
            }

        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();

            if ($th->getCode() == 99) {
                header('location: ' . BASE_URL_ADMIN . '&act=books-index');
                exit();
            }
        }
        header('location: ' . BASE_URL_ADMIN . '&act=books-edit&id=' . $id);
        exit();
    }
    public function show()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu Tham Số id', 99);
            }
            $id = $_GET['id'];

            $book = $this->book->find('*', 'id = :id', ['id' => $id]);

            if (empty($book)) {
                throw new Exception("book có Id = $id Không Tồn Tại");
            }

            $view = 'books/show';
            $title = 'Chi Tiết book có Id = ' . $id;

            require_once PATH_VIEW_ADMIN_MAIN;
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();
        }
    }
    public function delete()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu Tham Số id', 99);
            }
            $id = $_GET['id'];

            $book = $this->book->find('*', 'id = :id', ['id' => $id]);

            if (empty($book)) {
                throw new Exception("book có Id = $id Không Tồn Tại");
            }

            $rowcount = $this->book->delete('id = :id', ['id' => $id]);

            if ($rowcount > 0) {
                if (!empty($book['img_cover']) && file_exists(PATH_ASSETS_UPLOADS . $book['img_cover'])) {
                    unlink(PATH_ASSETS_UPLOADS . $book['img_cover']);
                }

                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Thao Tác Thành công';
            }
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();
        }

        header('location: ' . BASE_URL_ADMIN . '&act=books-index');
        exit();
    }
}
