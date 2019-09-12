<?php
session_start();
require('connectDb.php');

if (isset($_SESSION['id']) && $_SESSION['time'] +3600 > time()) {
  $_SESSION['time'] = time();
  $users = $db->prepare('SELECT * FROM users WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
  } else {  //ログインしていない時
    header('Location: login.php');  //ログイン画面へ強制的に移動
    exit();
}

?>
<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>マイページ</title>
  </head>


  <body>
  <div class="container mt-5 jumbotron">
  <div style="text-align: right">
  <a class="btn btn-info" href="logout.php" role="button">ログアウト</a>
  </div>
    <p class="mb-5"><?php print(htmlspecialchars($user['name'], ENT_QUOTES)); ?>様のマイページです</p>
    <div class="border border-success container-fluid text-center p-3">登録情報の変更</div>
<!-- <div class="container-fluid">
  <div class="row">
    <a href="#" class="border col-6">メールアドレスの変更</div>
    <a href="#" class="border col-6">パスワードの変更</div>
  </div> -->
  <div class="row justify-content-between p-5 m-5">
    <a  href="changeEmail.php" class="text-center">メールアドレスの変更</a>
    <a  href="changePassword.php" class="text-center">パスワードの変更</a>
    <a  href="unsubscribe.php" class="text-center">退会する</a>
  </div>

</div>

    </div>





        <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>


</html>