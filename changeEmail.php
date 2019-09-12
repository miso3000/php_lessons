<?php
session_start();
require('connectDb.php');

//セッション更新
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  $users = $db->prepare('SELECT * FROM users WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
  } else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
  //エラーチェック
  if ($_POST['newEmail'] == '' || $_POST['newEmail2'] == '') {
    $error['newE'] = 'blank';
  }
  if ($_POST['newEmail'] !== $_POST['newEmail2']) {
    $error['newE'] = 'missMatch';
  }

  //重複チェック
  if (empty($error)) {
      $members = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
      $members->execute( array($_POST['newEmail2']) );
      $record = $members->fetch();
      if ($record['cnt'] > 0) {
        $error['newE'] = 'duplicate';
      }
  }
  //最終動作
  if (empty($error)) {
    $update = $db->prepare('UPDATE users SET email=? WHERE id=?');
    $update->execute( array($_POST['newEmail2'],$_SESSION['id']) );
    header('Location: dune2.php');
    exit();
  }

}

var_dump($user);
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
	<title>メールアドレスを変更する</title>
</head>
<body>
  <div class="container mt-5 jumbotron">
    <h1>メールアドレスを変更する</h1>
      <form action="" method="post">
        <label class="mt-5 label">現在のメールアドレス</label>
        <input class="form-control"  type="test" value="<?php print(htmlspecialchars($user['email'], ENT_QUOTES)); ?>" readonly><br>
        <label class="mt-5 label">新しいメールアドレス</label>
        <input class="form-control"  type="test" name="newEmail" value="<?php print(htmlspecialchars($_POST['newEmail'],ENT_QUOTES));?>"><br>
        <label class="mt-5 label">新しいメールアドレス（確認）</label>
        <input class="form-control"  type="test" name="newEmail2" value="<?php print(htmlspecialchars($_POST['newEmail2'],ENT_QUOTES));?>"><br>
        <!-- エラーメッセージ -->
        <?php if($error['newE'] === 'blank') { ?>
        <p style="color:red;">*メールアドレスを入力してください</p>
        <?php } ?>
        <?php if($error['newE'] === 'missMatch') { ?>
        <p style="color:red;">*入力されたメールアドレスが一致しません</p>
        <?php } ?>
        <?php if($error['newE'] === 'duplicate') { ?>
        <p style="color:red;">* そのメールアドレスは既に登録済みです！</p>
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


