<?php
session_start();
require('dbconnect.php');

try {
  $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
  $member->execute(array($_POST['email']));
} catch (PDOException $e) {
  echo 'エラーが発生しました：' . $e->getMessage();
}


if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  //ログインしている
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  //ログインしていない
  header('Location: login.php'); exit();
}

//投稿を記録する
if (!empty($_POST)) {
  if ($_POST['message'] !='') {
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, created=NOW()');
    $message->execute(array(
      $member['id'],
      $_POST['message']
    ));

    header('Location: index.php'); exit();

  }
}
// if (!empty($_POST['email'])) {
//   $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
//   $member->execute(array($_POST['email']));
//   // 以下略...
// }
// if (!empty($_POST['message'])) {
//   // メッセージの処理
// }
// if (isset($_POST['message'])) {
//   // $_POST['message'] が存在する場合の処理
//   $message = $_POST['message'];
// } else {
//   // $_POST['message'] が存在しない場合の処理
//   $message = ''; // または適切な初期値を設定
// }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
  <form action="index.php" method="post" enctype="multipart/form-data">
  <dl>
    <dt><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>さん、メッセージをどうぞ</dt>
    <dd>
      <textarea name="email" name="message" cols="50" rows="5"></textarea>
    </dd>
    <div>
      <input type="submit" value="投稿する" />
    </div>
</form>
  </div>

</div>
</body>
</html>