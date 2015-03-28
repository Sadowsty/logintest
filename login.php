<?php

require_once("function.php");

session_start();

if(!empty($_SESSION["me"])){
    header("Location: index.php");
    exit;
}

$err = array("email" => null,"password" => null);
$email = null;

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    setToken();
}else{
    checkToken();

    $dbh = connectDb();
    $email = $_POST['email'];
    $password = $_POST['password'];

//メールアドレスチェック
    $err['email'] = filter_var($email,FILTER_VALIDATE_EMAIL) ? "" : "このメールアドレスは正しくありません";
//    if($err['email'] = null){
//        $err['email'] = emailExists($email,$dbh) ? "":"このメールアドレスは登録されていません";
//    }
//これもちゃんと動作しない、関数がおかしいのか？

//パスワードチェック
    $err['password'] = empty($password) ? "パスワードが入力されていません" : "";
    if(!$me = getUser($email, $password, $dbh)){
        $err['password'] = 'パスワードとメールアドレスの組み合わせが正しくありません';
    }

//エラーがなければログイン処理、セッションハイジャック対策
    if(empty($err['email']) and empty($err['password'])){
        session_regenerate_id(true);//わからん
        $_SESSION['me'] = $me;
        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" >
    <title>ログイン画面</title>
</head>
<body>
    <h1>ログイン</h1>
    <form action="" method="POST">
        <p>メールアドレス<input type="text" name="email" value="<?php echo h($email) ?>"><?php echo h($err['email']); ?></p>

        <p>パスワード：<input type="password" name="password" value=""><?php echo h($err['password']); ?></p>

        <input type = "hidden" name = "token" value = "<?php echo h($_SESSION['token']); ?>">

        <p><input type="submit" value="ログイン"><a href="signup.php">新規登録はこちら</a></p>
    </form>
</body>
</html>
