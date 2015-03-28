<?php

require_once("function.php");

session_start();

$name = null;
$email = null;
$err = array("name" => null,"email" => null,"password" => null);

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    setToken();
}else{
    checkToken();

    $dbh = connectDb();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

//エラー表示
    $err['email'] = filter_var($email,FILTER_VALIDATE_EMAIL) ? null : "このメールアドレスは正しくありません";
//    if($err['email'] = null){
//        $err['email'] = emailExists($email,$dbh) ? "このメールアドレスは登録済みです":"";
//    }
//これを入れるとちゃんと動作しないのはなぜ？

    $err['name'] = empty($name) ? "名前が入力されていません" : "";
    $err['password'] = empty($password) ? "パスワードが入力されていません" : "";

//エラーがなければ登録処理
    if(empty($err['name']) and empty($err['email']) and empty($err['password'])){
        $sql = "insert into user (name, email, password, created, modified)
            values (:name, :email, :password, now(), now())";
        $stmt = $dbh->prepare($sql);
        $params = array(
            ":name" => $name,
            ":email" => $email,
            ":password" => getsha1($password),
        );
        $stmt->execute($params);
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" >
    <title>新規登録</title>
</head>
<body>
    <h1>新規登録</h1>

    <form action="" method="POST">
        <p>お名前:<input type="text" name="name" value="<?php echo h($name) ?>">
        <?php echo h($err['name']); ?></p>

        <p>メールアドレス:<input type="text" name="email" value="<?php echo h($email) ?>">
        <?php echo h($err['email']); ?></p>

        <p>パスワード:<input type="password" name="password" value="">
        <?php echo h($err['password']); ?></p>

        <input type = "hidden" name = "token" value = "<?php echo h($_SESSION['token']); ?>"

        <p><input type="submit" value="新規登録"><a href="index.php">戻る</a></p>
    </form>

</body>
</html>
