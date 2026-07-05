<?php

require_once __DIR__ . "/app.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*==========================================
    CEK LOGIN
==========================================*/

function checkLogin()
{
    if (!isset($_SESSION['login'])) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

/*==========================================
    SUDAH LOGIN
==========================================*/

function alreadyLogin()
{
    if (isset($_SESSION['login'])) {

        switch ($_SESSION['role']) {

            case 'owner':
                header("Location: " . BASE_URL . "owner/dashboard.php");
                break;

            case 'admin':
                header("Location: " . BASE_URL . "admin/dashboard.php");
                break;

            case 'crew':
                header("Location: " . BASE_URL . "crew/dashboard.php");
                break;

            case 'client':
                header("Location: " . BASE_URL . "index.php");
                break;
        }

        exit;
    }
}