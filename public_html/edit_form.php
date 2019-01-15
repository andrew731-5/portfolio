<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

//
//  社員情報の表示
//

// 社員一覧画面からの遷移の場合、実行する
if ($_SERVER['HTTP_REFERER'] == SITE_URL . '/worker_list.php' or $_SERVER['HTTP_REFERER'] == SITE_URL . '/search_results.php'
    or $_SERVER['HTTP_REFERER'] == SITE_URL . '/searchEmpty.php') {

    $No = filter_input(INPUT_POST, 'No');

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
            $_SESSION['updateNo'] = $value['No'];
            $name = $value['Name'];
            $MailAddress = $value['MailAddress'];
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
  }


isset($_SESSION['No']) ? $_SESSION['No'] : $_SESSION['No'] = '';
isset($_SESSION['name']) ? $_SESSION['name'] : $_SESSION['name'] = '';
isset($_SESSION['email']) ? $_SESSION['email'] : $_SESSION['email'] = '';
isset($_SESSION['updateNo']) ? $_SESSION['updateNo'] : $_SESSION['updateNo'] = '';
isset($name) ? $name : $name = '';
isset($MailAddress) ? $MailAddress : $MailAddress = '';
isset($_SESSION['noError']) ? $_SESSION['noError'] : $_SESSION['noError'] = '';
isset($_SESSION['nameError']) ? $_SESSION['nameError'] : $_SESSION['nameError'] = '';
isset($_SESSION['emailError']) ? $_SESSION['emailError'] : $_SESSION['emailError'] = '';
isset($_SESSION['passwordError']) ? $_SESSION['passwordError'] : $_SESSION['passwordError'] = '';
isset($_SESSION['no_duplication']) ? $_SESSION['no_duplication'] : $_SESSION['no_duplication'] = '';
isset($_SESSION['email_duplication']) ? $_SESSION['email_duplication'] : $_SESSION['email_duplication'] = '';


// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('edit_form.html', array(

  'session_name' => $_SESSION['session_name'],
  'updateNo' => $_SESSION['updateNo'],
  'updateName' => $name,
  'MailAddress' => $MailAddress,
  'No' => $_SESSION['No'],
  'name' => $_SESSION['name'],
  'email' => $_SESSION['email'],
  'noError' => $_SESSION['noError'],
  'nameError' => $_SESSION['nameError'],
  'emailError' => $_SESSION['emailError'],
  'passwordError' => $_SESSION['passwordError'],
  'no_duplication' => $_SESSION['no_duplication'],
  'email_duplication' => $_SESSION['email_duplication'],
  'token' => sha1(session_id())
));
