<?php
namespace lib;

require_once('../config/config.php');

class Common {

  //　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移のゲッター
  public function getRequire_login() {
    return $this->require_login();
  }

  // // ログイン状態で直接アクセスされた場合のゲッター
  public function getRequire_unlogin() {
    return $this->require_unlogin();
  }

    private function require_login() {
      //　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
      if (!isset($_SESSION['login'])) {
        header('Location:' . SITE_URL . '/index.php');
        exit;
      }
    }

    private function require_unlogin() {
      // ログイン状態で直接アクセスされた場合社員一覧画面に遷移
      if ($_POST['token'] != sha1(session_id()) ){
        header('Location:' . SITE_URL . '/worker_list.php');
        exit;
      }

    }
}
