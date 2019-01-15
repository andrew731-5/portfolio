<?php
require_once ('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once ('../vendor/autoload.php');

isset ($_SESSION['loginId_Errors']) ? $_SESSION['loginId_Errors'] : $_SESSION['loginId_Errors'] = '';
isset ($_SESSION['password_Errors']) ? $_SESSION['password_Errors'] : $_SESSION['password_Errors'] = '';
isset ($_SESSION['not_match']) ? $_SESSION['not_match'] : $_SESSION['not_match'] = '';
isset ($_SESSION['loginId']) ? $_SESSION['loginId'] : $_SESSION['loginId'] = '';
isset ($_SESSION['password']) ? $_SESSION['password'] : $_SESSION['password'] = '';

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('index.html', array(

// 'validation_Errors' => $_SESSION['validation_Errors'],
'loginId_Errors' => $_SESSION['loginId_Errors'],
'password_Errors' => $_SESSION['password_Errors'],
'not_match' => $_SESSION['not_match'],
'loginId' => $_SESSION['loginId'],
'password' => $_SESSION['password']
));
