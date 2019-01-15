<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();
// ログイン状態で直接アクセスされた場合社員一覧画面に遷移
$loginCheck->getRequire_unlogin();

$No = $_SESSION['NoDelete'];

try {
    // 削除IDの取得
    $db = new lib\Model();
    // Db接続
    $stat = $db->getDbh();
    // クエリの作成
    $sql = "UPDATE Employee SET DeleteFlg = 1 WHERE No = $No";
    // クエリの実行
    $statement = $stat->query($sql);

} catch (PDOException $e) {
    echo $e->getMessage();
}

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('delete_complate.html', array(

  'session_name' => $_SESSION['session_name'],
  'token' => sha1(session_id())
));
