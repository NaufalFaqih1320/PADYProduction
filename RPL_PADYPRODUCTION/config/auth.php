<?php

require_once __DIR__ . "/app.php";
require_once __DIR__ . "/session.php";

/*==========================================
    OWNER
==========================================*/

function ownerOnly()
{
    checkLogin();

    if ($_SESSION['role'] != 'owner') {

        require_once "app.php";

        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

/*==========================================
    ADMIN
==========================================*/

function adminOnly()
{
    checkLogin();

    if ($_SESSION['role'] != 'admin') {

        require_once "app.php";

        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

/*==========================================
    CREW
==========================================*/

function crewOnly()
{
    checkLogin();

    if ($_SESSION['role'] != 'crew') {

        require_once "app.php";

        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

/*==========================================
    CLIENT
==========================================*/

function clientOnly()
{
    checkLogin();

    if ($_SESSION['role'] != 'client') {

        require_once "app.php";

        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

/*==========================================
    OWNER ATAU ADMIN
==========================================*/

function ownerAdmin()
{
    checkLogin();

    if (
        $_SESSION['role'] != 'owner' &&
        $_SESSION['role'] != 'admin'
    ) {

        require_once "app.php";

        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

/*==========================================
    ADMIN ATAU CREW
==========================================*/

function adminCrew()
{
    checkLogin();

    if (
        $_SESSION['role'] != 'admin' &&
        $_SESSION['role'] != 'crew'
    ) {

        require_once "app.php";

        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

?>