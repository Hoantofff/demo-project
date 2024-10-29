<?php

class TestController{
    public function show(){
        echo 'đây là trang test admin có ID = ' . $_GET['id'];
    }
}