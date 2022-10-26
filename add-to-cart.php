
<?php

    session_start();

    $pid = $_GET['pid'];
    $pq = $_GET['pq'];

    if (!isset($_SESSION["trunk"][$pid])) {
        $_SESSION["trunk"][$pid] = $pq;
    } else {
        $_SESSION["trunk"][$pid] += $pq;
    }

?>

