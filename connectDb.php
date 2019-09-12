<?php
//PDOとは PHP DATA OBJECTの略,データベース抽象化レイヤの一つ(変換プラグ的な役割)

try {   //接続を試してみてうまくいかなければ例外(エラー)として処理する
  $db = new PDO('mysql:dbname=login; host=127.0.0.1; port=8889; charset=utf8', 'root', 'root');
              //DSN(データーベースの名前,サーバー(マンション)のアドレス,ポート(マンションの号室),文字コード）,ユーザー名,パスワード
//データベースにアクセスできなかった時の処理
} catch(PDOException $e) {    //PDOException(例外)を変数$eにいれる
  print ('接続エラー: ' . $e->getMessage()); //$eの中のメッセージ(getMessage)を画面に出力する処理
}
// <!-- getMessage()とは例外メッセージを取得するメソッド。 -->
?>