<?php

class TestController{
    public function show(){
        echo 'Đây là trang test client có ID = ' . $_GET['id'];
    }
}