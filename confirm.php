<?php
session_start();
require('connectDb.php');
// 直リン対策(内容検査)
if(!isset($_SESSION['join'])) {     //$_SESSION['join']に内容が入っていない場合の処理(!で否定形)
  header('Location: index.php');      //入力画面を正しく通過せずに呼び出された場合、強制的にTOPに飛ばす処理
  exit();
}
if (!empty($_POST)) {     //POSTの内容が空でないdenai場合(confirm.phpの登録ボタンを押した時)の処理  ※入力フォームはないがhiddenのsubmitで送信したことになる
$stmt = $db->prepare('INSERT INTO users SET name=?, email=?, password=?, created=NOW()');   //DBに接続(更新)する処理
$stmt->execute(array(   //どれを取り出すか指定
  $_SESSION['join']['name'],
  $_SESSION['join']['email'],
  sha1($_SESSION['join']['password'])   //数値を暗号化(返り値は40文字の16進数)
));
unset($_SESSION['join']);   //DBに記録し終わったら入力情報をリセットする

header('Location: done.php');
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

    <title>登録フォーム!</title>
  </head>


  <body>


    <div class="container mt-5 jumbotron">


      <div class="title">
        <h2>登録内容を確認してください。</h2>
      </div>


      <div class="contact-form">


        <form method="post" action="">
          <input type="hidden" name="action" value="submit"/> <!--hiddenで送信扱いにする-->

          <div class="items">

            <!-- 名前 -->
            <div class="item">
              <label>ニックネーム:</label>
              <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?><br>      <!-- 二次元配列＄＿SESSION['join']の中の['name']を取り出す処理をして表示 -->
              <!--htmlspecialchars(HTMLエンティティ化)とは、>（大なり）や""（ダブルクォート）など、特殊な意味を持つ特殊文字を、特殊な意味を持たない単なる文字列に変換すること。フォームなどでユーザーが悪意のあるスクリプトを送信しようとするのを防いでくれたりするので、セキュリティ上必須-->
            </div>

            <!-- メールアドレス -->
            <div class="item">
              <label>メールアドレス:</label>
              <?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?><br>      <!-- 二次元配列＄＿SESSION['join']の中の['email']を取り出す処理をして表示 -->
            </div>

            <!-- パスワード -->
            <div class="item">
              <label>パスワード:</label>
              ********<br>    <!--$_SESSION['join']に格納されている-->
            </div>

            <!-- 登録ボタン -->
            <div class="btn-area">
              <a href="index.php?action=rewrite">修正</a>
              <input type="submit" value="登録">
            </div>


          </div>


        </form>


      </div>


    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>


</html>