<?php
session_start();
require('connectDb.php');

if($_COOKIE['email'] !== '') {  //Cookieに保存されている時に実行
  $email = $_COOKIE['email'];   //$emailにCookieを
}

if(!empty($_POST)) {  //ログインがクリックされた場合に実行
  $email = $_POST['email'];

  if($_POST['email'] !== '' && $_POST['password'] !== '') { //メアドとパスワードが空でない時に実行
    $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');  //WHERE句でSQLを発行し$loginに代入
    $login->execute(array(  //入力された値と同じやつをDBに問い合わせる
      $_POST['email'],
      sha1($_POST['password'])  //登録時に暗号化されているので呼び出すときも暗号化したものを指定
    ));
    $user = $login->fetch();  //実行結果の値を取り出して$userに格納

    if ($user) {  //fetchで取り出した値がtrueであれば実行
      $_SESSION['id'] = $user['id'];
      $_SESSION['time'] = time(); //今の時間を格納
      //Cookieの保存
      if ($_POST['save'] === 'on') {  //保存にチェックがされていた場合実行
        setcookie('email', $_POST['email'], time()+60*60*24*14);  //Cookieの名前をemailとし２週間まで保存する
      }   //今の時間 + (60秒 × 60分 × 24時間 × 14日 = 1,209,600秒後)

      header('Location: myPage.php');
      exit();
    } else {  //$userが空の時(エラー)
      $error['login'] = 'failed';
    }
  } else {  //メアドかパスワードのどちらかしか入力されていない時に実行
    $error['login'] = 'blank';
  }
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

    <title>ログインする</title>
  </head>


  <body>
  <div class="container mt-5 jumbotron">
    <p>メールアドレスとパスワードを入力してログインしてください</p>
    <form action="" method="post">

    <div class="form-group">
      <label class="mt-5 label">メールアドレス<span class="ml-2 bg-danger text-white">必須</span></label>
      <input class="form-control"  type="text" name="email" placeholder="info@sample.com" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>">  <!-- エラー時にフォーム内にいれた値を再び入力欄に表示できる -->
    <?php if ($error['login'] === 'blank'): ?>
      <p class="text-danger">* メールアドレスとパスワードを入力してください！</p>
    <?php endif;?>
    <?php if ($error['login'] === 'failed'): ?>
      <p class="text-danger">* ログインに失敗しました。正しくご入力ください！</p>
    <?php endif;?>
    </div>
    <div class="form-group">
      <label class="label">パスワード<span class="ml-2 bg-danger text-white">必須</span></label>
      <input class="form-control"  type="password" name="password" maxlength="8" pattern="^[0-9A-Za-z]+$" placeholder="半角英数字8桁" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">  <!-- エラー時にフォーム内にいれた値を再び入力欄に表示できる -->
    </div>

    <div class="mt-5 text-center">
      <input type="checkbox" name="save" class="form-check-input" value="on">
      <label class="form-check-label mb-5" for="save">ログイン情報を保存する</label><br>
      <button class="btn btn-info mx-auto" style="width: 200px;" type="submit">ログインする</button>
    </div>
    </form>
    <p class="mt-3 text-center">&raquo;<a href="index.php">新規登録はこちらから</a></p>
  </div>





        <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>


</html>