<?php


$act = $_GET['act'] ?? '/';

match($act){
    '/' => (new HomeController)->index(),
    'test-show' => (new TestController)->show(),
};