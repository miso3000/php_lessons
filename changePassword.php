<?php
session_start();
require('connectDb.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  $users = $db->prepare('SELECT * FROM users WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
  } else {
    header('Location: login.php');
    exit();
}
  //エラーチェック
  if(!empty($_POST)) {
    if (strlen($_POST['newPassword2']) < 8) {   //8桁未満
      $error['newP'] = 'length';
    }
    if ($_POST['newPassword'] == '' || $_POST['newPassword2'] == '') {    //片方
      $error['newP'] = 'blank';
    }
    if ($_POST['newPassword'] !== $_POST['newPassword2']) {    //不一致
      $error['newP'] = 'missMatch';
    }
  //重複チェック
  if (empty($error)) {
    $users = $db->prepare('SELECT * FROM users WHERE password=? AND id=?');
    $users->execute(array( sha1($_POST['newPassword2']), $_SESSION['id'] ));
    $record = $users->fetch(PDO::FETCH_ASSOC);
    if($record['password'] === sha1($_POST['newPassword2'])) {
      $error['newP'] = 'duplicate';
    }
  }
  //最終動作
  if (empty($error)) {
    $update = $db->prepare('UPDATE users SET password=? WHERE id=?');
    $update->execute(array( sha1($_POST['newPassword2']), $_SESSION['id'] ));
    header('Location: done2.php');
    exit();
  }
var_dump($record);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
 <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

	<meta charset="UTF-8">
	<title>パスワードを変更する</title>
</head>
<body>
  <div class="container mt-5 jumbotron">
    <h1>パスワードを変更する</h1>
    <form action="" method="post">
      <label class="mt-5 label">新しいパスワード</label>
      <input class="form-control"  type="password" name="newPassword" maxlength="8" pattern="^[0-9A-Za-z]+$" placeholder="半角英数字8桁" value="<?php htmlspecialchars($_POST['newPassword2'], ENT_QUOTES); ?>"><br>
      <label class="mt-5 label">パスワードの確認</label>
      <input class="form-control"  type="password" name="newPassword2" maxlength="8" pattern="^[0-9A-Za-z]+$" placeholder="半角英数字8桁" value="<?php htmlspecialchars($_POST['newPassword2'], ENT_QUOTES); ?>"><br>
      <!-- エラーメッセージ -->
      <?php if($error['newP'] === 'length') { ?>
        <p style="color:red;">＊パスワードは８桁以上で入力してください！</p>
      <?php } ?>
      <?php if($error['newP'] === 'blank') { ?>
        <p style="color:red;">＊パスワードを入力してください！</p>
      <?php } ?>
      <?php if($error['newP'] === 'missMatch') { ?>
        <p style="color:red;">＊パスワードが一致しません！</p>
      <?php } ?>
      <?php if($error['newP'] === 'duplicate') { ?>
        <p style="color:red;">＊入力されたパスワードは既に登録済みです！</p>
      <?php } ?>
      <input class="btn btn-info mx-auto mt-5" style="width: 200px;" type="submit" value="更新する">
    </form>
       <a href="myPage.php">マイページへ</a>
  </div>
   <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>


