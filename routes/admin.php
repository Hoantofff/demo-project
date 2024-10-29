<?php



$act = $_GET['act'] ?? '/';

if (
    empty($_SESSION['user'])
    && !in_array($act, ['show-form-login', 'login'])
) {
    header('Location: ' . BASE_URL_ADMIN . '&act=show-form-login');
    exit();
}
match($act){
    '/' => (new DashboardController)->index(),
    'test-show' => (new TestController)->show(),

    //Authen
    'show-form-login' => (new AuthenController)->showFormLogin(),
    'login'                 => (new AuthenController)->login(),
    'logout'                => (new AuthenController)->logout(),
    
    // CRUD User
    'users-index' => (new UserController)->index(),
    'users-create' => (new UserController)->create(),
    'users-store' => (new UserController)->store(), // Lưu Dữ Liệu Thêm Mới
    'users-edit' => (new UserController)->edit(), 
    'users-update' => (new UserController)->update(), // Lưu Dữ Liệu Update
    'users-show' => (new UserController)->show(),
    'users-delete' => (new UserController)->delete(),

    // CRUD Books
    'books-index' => (new BookController)->index(),
    'books-create' => (new BookController)->create(),
    'books-store' => (new BookController)->store(), // Lưu Dữ Liệu Thêm Mới
    'books-edit' => (new BookController)->edit(), 
    'books-update' => (new BookController)->update(), // Lưu Dữ Liệu Update
    'books-show' => (new BookController)->show(),
    'books-delete' => (new BookController)->delete(),
};
