<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include 'db.php';
    
    $item_price = 0;
    $item_count = 0;

    if (count($_SESSION["trunk"]) > 0) {
        $whereQuery = "";
        // $i = ref and $value = stock
        $keys = 0;
        foreach ($_SESSION["trunk"] as $i => $value) {
            if (isset($_GET[$i])) {
                $_SESSION["trunk"][$i] = $_GET[$i];
            }

            if ($_SESSION["trunk"][$i] == 0) {
                unset($_SESSION["trunk"][$i]);
            } else {
                if ($keys >= 1)
                    $whereQuery .= " OR ";

                $whereQuery .= "REF = '$i'";
                $item_count += $value;
                $keys++;
            }
        }

        $validation = false;
        if (isset($_GET["validation"]) && isset($_SESSION["email"])) {
            $validation = true;
        }

        function emptySpace() {
            echo "<div class = 'espace_vide'> <h1> Votre panier est vide ... </h1>
            
            <a href = 'index.php' class = 'prod_details' >&laquo; Faire mes achats</a>
            </div>";
        }

        if (empty($whereQuery)) {
            echo "<div class = 'espace_vide'> <h1> Votre panier est vide ... </h1>
            
            <a href = 'index.php' class = 'prod_details' >&laquo; Faire mes achats</a>
            </div>";
        } else {
            if (!$validation) {
                echo "
                <h1 class = 'trunk_container_title'> Vos Articles : ($item_count articles)</h1>
                <table id = 'main_trunk'>";
            } else {
                $sayinS = "produit";
                if ($item_count > 1) 
                    $sayinS .= "s";
                echo "<div class = 'espace_vide'> <h1> Merci d'avoir acheté notre $sayinS... </h1>
            
                <a href = 'index.php' class = 'prod_details' >&laquo; Retourner</a>
                </div>";
            }
            $product_query = "SELECT * FROM products WHERE $whereQuery";
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $run_query = mysqli_query($connection, $product_query);
            if(mysqli_num_rows($run_query) > 0) {
                while($row = mysqli_fetch_array($run_query)){
                    $pro_id    = $row['REF'];
                    $quantity_trunk = $_SESSION["trunk"][$pro_id];
                    if (isset($_GET["validation"]) && isset($_SESSION["email"])) {
                        if ($row['stock'] > 0) {
                            if (($quantity_trunk < 1 || $quantity_trunk > $row['stock'])) {
                                $quantity_trunk = 1;
                                $_SESSION["trunk"][$pro_id] = $quantity_trunk;
                            }
                            $newStock = $row['stock'] - $quantity_trunk;
                            $pro_query = "UPDATE products SET stock = $newStock WHERE REF = '$pro_id'";
                            $update_query = mysqli_query($connection, $pro_query);
                            $_SESSION["trunk"][$pro_id] = null;
                        }
                    } else {
                        $pro_stock   = $row['stock'];
                        $pro_img = explode(";", $row['img'])[0];
                        $pro_once = $row['prix'];
                        $pro_prix = ($quantity_trunk*$pro_once);
                        $pro_reduction = $pro_prix-($pro_prix*$row['remise']/100);
                        $item_price += $pro_reduction;
                        $pro_amp = $row["amperage"]; 
                        $pro_puiss = $row["puissance_au_dem"];
                        // if type == batterie then
                        // 	$pro_amp = $row["amperage"]; $pro_puiss = $row["puissance_au_dem"];
                        // name = Batterie de démarrage ".$pro_amp."Ah ".$pro_puiss."A

                        echo "
                        <tr class = 'trunk_item'>
                            <td class = 't_item_img'><img src = '$pro_img' /></td>
                            <td class = 't_item_text'>
                                <p class = 't_item_title'>Batterie de démarrage ".$pro_amp."Ah ".$pro_puiss."A</p>
                                <p class = 't_item_ref'>Ref : $pro_id</p>
                                <p class = 't_item_tq'>Quantity : <input type='number' min='1' max='$pro_stock' value='$quantity_trunk' class = 't_item_quantity' onchange = 'quantityChange(this)' id = '$pro_id'><span id = 'stock_check'>en stock</span></p>
                            </td>
                            <td class = 't_item_price'>
                                <p class = 't_item_unitaire'>($pro_once TND prix unitaire)</p>
                                <p class = 't_item_pnew'>$pro_reduction TND</p>
                                <p class = 't_item_pold'>$pro_prix TND</p>
                                <a onclick = 'quantityZero(\"$pro_id\")' class = 'supprimer_butt'>Supprimer</a>
                            </td>
                        </tr>";
                    }
                }
            }

            if (!$validation) { 
                $tva = number_format((($item_price*19)/100), 3, '.', '');
                echo "
                    <tr class = 'trunk_item trunk_total'>
                        <td class = 't_item_total' colspan='2'>
                            <h1>Total</h1>
                        </td>
                        <td class = 't_item_price'>
                            <p class = 't_item_unitaire'>$item_price TND</p>
                            <p class = 't_item_pold'>(19% TVA) $tva TND</p>
                            <p class = 't_item_pnew'>".($item_price+$tva)." TND</p>
                        </td>
                    </tr>
                </table> 
                <div class = 'payment_buttons'>
                    <p class = 'payment_methode'>Choisir la methode de payement :
                        <select name='payment' id='payments' form='payform'>
                            <option value='cbancaire'>Carte bancaire</option>
                            <option value='paypal'>Paypal</option>
                            <option value='virbancaire'>Virement bancaire</option>
                            <option value='visa'>Visa</option>
                        </select>
                    </p>
                </div>
                <a onclick = 'validation()' class = 'validation_button'>Passer la commande  &raquo;</a>";   
            }
        }
    } else {
        echo "<script> location.href = 'index.php'; </script>";
    }
?>