<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

// ページの有効期限切れの対策
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();
// ログイン状態で直接アクセスされた場合社員一覧画面に遷移
$loginCheck->getRequire_unlogin();

// トークン発行
$TOKEN_LENGTH = 16;//16*2=32バイト
$bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
$token = bin2hex($bytes);
$_SESSION['duplication_token'] = $token;

//
// 入力内容をセッションに入れる
//
$_SESSION['No'] = filter_input(INPUT_POST, 'No');
$_SESSION['name'] = filter_input(INPUT_POST, 'name');
$_SESSION['email'] = filter_input(INPUT_POST, 'email');
$_SESSION['password'] = filter_input(INPUT_POST, 'password');

// パスワードを最初と最後意外伏せ字にする
$password = substr($_SESSION['password'], 0, 1) . '●●●●●●' .  $_SESSION['password'][strlen($_SESSION['password']) - 1];

$validation = new \lib\Validation();
//社員ID バリデーション
$_SESSION['noError'] = $validation->noCheck();
// 氏名 バリデーション
$_SESSION['nameError'] = $validation->nameCheck();
// メアド バリデーション
$_SESSION['emailError'] = $validation->emailCheck();
// パスワード バリデーション
$_SESSION['passwordError'] = $validation->passwordCheck();
// 社員ID 重複チェック
$_SESSION['no_duplication'] = $validation->no_duplication();
// メアド　重複チェック
$_SESSION['email_duplication'] = $validation->email_duplication();

// バリデーションエラーがあれば、登録フォームにリダイレクト
if (isset($_SESSION['noError']) || isset($_SESSION['nameError'])
  || isset($_SESSION['emailError']) || isset($_SESSION['passwordError'])
  || isset($_SESSION['no_duplication']) || isset($_SESSION['email_duplication'])) {
  header('Location:' . SITE_URL . '/signUp_form.php');
}

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('signUp_confirm.html', array(

  'No' => $_SESSION['No'],
  'name' => $_SESSION['name'],
  'email' => $_SESSION['email'],
  'password' => $password,
  'session_name' => $_SESSION['session_name'],
  'token' => sha1(session_id()),
  'duplication_token' => $_SESSION['duplication_token']
));
