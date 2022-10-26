<?php
    include "header.php";
?>

    <div id="main_content_contact">
        <div class="center_cc">
            <div class="contact_form">

                <div class="form_row">
                    <label class="contact"><strong>Nom: </strong></label>
                    <input type="text" id = "nom_input" class="contact_input" />
                </div>
                <div class="form_row">
                    <label class="contact"><strong>E-mail: </strong></label>
                    <input type="email" id = "email_input" class="contact_input" />
                </div>
                <div class="form_row">
                    <label class="contact"><strong>Destinataire: </strong></label>
                    <select name="select" class = "contact_select">
                        <option value="mod" selected>Modérateur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <div class="form_row">
                    <label class="contact"><strong>Message: </strong></label>
                    <textarea class="contact_textarea" ></textarea>
                </div>
                <div class="form_row">
                    <a href="#" class="prod_details">Send</a> 
                </div>
            </div>
            <?php
                $email = $_SESSION["email"];
                if (isset($email)) {
                    $user_query = "SELECT nom, prenom, email FROM users WHERE email = '$email'";
                    $run_query = mysqli_query($connection, $user_query);
                    if(mysqli_num_rows($run_query) > 0) {
                        $row = mysqli_fetch_array($run_query);
                        $nom = $row["nom"];
                        $prenom = $row["prenom"];
                        echo "<script>
                            document.getElementById('email_input').value = '$email';
                            document.getElementById('email_input').disabled = true;
                            document.getElementById('email_input').style.backgroundColor ='#EBECF0';
                            document.getElementById('nom_input').value = '$nom $prenom';
                            document.getElementById('nom_input').style.backgroundColor = '#EBECF0';
                            document.getElementById('nom_input').disabled = true;
                            </script>
                        ";
                    }
                }
            ?>
        </div>
    </div>
<!-- end of main content -->
</div>
<!-- end of main_container -->
<?php
    include "footer.php";
?>

</body>


