<?php
    if (!isset($_SESSION))
        session_start();
    include("routes.php");

    $route = new Routes();
    if (file_exists($_COOKIE['PHPSESSID']))
        $route->load($_COOKIE['PHPSESSID']);
    
    
    if (strtolower($route->QURY['req']) == strtolower('add') && count($route->QURY) >= 5)
    {
        if (!isset($_SERVER['Referer']))
            $_SERVER['Referer'] = "index.php";
        @$route->addContract();
        @$route->route();
    }
    else if (strtolower($route->QURY['req']) == strtolower('remove') && count($route->QURY) >= 5)
    {
        if (!isset($_SERVER['Referer']))
            $_SERVER['Referer'] = "index.php";
        @$route->remContract();
        @$route->route();
    }
    else if (count($route->QURY) >= 5)
    {
        if (!isset($_SERVER['Referer']))
            $_SERVER['Referer'] = "index.php";
        @$route->route();
    }
    else if (count($route->QURY) < 5) {
        echo "Not Enough Arguments\rRecv, From, Target, Port, User";
    }
    $route->pasm->load_str($_COOKIE['PHPSESSID']); 
    $route->save($_COOKIE['PHPSESSID']);

?>
