<?php

namespace lib;

class Login extends Model {

    private $_errors;

    public function login() {

    // もしログインボタンが押されたら
        if (isset($_POST['login'])) {
            $loginId = filter_input(INPUT_POST, 'loginId');
            $password = filter_input(INPUT_POST, 'password');

            // 前後の半角・全角の削除
            $_SESSION['loginId'] = trim($loginId);
            $_SESSION['password'] = trim($password);

            // バリデーションエラー
            $loginId_Errors = $this->loginIdValidate();
            $password_Errors = $this->passwordValidate();

            // バリデーションエラーがあればログイン画面に遷移
            if (isset($loginId_Errors)|| isset($password_Errors)) {
                $_SESSION['not_match'] = '';
                $_SESSION['loginId_Errors'] = $loginId_Errors;
                $_SESSION['password_Errors'] = $password_Errors;
                header('Location:' . SITE_URL . '/index.php');
                exit;
            }

                $_SESSION['not_match'] = '';

                // ログインID・パスワードの認証
                try {
                    $this->certification();

                    // エラーがある場合
                    if (count($_SESSION['not_match']) > 0) {
                        $_SESSION['loginId_Errors'] = '';
                        $_SESSION['password_Errors'] = '';
                        header('Location:' . SITE_URL . '/index.php');
                        exit;
                    } else {
                        unset($_SESSION['not_match']);
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit;
                }

        }
    }

    // ログインID・パスワードの認証メソッド
    private function certification() {

        // アカウントで検索
        $statement = $this->dbh->prepare("SELECT * FROM Employee WHERE No = (:No)");
        $statement->bindValue(":No", $_SESSION['loginId'], \PDO::PARAM_STR);
        $statement->execute();

        // アカウントが一致
        if ($row = $statement->fetch()) {
            $password_hash = $row['Password'];
            $name = $row['Name'];

            // パスワードが一致
            if (password_verify($_SESSION['password'], $password_hash)) {

            // セッションハイジャック対策
                session_regenerate_id(true);

                $_SESSION['session_name'] = $name;

                // トークン生成
                $TOKEN_LENGTH = 16;//16*2=32バイト
                $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
                $_SESSION['login'] = bin2hex($bytes);

                // パスワードをセッションから解除
                $_SESSION['password'] = '';
                $_SESSION['No'] = '';

                header('Location:' . SITE_URL . '/worker_list.php');
                exit;
            } else {
                $_SESSION['not_match'] = "ログインIDまたはパスワードが一致しません。";
            }
        } else {
            $_SESSION['not_match'] = "ログインIDまたはパスワードが一致しません。";
        }
    }


    // ログインIDのバリデーション
    private function loginIdValidate() {

        //もし空だったら
        if (empty($_SESSION['loginId'])) {
            return $this->_errors['loginId'] = "ログインIDを入力してください。";
        } elseif (!preg_match('/^[0-9a-zA-Z]+$/', $_SESSION['loginId'])) {
            return $this->_errors['loginId'] = "ログインIDは半角英数で入力してください。";
        }
    }


    // パスワードのバリデーション
    private function passwordValidate() {

        // もし空だったら
        if (empty($_SESSION['password'])) {
            return $this->_errors['password'] = "パスワードを入力してください。";
        } elseif (!preg_match('/^[0-9a-zA-Z]+$/', $_SESSION['password'])) {
            return $this->_errors['password'] = "パスワードは半角英数で入力してください。。";
        }
    }
}
