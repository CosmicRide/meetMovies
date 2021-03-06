<?php
//获得推荐电影
header("Content-type:text/html;charset=utf-8"); //utf-8
//session_start();                    //开始 session
//require "config.php";
$user_Id=$_SESSION['user']['Id'];  //发送者的Id
//$user_Id = '1';                       //测试发送者的Id
$result = array();    //返回数组
//连接数据库、选择数据库
$pdo = new PDO(DB_DSN, DB_USER, DB_PWD);
//从聊天信息表中查询数据
$sql = "select * from user where Id = '$user_Id'";
$res = $pdo->prepare($sql);
$res->execute();
$user = $res->fetch(PDO::FETCH_ASSOC);
$tags=explode('/',$user['tag']);    //划分字符串

$good_movies=get_command_movies_by_tags($pdo,$tags,5);



function get_command_movies_by_tags($pdo,$tags,$num){
    $all_movies=array();//初始化
    //根据标签获得推荐电影
    for($i=0;$i<count($tags);$i++){
        $tag='%'.$tags[$i].'%';
        $sql = "select * from movies where type like '$tag' limit 0,6";//限定推荐6个
        $res = $pdo->prepare($sql);
        $res->execute();
        $movies = $res->fetchAll(PDO::FETCH_ASSOC);
        for($j=0;$j<5;$j++ ){
            $temp=array("Id"=>$movies[$j]['Id'],"picture"=>$movies[$j]['picture'],'score'=>$movies[$j]['score'],'name'=>$movies[$j]['name']);
            array_push($all_movies,$temp);
        }
    }
    //随机获取推荐数组中的推荐
    $all_movies_number= count($all_movies);//系统推荐的电影数目
    $number_temp=array();//推荐数组
    while(count($number_temp)<$num){
        $temp=rand(0,$all_movies_number-1);
        if(!in_array($temp,$number_temp))
            array_push($number_temp,$temp);
    }
    $recommend_movies=array();
    foreach ($number_temp as $key)
        array_push($recommend_movies,$all_movies[$key]);
    return $recommend_movies;
}