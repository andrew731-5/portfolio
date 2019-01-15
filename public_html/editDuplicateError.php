<?php

require_once ('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once ('../vendor/autoload.php');



// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('editDuplicateError.html', array(

  'session_name' => $_SESSION['session_name']
));
