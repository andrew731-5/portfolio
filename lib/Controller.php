<?php
namespace lib;

class Controller　{
    private $_errors;

    // コンストラクタ
    public function __construct() {

        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }
    }
}
