<?php
session_start();

$_SESSION = array(); //セッションの情報を削除するので空っぽの配列を代入
if (ini_get('session.use_cookies')) {   //常套句
  $params = session_get_cookie_params();
  setcookie(session_name() . '', time() - 42000,
  $params['path'], $params['domain'], $params['secure'], $params['httponly']);  //セッションのクッキーが使っているオプションを指定
}
session_destroy(); //セッションのデータを破棄

setcookie('email', '', time()-3600);

header('Location: login.php');
exit();
?>