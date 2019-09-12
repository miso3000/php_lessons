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

if (!empty($_POST)) {

  $members = $db->prepare('SELECT * FROM users WHERE password=? AND id=?');
  $members->execute(array( sha1($_POST['password']), $_SESSION['id'] ));
  $record = $members->fetch(PDO::FETCH_ASSOC);
  if ($record['password'] !== sha1($_POST['password'])) {
    $error['us'] = 'disagreement';
  }
  if (empty($error)) {
    $delete = $db->prepare('DELETE FROM users WHERE users.id=?');
    $delete->execute(array( $_SESSION['id'] ));
    header('Location: done3.php');
    exit();
  }

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
	<title>退会する</title>
</head>
<body>
  <div class="container mt-5 jumbotron">
    <h1>退会する</h1>
    <form action="" method="POST">
      <label class="mt-5 label">ログインパスワードを入力し、退会してください</label>
      <input class="form-control"  type="password" name="password" maxlength="8" pattern="^[0-9A-Za-z]+$" placeholder="半角英数字8桁" value="<?php htmlspecialchars($_POST['password'], ENT_QUOTES); ?>"><br>
      <?php if($error['us'] === 'disagreement') { ?>
        <p style="color:red;">＊パスワードに誤りがあります</p>
      <?php } ?>
      <input type="submit" value="退会する">
    </form>
    <a href="myPage.php">退会をやめる</a>
  </div>



   <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>


