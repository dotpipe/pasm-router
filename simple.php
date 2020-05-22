<?php
    if (!isset($_SESSION))
        session_start();
    include("routes.php");

    $route = new Routes();
    if (file_exists($_COOKIE['PHPSESSID']))
        $route->load($_COOKIE['PHPSESSID']);
    
    
    if (count($route->QURY) >= 5)
    {
        $route->addContract();
        $route->route();
    }
    else {
        echo "Not Enough Arguments\nRecv, From, Target, Port, User";
    }
    $route->pasm->load_str($_COOKIE['PHPSESSID']); 
    $route->save($_COOKIE['PHPSESSID']);

    header("Location: simple.php?recv=localhost&from=localost&target=pasm-router/test.php&port=80&user=xiv");
?>

    <form method="POST" action="simple.php">
        <input type="hidden" name="recv" value="localhost">
        <input type="hidden" name="from" value="localost">
        <input type="hidden" name="target" value="pasm-router/test.php">
        <input type="hidden" name="port" value="80">
        <input type="hidden" name="user" value="xiv">
        <button onclick="submit">HEY!</button>
    </form>

