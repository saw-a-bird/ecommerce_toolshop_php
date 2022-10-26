
<?php
    if(session_id() == '') {
        session_start();
    }

    $count = isset($_SESSION["trunk"]) ? count($_SESSION["trunk"]) : 0;
    if ($count > 0)
        echo "<a href = 'trunk-menu.php' class= 'trunkitself'> </a>";
    else
        echo "<a href = '#' class= 'trunkitself' style = 'cursor:default;'> </a>";
        
    include 'db.php';
    $item_price = 0;
    $item_count = 0;

    if (!isset($_SESSION["trunk"])) {
        $_SESSION["trunk"] = array();
    }

    if (count($_SESSION["trunk"]) > 0) {
        // $i as ref and $value as stock;
        foreach ($_SESSION["trunk"] as $i => $value) {
            $item_count += $value;
            $product_query = "SELECT * FROM products WHERE REF = '$i'";
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $run_query = mysqli_query($connection, $product_query);
            if(mysqli_num_rows($run_query) > 0) {
                $row = mysqli_fetch_array($run_query);
                $item_price += ($value*$row['prix']);
            }
        }
    }

    echo "
        <div class= 'trunk-info' id = 'trunk-info'>
            <span class='item_count'> ". $item_count ." articles: </span> 
            <span class='item_price'> $item_price TND </span>
        </div>
    ";
?>