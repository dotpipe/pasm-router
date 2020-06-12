<?php
    if (!isset($_SESSION))
        session_start();
    include("routes.php");

    $route = new Routes();
    if (file_exists("routing.ini"))
        $route->load("routing.ini");
    
    
    if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('addgroup'))
    {
        @$route->addGroupToContract();
        @$route->route();
    }
    else if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('adduser'))
    {
        @$route->addUserToContract();
        @$route->route();
    }
    else if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('remuser'))
    {
        @$route->remUserFromContract();
        @$route->route();
    }
    else if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('add'))
    {
        @$route->addContract();
        @$route->route();
    }
    else if (isset($route->QURY['req']) && strtolower($route->QURY['req']) == strtolower('remove'))
    {
        @$route->remContract();
        @$route->route();
    }
    $route->pasm->load_str("routing.ini"); 
    $route->save("routing.ini");

?>
