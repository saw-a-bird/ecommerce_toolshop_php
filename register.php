<?php
include "short-header.php";
?>

<body>

    <div class="content">

        <?php
            session_start();

            if (isset($_SESSION["email"]) && !empty($_SESSION["email"])) {
                header('location: index.php');
                die();
            } else {
                include 'db.php';

                if (isset($_POST["register"])) {
                    $prenom = $_POST["prenom"];
                    $nom = $_POST["nom"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
        
                    $user_query = "SELECT * FROM users WHERE email = '$email'";
                    $run_query = mysqli_query($connection ,$user_query);
                    if(mysqli_num_rows($run_query) == 0) {

                        $reg_sql = "INSERT INTO users (prenom, nom, email, password) VALUES ('$prenom','$nom','$email','$password')";
                        
                        if ($connection->query($reg_sql) === TRUE) {
                            echo '<script> alert("success: New record created successfully"); </script>';

                            if(session_id() == '') {
                                session_start();
                            }
                            $_SESSION["email"] = $email;

                            header('location: index.php');
                            die();
                        } else {
                            echo '<script> alert("failed: '.$connection->error.'"); </script>';
                        }
                        
                    } else {
                        echo '<script> alert("failed: email already exists in db."); </script>';
                    }
                } else if (isset($_POST["login"])) {
                    $email = $_POST["log_email"];
                    $password = $_POST["log_password"];
            
                    $user_query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
                    $run_query = mysqli_query($connection, $user_query);
                    if(mysqli_num_rows($run_query) > 0) {
                        $row = mysqli_fetch_array($run_query);
                        $nom = $row["nom"];
                        $prenom = $row["prenom"];
                        
                        echo "<script> alert('success: Login successful, welcome $prenom $nom'); </script>";
                    //   setcookie("email", $email, time()*60); // add this line to remember me
                        $_SESSION["email"] = $email;

                        header('location: index.php');
                        die();
                    } else {
                        echo "<script> alert('failed: E-mail or password is invalid, try again.'); </script>";
                    }
                }
                $connection->close();
            }
        ?>
        <div class = "register_logos">
            <div class = "logos_group">
                <img class = "logos_item" src = "images/bosch.svg"/> 
                <img class = "logos_item" src = "images/varta.svg"/>
                <img class = "logos_item" src = "images/bolk.svg"/> 
                <img class = "logos_item" src = "images/bproauto.svg"/> 
                <img class = "logos_item" src = "images/fulmen.svg"/> 
                <img class = "logos_item" src = "images/continental.svg"/> 
            </div>
        </div>
        <div class = "unique_right_content">
            <div class = "page_type_buttons">
               <a href = "#" class = "page_selected_reg page_type_bt"> Register </a>
               <a href = "index.php" class = "page_type_home page_type_bt"> Home </a>
               <a href = "#" class = "page_type_log page_type_bt"> Login </a>
            </div>
            <img class = "main_logo" src = "images/header_bg.png"/>

            <form method = "post" class = "login_form">
                <div class="register_form_row">
                    <label for = "log_email" class="register_label">
                        <strong>E-mail:</strong>
                    </label>
                    <input type="email" class="contact_input" id = "log_email" name = "log_email" required>
                </div>
                <div class="register_form_row">
                    <label for = "log_password" class="register_label">
                        <strong>Password:</strong>
                    </label>
                    <input type="password" class="contact_input" id = "log_password" name = "log_password" required>
                </div>

                <div class="form_row">
                    <input type = "submit" name = "login" value = "Login &raquo;">
                </div>
            </form>

            <form method = "post" class = "register_form">
                <h2 class = "register_title" >
                    MES INFORMATIONS PERSONNELLES
                </h2>
                <div class="register_form_row">
                    <label for = "prenom" class="register_label">
                        <strong>Prénom:</strong>
                    </label>
                    <input type="text" class="contact_input" id = "prenom" name = "prenom" required>
                </div>
                <div class="register_form_row">
                    <label for = "nom" class="register_label">
                        <strong>Nom:</strong>
                    </label>
                    <input type="text" class="contact_input" id = "nom" name = "nom" required>
                </div>
                <div class="register_form_row">
                    <label for = "email" class="register_label">
                        <strong>E-mail:</strong>
                    </label>
                    <input type="email" class="contact_input" id = "email" name = "email" required>
                </div>
                <div class="register_form_row">
                    <label for = "mpasse" class="register_label">
                        <strong>Mot de passe:</strong>
                    </label>
                    <input type="password" class="contact_input" id = "mpasse" name = "password" required>
                </div>

                <h2 class = "register_title" >
                MES COORDONNÉES
                </h2>

                <div class="register_form_row">
                    <label for = "cpostal" class="register_label">
                        <strong>Code Postal:</strong>
                    </label>
                    <input type="text" class="contact_input" id = "cpostal" name = "cpostal" required>
                </div>
                <div class="register_form_row">
                    <label for = "ville" class="register_label">
                        <strong>Ville:</strong>
                    </label>
                    <input type="text" class="contact_input" id = "ville" name = "ville" required>
                </div>

                <div class="register_form_row">
                    <label for = "nvoie" class="register_label">
                        <strong>N° de Voie:</strong>
                    </label>
                    <input type="text" class="contact_input" id = "nvoie" name = "nvoie" required>
                </div>

                <div class="register_form_row">
                    <label for = "phone" class="register_label">
                        <strong>Telephone:</strong>
                    </label>
                    <input type="text" class="contact_input" id = "phone" name = "phone" required>
                </div>

                <div class="form_row">
                    <input type = "submit" name = "register" value = "Register &raquo;">
                </div>
            </form>
        </div>
    </div>
    <script>
    
    var buttons = document.querySelectorAll(".page_type_bt");
    var registerForm = document.querySelector(".register_form");
    var registerButton = buttons[0];
    var loginForm = document.querySelector(".login_form");
    var loginButton = buttons[2];
    
    loginButton.addEventListener("click", function() {
        loginButton.classList.add("page_selected_log");
        loginButton.classList.remove("page_type_log");
        registerButton.classList.remove("page_selected_reg");
        registerButton.classList.add("page_type_reg");
        loginForm.style.display = "block";
        registerForm.style.display = "none";
    });

    registerButton.addEventListener("click", function() {
        loginButton.classList.remove("page_selected_log");
        loginButton.classList.add("page_type_log");
        registerButton.classList.add("page_selected_reg");
        registerButton.classList.remove("page_type_reg");
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    });

    </script>
</body> 
</html>