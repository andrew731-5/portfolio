<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

//　セッション変数の保持
isset($_SESSION['No']) ?  $_SESSION['No']: $_SESSION['No'] = '';
isset($_SESSION['password']) ?  $_SESSION['password']: $_SESSION['password'] = '';
isset($_SESSION['name']) ?  $_SESSION['name']: $_SESSION['name'] = '';
isset($_SESSION['email']) ?  $_SESSION['email']: $_SESSION['email'] = '';
isset($_SESSION['noError']) ?  $_SESSION['noError']: $_SESSION['noError'] = '';
isset($_SESSION['nameError']) ?  $_SESSION['nameError']: $_SESSION['nameError'] = '';
isset($_SESSION['emailError']) ?  $_SESSION['emailError']: $_SESSION['emailError'] = '';
isset($_SESSION['passwordError']) ?  $_SESSION['passwordError']: $_SESSION['passwordError'] = '';
isset($_SESSION['email_duplication']) ?  $_SESSION['email_duplication']: $_SESSION['email_duplication'] = '';
isset($_SESSION['no_duplication']) ?  $_SESSION['no_duplication']: $_SESSION['no_duplication'] = '';

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('signUp_form.html', array(

  'No' => $_SESSION['No'],
  'name' => $_SESSION['name'],
  'email' => $_SESSION['email'],
  'password' => $_SESSION['password'],
  'session_name' => $_SESSION['session_name'],
  'noError' => $_SESSION['noError'],
  'nameError' => $_SESSION['nameError'],
  'emailError' => $_SESSION['emailError'],
  'passwordError' => $_SESSION['passwordError'],
  'no_duplication' => $_SESSION['no_duplication'],
  'email_duplication' => $_SESSION['email_duplication'],
  'token' => sha1(session_id())
));
