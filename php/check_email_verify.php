<?php
$user_word=isset($_POST['user_word'])?$_POST['user_word']:"";
session_start();
$verify_email=$_COOKIE['verify_email'];
if($user_word == $verify_email)
    $err="2";//验证码正确
else
    $err="1";//验证码错误