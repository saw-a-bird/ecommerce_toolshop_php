<?php
session_start();

?>

<!DOCTYPE html>
    <html lang="en">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <head>
            <title>CIPA</title>
            <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
            <meta http-equiv="Cache-control" content="no-cache">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
            
            <link rel="stylesheet" type="text/css" href="style.css" />
        </head>
        
        <body>  
            <div id="main_container">
                <div id="header">
                    <div class = "header_top" >
                        <span class = "header_phone"><i class="fa fa-phone"></i> 98475438</span>
                        <span class = "header_mail"><i class="fa fa-envelope"></i> cipa3imed@gmail.com</span>
                        
                        <div class = "header_buttons">
                             <div id = "loggedout" style = "height:0px; width:0px; overflow:hidden;">
                                <a class= "header_sign header_button_title" href="myinfo.php">
                                <i class="fa fa-user-circle "></i> Profile
                                </a>
                                <a class= "header_sign header_button_title dropdown" href="index.php?action=exit"> <i class="fa fa-sign-out"></i> Se déconnecter
                                </a>
                            </div>
                            
                            <div id = "loggedin">
                                <a class= "header_sign header_button_title" href="register.php">
                                <i class="fa fa-pencil "></i> Enregister
                                </a>
                                <div class="dropdown header_sign">
                                    <a class="header_button_title sign-in_header"><i class="fa fa-sign-in"></i> Se connecter</a>
                                    <div class="dropdown-content">
                                        <div class="login-box">
                                            <form action="index.php" class="form" method="post" id="loginFormHeader">
                                                <input type="email" name="email" class="login-box-field email" autocomplete="off" placeholder="E-mail">
                                                <input type="password" name="password" class="login-box-field password" autocomplete="off" placeholder="Mot de passe">
                                                <a href="reset_password.php" class="forgot-password">Mot de passe oublié ?</a>
                                                <input type="submit" name = "login" value="Se connecter" class="ease0-5" >
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <?php

                            $action = isset($_GET['action']) ? $_GET['action'] : 'none';

                            if (isset($_POST["login"])) {

                                include 'db.php';

                                $email = $_POST["email"];
                                $password = $_POST["password"];
                        
                                $user_query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
                                $run_query = mysqli_query($connection, $user_query);
                                if(mysqli_num_rows($run_query) > 0) {
                                    $row = mysqli_fetch_array($run_query);
                                    $nom = $row["nom"];
                                    $prenom = $row["prenom"];
                                    
                                    echo "<script> console.log('Login successful, welcome $prenom $nom'); </script>";
                                 //   setcookie("email", $email, time()*60); // add this line to remember me
                                    if(session_id() == '') {
                                        session_start();
                                    }
                                    $_SESSION["email"] = $email;
                                } else {
                                    echo "<script> alert('failed: E-mail or password is invalid, try again.'); </script>";
                                }
                            }

                            if ($action == "exit" || !isset($_SESSION["email"])) {
                                $_SESSION["email"] = "";
                            } else {
                                $email = $_SESSION["email"];

                                echo "<script> 
                                var email = '$email';
                                
                                if (email.length > 0) {
                                    document.getElementById('loggedout').style.height = '';
                                    document.getElementById('loggedout').style.width = '';
                                    document.getElementById('loggedout').style.overflow = 'visible';
                                    document.getElementById('loggedin').style = 'height:0px; width:0px; overflow:hidden;';
                                }
                                </script>";
                            }

                        ?>

                    </div> 
                    <div class = "header_bottom">
                    <a href="index.php" class = "website_logo">
                        <img src = "images/header_bg.png"/>
                    </a>
                    <div class="search-container">
                        <form action="index.php" method = "get">
                            <input type="text" placeholder="Par reference..." name="search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <div class="shop-trunk">
                        <?php
                            include 'trunk.php';
                        ?>
                    </div>
                </div>
            </div>
                
            <div id="menu_tab">
                <li class="nav-drop">
                    <a class = "drop-link nav" href="#"> 
                        <i class="fa fa-ship"></i> Services 
                    </a>
                    <ul class="drop-menu">
                        <li><a class="drop-item" href="#"> Livraison</a></li>
                        <li><a class="drop-item" href="#"> Guarantie </a></li>
                    </ul>
                </li>
                <li ><a href="index.php" class="nav"> <i class="fa fa-home"></i> Accueil </a></li>
                <li><a href="contact.php" class="nav"> <i class="fa fa-send"></i> Contactez-nous</a></li>
            </div>


            <iframe style="display: none;" name = "target"></iframe>

            <div class='modal'>
                <div class='modal-content'>
                     <span class='close-button'></span>
                     <h2 class = 'carter_h'></h2>
                     
                </div>
            </div>

            <script>
                //Veuillez vous connecter.
                //Produit ajouté au panier avec succès.
                //Confirm.
                //Produit acheté avec succes.
                const modal = document.querySelector(".modal");
                const closeButton = document.querySelector(".close-button");
                const modalText = document.querySelector(".carter_h");

                function toggleModal(str = "") {
                    modalText.innerHTML = str;
                    $(".shop-trunk").load("trunk.php");
                    modal.classList.toggle("show-modal");
                }

                function windowOnClick(event) {
                    if (event.target === modal) {
                        toggleModal("");
                    }
                }

                closeButton.addEventListener("click", toggleModal);
                window.addEventListener("click", windowOnClick);
            </script>