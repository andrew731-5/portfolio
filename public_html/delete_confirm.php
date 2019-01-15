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

    //
    // 社員情報の表示
    //
    $No = $_POST['No'];

    try {
        // 削除IDの取得
        $db = new lib\Model();
        // Db接続
        $stat = $db->getDbh();
        // クエリの作成
        $sql = "SELECT No, Name, MailAddress from Employee
  WHERE No = $No";
        // クエリの実行
        $statement = $stat->query($sql);
        // 結果を変数に代入
        foreach ($statement as $value) {
            $_SESSION['NoDelete'] = $value['No'];
            $name = $value['Name'];
            $MailAddress = $value['MailAddress'];
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }


// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('delete_confirm.html', array(

  'No' => $_SESSION['NoDelete'],
  'name' => $name,
  'MailAddress' => $MailAddress,
  'session_name' => $_SESSION['session_name'],
  'token' => sha1(session_id())
));
