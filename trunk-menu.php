<?php
    include "header.php";
    if (isset($_SESSION['email']))
        echo "<script>var emailSession = '".$_SESSION['email']."';</script>";
    else
        echo "<script>var emailSession = '';</script>";
?>

    <div id="main_trunk_content">
        <script>
            var confirmBuy = false;
            function quantityChange(e) {
                if (e.value <= 0) {
                    e.value = 0;
                }
                var xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    document.querySelector(".trunk_container").innerHTML = this.responseText;
                    $(".shop-trunk").load("trunk.php");
                }
                xhttp.open("GET", "trunk-content.php?"+e.id+"="+e.value);
                xhttp.send();
            }

            function quantityZero(id) {
                var xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    document.querySelector(".trunk_container").innerHTML = this.responseText;
                    $(".shop-trunk").load("trunk.php");
                }
                xhttp.open("GET", "trunk-content.php?"+id+"=0");
                xhttp.send();
            }

            function validation() {
                if (!confirmBuy) {
                    confirmBuy = true;
                    toggleModal("Confirm?");
                } else if (emailSession.length > 0) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onload = function() {
                        document.querySelector(".trunk_container").innerHTML = this.responseText;
                        $(".shop-trunk").load("trunk.php");
                    }
                    xhttp.open("GET", "trunk-content.php?validation=true");
                    xhttp.send();

                    toggleModal("Produit acheté avec succes.");
                } else {
                    toggleModal("Veuillez vous connecter.");
                }
            }

        </script>
        <div class="trunk_container">
            <?php
                include 'trunk-content.php';
            ?>
        </div>
    </div>
<!-- end of main content -->
</div>
<!-- end of main_container -->
<?php
    include "footer.php";
?>


