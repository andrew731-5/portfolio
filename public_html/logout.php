<?php

require_once('../config/config.php');

// セッション変数を全て解除
$_SESSION = [];

// セッションを切断するにはセッションクッキーも削除する。(ブラウザ側)
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// セッションを破棄する(サーバー側でのセッションIDの破棄)
session_destroy();

// ログイン画面へリダイレクト
header('Location:' . SITE_URL . '/index.php');
