<?php
require('dbconnect.php');

session_start();

if ($_COOKIE['email'] != '') {
  $_POST['email'] = $_COOKIE['email'];
  $_POST['password'] = $_COOKIE['password'];
  $_POST['save'] = 'on';
}

if (!empty($_POST)) {
  //ログインの処理
  if ($_POST['email'] != '' && $_POST['password'] !='') {
    $login = $db->prepare('SELECT * FROM menbers WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();

    if ($member) {
      //ログイン成功
      $_SESSION['id'] = $memer['id'];
      $_SESSION['time'] = time();
        //ログイン情報を記録する
        if ($_POST['save'] == 'on') {
          setcookie('email',$_POST['email'], time()+60*60*24*14);
          setcookie('password', $_POST['password'], time()+60*60*24*14);
        }
      }
      header('Location: index.php'); exir;
    } else {
      $error['login'] = 'blank';
    }
  }
  ?>
<div id="lead">
  <p>メールアドレスとパスワードを記入してログインしてください。</p>
  <p>入会手続きがまだの方はこちらからどうぞ</p>
  <p>&raquo;<a href="join.php">入会手続きをする</a></p>
</div>
<form action="" method="post">
  <dl>
    <dt>メールアドレス</dt>
    <dd>
      <input type="text" name="email" size="35" maxlength="255"
      value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
      <?php if ($error['login'] == 'blank'): ?>
        <p class="error">* メールアドレスとパスワードをご記入してください</p>
        <?php endif; ?>
        <?php if ($error['login'] == 'failed'): ?>
          <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
          <?php endif; ?>
    </dd>
    <dt>パスワード</dt>
    <dd>
      <input type="password" name="password" size="35" maxlength="255"
      value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
    </dd>
    <dt>ログイン情報の記録</dt>
    <dd>
      <input id="save" type="checkbox" name="save" value="on"><labelfor="save">次回からは自動でログインする</label>
    </dd>
  </dl>
  <div><input type="submit" value="ログインする" /></div>
</form>