<?php

session_set_cookie_params(0, "/test/");

function connectDb(){
    try{
        return new PDO('mysql:host=localhost;dbname=baito', 'root', 'root');
    }catch(PDOException $e){
        echo $e->getMessage();
        exit;
    }
}

function h($s){
    return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
}

function setToken(){
//    $token = sha1(uniqid(mt_rand(),true));
    $_SESSION['token'] = "sha1(uniqid(mt_rand(),true))";
    //$tokenで代入するか直接代入するかで表示が異なるのはなんで？
    //$tokenで代入するとソースから値見れて意味なくない？
    //""をつけないと値が見れる・・・
}

function checkToken(){
    if(empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])){
        echo "不正なポストです";
        exit ;
    }
}

function emailExists($email,$dbh){
    $sql = "select * from user where email = :email limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":email" => $email));
    $user = $stmt->fetch();
    return $user ? true : false;
}

function getsha1($s){
    return (sha1('JfnkJDKF3478J'.$s));
}

function getUser($email, $password, $dbh){
    $sql = "select * from user where email = :email and password = :password limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":email"=>$email, "password"=>getsha1($password)));
    $user = $stmt->fetch();
    return $user ? $user : false;

}
