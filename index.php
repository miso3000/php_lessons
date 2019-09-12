<?php
session_start();  //自動でセッションIDの発行を開始
require('connectDb.php');

if (!empty($_POST)) {      //$_POST（submitされた値）が空ではない時に以下のエラーチェックを走らせる

    if ($_POST['name'] === '') {				//もし名前が空だった場合$error['name']を'blank'とする
        $error['name'] = 'blank';
    }
    if ($_POST['email'] === '') {			//もしemailが空だった場合$error['email]を'blank'とする
        $error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {			//もしpasswordが4桁未満だった場合$error['password']を'length'とする
        $error['password'] = 'length';
    }
    if ($_POST['password'] === '') {			//もしpasswordが空だった場合$error['password']を'blank'とする
        $error['password'] = 'blank';
    }

    // アカウントの重複チェック
if (empty($error)) {  //usersテーブルから件数を取得しcntというショートカットに格納しプレースホルダでメアドを絞り込む
  $user = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
    $user->execute(array($_POST['email'])); //入力されたメアドがいれば１、なければ０が変える
    $record = $user->fetch();
    if ($record['cnt'] > 0) { //重複しているメアドがあればエラーにする
        $error['email'] = 'duplicate';
    }
}

    if (empty($error)) {			//もし$errorが空だった場合confirm.phpにジャンプする
  $_SESSION['join'] = $_POST;     //$_SESSIONの['join']という配列に対し＄＿POSTの内容を保存する(代入する)
  header('Location: confirm.php');
        exit();
    }
}
//修正ボタンを押された時の処理
if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {       //URLパラメーターに'rewrite'が付いている且つSESSIONが正しく設定されている時は
  $_POST = $_SESSION['join'];     //入力したデータを$_POSTに戻す
}
?>
<!doctype html>
<html lang="ja">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
    integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <title>登録フォーム!</title>
</head>


<body>
  <div class="container mt-5 jumbotron">
    <form method="post" action="">
      <div class="title mb-5">
        <h1 class="text-center">新規登録</h1>
      </div>
      <!-- 名前 -->
      <div class="form-group">
        <label>ニックネーム<span class="ml-2 bg-danger text-white">必須</span></label>
        <input type="text" name="name" class="form-control"
          value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>">
        <?php if ($error['name'] === 'blank'): ?>
        <!-- //もしニックネームが未入力なら以下を表示する -->
        <p class="text-danger">* ニックネームを入力してください！</p>
        <?php endif; ?>
      </div>
      <!-- メールアドレス -->
      <div class="form-group">
        <label class="label">メールアドレス<span class="ml-2 bg-danger text-white">必須</span></label>
        <input class="form-control" type="email" name="email" placeholder="info@sample.com"
          value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>">
        <?php if ($error['email'] === 'blank'): ?>
        <!-- //もしメールアドレスが未入力なら以下を表示する -->
        <p class="text-danger">* メールアドレスを入力してください！</p>
        <?php endif; ?>
        <?php if ($error['email'] === 'duplicate'): ?>
        <!-- //もしメールアドレスが重複しているなら以下を表示する -->
        <p class="text-danger">* 入力されたメールアドレスは既に登録されています！</p>
        <?php endif; ?>
      </div>
      <!-- パスワード -->
      <div class="form-group">
        <label class="label">パスワード<span class="ml-2 bg-danger text-white">必須</span></label>
        <input class="form-control" type="password" name="password" maxlength="8" pattern="^[0-9A-Za-z]+$"
          placeholder="半角英数字8桁" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>">
        <?php if ($error['password'] === 'length') :?>
        <!-- //もしパスワードが４桁未満なら以下を表示する -->
        <p class="text-danger">* パスワードは<span class=font-weight-bold>４桁以上</span>で入力してください！</p>
        <?php endif; ?>
        <?php if ($error['password'] === 'blank') :?>
        <!-- //もしパスワードが未入力なら以下を表示する -->
        <p class="text-danger">* パスワードを入力してください！</p>
        <?php endif; ?>
      </div>

      <!-- 登録ボタン -->
      <div class="mt-5 text-center">
        <input type="checkbox" class="form-check-input" required>
        <label class="form-check-label mb-5" for=""><a href="#">利用規約</a>に同意する</label><br>
        <button class="btn btn-info mx-auto" style="width: 200px;" type="submit">確認する</button>
        <p class="mt-3"><a href="login.php">既にご登録済みの方はコチラからログイン</a></p>
      </div>

    </form>
  </div>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
    integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
    integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
  </script>
</body>


</html>