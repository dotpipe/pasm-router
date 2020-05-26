<?php
    if (!isset($_SESSION))
        session_start();
    include("routes.php");

    $route = new Routes();
    if (file_exists($_COOKIE['PHPSESSID']))
        $route->load($_COOKIE['PHPSESSID']);
    
    
    if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('adduser'))// && count($route->QURY) >= 5)
    {
        if (!isset(($_SERVER['PHP_SELF'])))
            $_SERVER['PHP_SELF'] = "index.php";
        @$route->addUserToContract();
        @$route->route();
    }
    else if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('remuser'))// && count($route->QURY) >= 5)
    {
        if (!isset(($_SERVER['PHP_SELF'])))
            $_SERVER['PHP_SELF'] = "index.php";
        @$route->remUserFromContract();
        @$route->route();
    }
    if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('add'))// && count($route->QURY) >= 5)
    {
        if (!isset(($_SERVER['PHP_SELF'])))
            $_SERVER['PHP_SELF'] = "index.php";
        @$route->addContract();
        @$route->route();
    }
    else if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('remove'))// && count($route->QURY) >= 5)
    {
        if (!isset(($_SERVER['PHP_SELF'])))
            $_SERVER['PHP_SELF'] = "index.php";
        @$route->remContract();
        @$route->route();
    }
    else
    {
        if (!isset(($_SERVER['PHP_SELF'])))
            $_SERVER['PHP_SELF'] = "index.php";
        @$route->route();
    }
    $route->pasm->load_str($_COOKIE['PHPSESSID']); 
    $route->save($_COOKIE['PHPSESSID']);

?>
