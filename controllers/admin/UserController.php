<?php

class UserController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }
    public function index()
    {
        $view = 'users/index';
        $title = 'Danh Sách User';
        $data = $this->user->select('*', '1=1 ORDER BY id DESC');

        require_once PATH_VIEW_ADMIN_MAIN;
    }
    public function create()
    {
        $view = 'users/create';
        $title = 'Thêm Mới User';

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
            if (empty($data['name']) || strlen($data['name']) > 50) {
                $_SESSION['error']['name'] = 'Trường name bắt buộc và độ dài không quá 50 ký tự.';
            }

            if (
                empty($data['email'])
                || strlen($data['email']) > 100

                || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)

                || !empty($this->user->find('*', 'email = :email', ['email' => $data['email']]))
            ) {
                $_SESSION['error']['email'] = 'Trường email bắt buộc, độ dài không quá 100 ký tự và không được trùng';
            }

            if (empty($data['password']) || strlen($data['password']) < 6 || strlen($data['password']) > 30) {
                $_SESSION['error']['password'] = 'Trường password bắt buộc, độ dài trong khoảng từ 6 đến 30 ký tự.';
            }

            if ($data['avatar']['size'] > 0) {

                if ($data['avatar']['size'] > 2 * 1024 * 1024) {
                    $_SESSION['error']['avatar_size'] = 'Trường avatar có dung lượng tối đa 2MB';
                }

                $fileType = $data['avatar']['type'];
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error']['avatar_type'] = 'Xin lỗi, chỉ chấp nhận các loại file JPG, JPEG, PNG, GIF.';
                }
            }

            if (!empty($_SESSION['error'])) {
                $_SESSION['data'] = $data;
                throw new Exception('Dữ Liệu Lỗi');
            }
            if ($data['avatar']['size'] > 0) {
                $data['avatar'] = upload_file('users', $data['avatar']);
            } else {
                $data['avatar'] = null;
            }
            $rowcount = $this->user->insert($data);

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
        header('location: ' . BASE_URL_ADMIN . '&act=users-create');
        exit();
    }

    public function edit()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu tham số id', 99);
            }
            $id = $_GET['id'];

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có Id = $id Không Tồn Tại");
            }

            $view = 'users/edit';
            $title = 'Cập nhật User có Id = ' . $id;

            require_once PATH_VIEW_ADMIN_MAIN;
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();

            header('location: ' . BASE_URL_ADMIN . '&act=users-index');
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

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có Id = $id Không Tồn Tại");
            }
            $data = $_POST + $_FILES;

            $_SESSION['error'] = [];

            // Validate dữ liệu
            if (empty($data['name']) || strlen($data['name']) > 50) {
                $_SESSION['error']['name'] = 'Trường name bắt buộc và độ dài không quá 50 ký tự.';
            }

            if (
                empty($data['email'])
                || strlen($data['email']) > 100

                || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)

                || !empty($this->user->find('*', 'email = :email AND id !=:id', ['email' => $data['email'], 'id' => $id]))
            ) {
                $_SESSION['error']['email'] = 'Trường email bắt buộc, độ dài không quá 100 ký tự và không được trùng';
            }

            if (empty($data['password']) || strlen($data['password']) < 6 || strlen($data['password']) > 30) {
                $_SESSION['error']['password'] = 'Trường password bắt buộc, độ dài trong khoảng từ 6 đến 30 ký tự.';
            }

            if ($data['avatar']['size'] > 0) {

                if ($data['avatar']['size'] > 2 * 1024 * 1024) {
                    $_SESSION['error']['avatar_size'] = 'Trường avatar có dung lượng tối đa 2MB';
                }

                $fileType = $data['avatar']['type'];
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error']['avatar_type'] = 'Xin lỗi, chỉ chấp nhận các loại file JPG, JPEG, PNG, GIF.';
                }
            }
            if (!empty($_SESSION['error'])) {
                $_SESSION['data'] = $data;
                throw new Exception('Dữ Liệu Lỗi');
            }
            if ($data['avatar']['size'] > 0) {
                $data['avatar'] = upload_file('users', $data['avatar']);
            } else {
                $data['avatar'] = null;
            }

            $data['updated_at'] = date('Y-m-d h:i:s');

            $rowcount = $this->user->update($data, 'id = :id', ['id' => $id]);

            if ($rowcount > 0) {
                if (
                    (empty($_FILES['avatar']['size']) || $_FILES['avatar']['size'] == 0)
                    && !empty($user['avatar'])
                    && file_exists(PATH_ASSETS_UPLOADS . $user['avatar'])
                ) {
                    unlink(PATH_ASSETS_UPLOADS . $user['avatar']);
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
                header('location: ' . BASE_URL_ADMIN . '&act=users-index');
                exit();
            }
        }
        header('location: ' . BASE_URL_ADMIN . '&act=users-edit&id=' . $id);
        exit();
    }
    public function show()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu Tham Số id', 99);
            }
            $id = $_GET['id'];

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có Id = $id Không Tồn Tại");
            }

            $view = 'users/show';
            $title = 'Chi Tiết User có Id = ' . $id;

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

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có Id = $id Không Tồn Tại");
            }

            $rowcount = $this->user->delete('id = :id', ['id' => $id]);

            if ($rowcount > 0) {
                if (!empty($user['avatar']) && file_exists(PATH_ASSETS_UPLOADS . $user['avatar'])) {
                    unlink(PATH_ASSETS_UPLOADS . $user['avatar']);
                }

                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Thao Tác Thành công';
            }
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();
        }

        header('location: ' . BASE_URL_ADMIN . '&act=users-index');
        exit();
    }
}
