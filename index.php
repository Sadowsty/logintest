<?php

require_once("function.php");

session_start();

if(empty($_SESSION["me"])){
    header("Location: login.php");
    exit;
}

$me = $_SESSION['me'];
$dbh = connectDb();
$sql = "select * from user order by created desc";

foreach ($dbh->query($sql) as $row){
    $users[] = $row;
}

//var_dump($_SESSION);連想配列だけじゃなくて、数字の配列としても突っ込まれてるのはなぜ？


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" >
    <title>ホーム画面</title>
</head>
<body>
    Logged in as '<?php echo h($me['name']);?>'<br>
    [<?php echo h($me['email']); ?>]
    <a href="logout.php">ログアウト</a>
    <h1>ユーザー一覧</h1>
    <ul>
        <?php foreach($users as $u) : ?>
        <li>
            <?php echo h($u["name"]); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
