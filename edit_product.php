<?php
    include "header.php";
?>

<div id="main_content_product">
    <div class="center_cc">
        <form class="contact_form">
            <br/>
            <h2 class = 'pdinfo_titre'>
                INFORMATION DE BASE
            </h2>
            <br/>

            <input type = 'hidden' id = "callAction" name = 'action' class = 'contact_input' value = "edit">

            <div class='form_row'>
                <label class='edit'><strong>REF:</strong></label>
                <input type = 'text' name = 'REF' class = 'contact_input' oninput='this.value = this.value.toUpperCase()'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Stock:</strong></label>
                <input type = 'text' name = 'stock' class = 'contact_input'>
            </div>
            
            <div class='form_row'>
                <label class='edit'><strong>Prix:</strong></label>
                <input type = 'text' name = 'prix' class = 'contact_input'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Remise:</strong></label>
                <input type = 'text' name = 'remise' class = 'contact_input'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Images:</strong></label>
                <input type = 'text' name = 'img' class = 'contact_input' value = "images/products/">
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Guarantie:</strong></label>
                <input type = 'text' name = 'guarantie' class = 'contact_input'>
            </div>

            <br/>
            <h2 class = 'pdinfo_titre'>
                CARACTÉRISTIQUES TECHNIQUES
            </h2>
            <br/>

            <div class='form_row'>
                <label class='edit'><strong>Marque:</strong></label>
                <input type = 'text' name = 'marque' class = 'contact_input' oninput='this.value = this.value.toUpperCase()'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Amperage [Ah]:</strong></label>
                <input type = 'text' name = 'amperage' class = 'contact_input'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Puissance au demarrage [A]:</strong></label>
                <input type = 'text' name = 'puissance_au_dem' class = 'contact_input'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Type au bac:</strong></label>
                <input type = 'text' name = 'type_de_bac' class = 'contact_input'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Polarité:</strong></label>
                <input type = 'text' name = 'polarite' class = 'contact_input'>
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Dimension en mm:</strong></label>
                <input type = 'text' name = 'dimensions' class = 'contact_input' placeholder= "hauter x largeur x longeur">
            </div>

            <div class='form_row'>
                <label class='edit'><strong>Start et Stop:</strong></label>
                <input type = 'text' name = 'start_et_stop' class = 'contact_input'>
            </div>
            <br/>
            <h2 id = 'pdinfo_titre' class = 'pdinfo_titre'>
                ALTERNATIVES
            </h2>
            <div class='form_row'>
                <label class='edit'><strong>Alternatives:</strong></label>
                <input type = 'text' name = 'alternatives' class = 'contact_input'>
            </div>
            <br/>
			<br/>
            <div class='form_row'>
                <input id = 'save_button' type = 'submit' value = 'Save &raquo;'>
                <input id = 'remove_button' type = 'submit' value = 'Remove'>
            </div>

            <br/>
			<br/>
			<br/>

			<script>

                
                var removeButton = document.getElementById("remove_button");
                var saveButton = document.getElementById("save_button");
                var hidden = document.getElementById("callAction");

                removeButton.addEventListener("click", function() {
                    hidden.value = "remove";
                });

                var inputText = document.querySelectorAll("#main_content_product input[type=text]");
				function editInput(args) {
                    $.each(args, function(index, value) {
                        if (isNaN(index))
                            return false; 
                        else
                            inputText[index].value = value;
                        
                    });
                }


			</script>

            <?php 

                if (isset($_SESSION["email"]) && $_SESSION["email"] == "admin@cipa.com") {
                    $pro_query = "";
                    include 'db.php';
                    if (isset($_GET['REF']) && !empty($_GET['REF'])) {
                        $product_id = $_GET['REF'];

                        function loadForm($product_id, $connection) {
                            $product_query = "SELECT * FROM products WHERE REF = '$product_id'";
                            $run_query = mysqli_query($connection, $product_query);
                            if(mysqli_num_rows($run_query) > 0) {
                                $row = mysqli_fetch_array($run_query);
                                echo "<script> editInput(".json_encode($row)."); </script>";
                            }
                        }

                        if (isset($_GET['action'])) {
                            if ($_GET['action'] == "edit") {
                                $setQuery = "";
                                $last_key = array_key_last($_GET);
                                foreach ($_GET as $key => $value) {
                                    if ($key != "action") {
                                        $setQuery .= " $key = '$value' ";
                                        if ($last_key != $key) {
                                            $setQuery .= ",";
                                        }
                                    }
                                }

                                $pro_query = "UPDATE products SET $setQuery WHERE REF = '$product_id'";
                                $update_query = mysqli_query($connection, $pro_query);
                                loadForm($product_id, $connection);
                                echo "<script> setTimeout(function() {
                                        location.href = 'product.php?p=$product_id';
                                    }, 3000);
                                    toggleModal('Produit a été modifié avec succès. Rediriger...'); </script>";

                            } else if ($_GET['action'] == "add") {
                                unset($_GET['action']);
                                
                                $pro_query = "INSERT INTO products (".implode(", ", array_keys($_GET)).") VALUES ('".implode("', '", $_GET)."')";
                                $update_query = mysqli_query($connection, $pro_query);
                                echo "<script> setTimeout(function() {
                                    location.href = 'index.php';
                                }, 3000);
                                toggleModal('Produit ajouté à la base de données. Rediriger...'); </script>";
                                
                            } else if ($_GET['action'] == "remove") {
                                $pro_query = "DELETE FROM products WHERE REF = '$product_id'";
                                $update_query = mysqli_query($connection, $pro_query);
                                echo "<script> setTimeout(function() {
                                        location.href = 'index.php';
                                    }, 3000);
                                    toggleModal('Produit supprimé de la base de données. Rediriger...'); </script>";
                            }
                        } else {
                            loadForm($product_id, $connection);
                        }
                    } else {
                        if (isset($_GET['action']) && $_GET['action'] == "add") {
                            echo "<script> hidden.value = 'add';
                            removeButton.style.display = 'none';
                            saveButton.value = 'Add »';
                            </script>";
                        } else {
                            echo "<script> toggleModal('Error.') </script>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
<!-- end of main content -->
</div>

</body>
	<?php
		include "footer.php";
	?>
