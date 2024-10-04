<?php
session_start();

class Session {
    public $msg = [];
    private $user_is_logged_in = false;

    function __construct() {
        $this->flash_msg();
        $this->userLoginSetup();
    }

    public function isUserLoggedIn() {
        return $this->user_is_logged_in;
    }

    public function login($user_id) {
        $_SESSION['user_id'] = $user_id;
    }

    private function userLoginSetup() {
        $this->user_is_logged_in = isset($_SESSION['user_id']);
    }

    public function logout() {
        unset($_SESSION['user_id']);
    }

    public function msg($type = '', $msg = '') {
        if (!empty($msg)) {
            // Handle different message types (d, i, w, s)
            if (strlen(trim($type)) == 1) {
                $type = str_replace(['d', 'i', 'w', 's'], ['danger', 'info', 'warning', 'success'], $type);
            }
            $_SESSION['msg'][$type] = $msg;
        } else {
            return $this->msg;
        }
    }

    private function flash_msg() {
        if (isset($_SESSION['msg'])) {
            $this->msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
        } else {
            $this->msg = [];
        }
    }

    public function has_msg() {
        return !empty($this->msg);
    }

    public function display_msg() {
        if ($this->has_msg()) {
            $output = '';
            foreach ($this->msg as $type => $message) {
                $output .= '<div class="alert alert-' . htmlspecialchars($type) . '">' . htmlspecialchars($message) . '</div>';
            }
            return $output;
        }
        return '';
    }
}

$session = new Session();
