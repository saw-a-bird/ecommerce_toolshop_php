
  
<?php
  if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    include 'db.php';
    session_start();
  }

  if (isset($_SESSION["email"]) && $_SESSION["email"] == "admin@cipa.com") {
    echo "<a href='edit_product.php?action=add' class = 'add_button'> <i class='fa fa-plus'></i> Ajouter</a>";
  }
?>
<div class="center_content">
<?php
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  echo "<script> var pageNumber = $page; </script>";

  $cpage = (($page-1) * 12);
  $whereArrays = array();
  $whereQuery = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page" && $key != "action" && $key != "type") {
      if (empty($whereQuery)) {
        $whereQuery .= "WHERE ";
      }

      if (!isset($whereArrays[$key])) {
        $whereArrays[$key] = "";
      } else {
        $whereArrays[$key] .= " OR ";
      }
      
      if ($key == "search") {
        $whereArrays[$key] .= "REF LIKE '%$value%'";
      } else if ($key == "prix") {
        echo "<script> document.getElementById('".$value."ap').checked = true;</script>";
        $whereArrays[$key] .= "$key >= '$value'";
      } else {
        if ($key == "amperage") {
          echo "<script> document.getElementById('".$value."ah').checked = true;</script>";
        } else {
          echo "<script> document.getElementById('$value').checked = true;</script>";
          if ($key == "dimensions") {
            $value = str_replace("x", " x ", $value);
          }
        }
        $whereArrays[$key] .= "$key = '$value'";
      }
    }
  }

  if (count($whereArrays) > 0) {
      $whereQuery .= "(".implode(') AND (', $whereArrays).")";
  }

  $product_query = "SELECT * FROM products $whereQuery LIMIT 13 OFFSET $cpage";
  $run_query = mysqli_query($connection, $product_query) or die(mysqli_error($connection));
  if(mysqli_num_rows($run_query) > 0) {
    $row_count = 0;
    while($row = mysqli_fetch_array($run_query) and $row_count < 12){
      $row_count ++;
      $pro_id    = $row['REF'];
      $pro_stock   = $row['stock']; 
      $pro_price = $row['prix']  + 0;
      $pro_marque = $row['marque'];
      $pro_img = explode(";", $row['img'])[0];
      $pro_reduction = $pro_price-($row['prix']*$row['remise']/100);

      $stockStorage = $pro_stock - (isset($_SESSION["trunk"][$pro_id]) ? $_SESSION["trunk"][$pro_id] : 0);

      $pro_red_tag = "";
      if(($pro_reduction < $pro_price) && ($pro_reduction > 0))
        $pro_red_tag = "<span class='price'> $pro_reduction TND</span>
        <span class='reduce'>$pro_price TND</span>
        <a class='remise_price'> ".$row["remise"]." % remise </a>";
      else 
        $pro_red_tag = "<span class='price' style='font-size: 14px'> $pro_reduction TND par unité </span>";

      echo "
      <div class='prod_box'>
        <div class='center_prod_box'>
          <div class='product_title'>
            <a href='/toolshop/product.php?p=$pro_id'>
              Batterie de démarrage ".$row["amperage"]."Ah ".$row["puissance_au_dem"]."A
            </a>
            <p class = 'product_ref'>
            ".$row["marque"]." - ".$row["REF"]."
            </p>
          </div>
            <div class='product_img'>
            <a href='/toolshop/product.php?p=$pro_id'> 
              <img class = 'prod_i' src='$pro_img' alt='' border='0' />
            </a>
          </div>
          <div class='prod_price'>
            $pro_red_tag
          </div>
        </div>
        <div class='prod_details_tab'>
          <a href='add-to-cart.php?pid=$pro_id&pq=1' target='target' class='prod_buy' id = 'buy_butt' name = '".($stockStorage)."'>
            <i class='fa fa-shopping-cart' aria-hidden='true'></i>
            <p class = 'next_to_card'>Ajouter au panier</p>
          </a>
        </div>
      </div>
      ";
    };
  }
?>
</div>

<?php

  //$page_url = ; // get link from input hidden

  if($page > 1) {
    echo "
    <a id = 'prev_page' class='contentnp' style = 'align-self: flex-start;'>
      &laquo; Previous
    </a>
  ";
  }

  $rown = mysqli_num_rows($run_query);
  if($rown > 12) {
    echo "
      <a id = 'next_page' class='contentnp' style = 'align-self: flex-end;'>
        Next &raquo;
      </a>
    ";
  } else if ($rown == 0 && $page > 1) { // when things go wrong smh
    echo "<script> location.href = 'index.php'; </script>" ;
  } elseif ($rown <= 8) {
    echo "<script> heightResizer(); </script>";
  }
?>

<script>
  var inputList = document.querySelectorAll("[id='buy_butt']");
  
  function heightResizer() {
    var mainContent = document.querySelector("[class='center_content']");
    mainContent.style = "height: 615px;";
  }

  function checkLimiter(b) {
    if (parseInt(b.name) <= 0) {
      b.style = "background-color: rgb(186, 195, 240); color: #fff; border-color:#f0f0f0;cursor:default;";
      b.childNodes[1].className = "";
      b.childNodes[3].innerHTML = "Stock pas disponible";
      b.href = "javascript:void(0)";
    }
  }

  inputList.forEach(b => {
    checkLimiter(b);

    b.addEventListener("click", function() {
      if (b.name > 0) {
        toggleModal("Produit ajouté au panier avec succès.");
        b.name -= 1;
        if (b.name == 0) { // Why? Actually, the crappy href is changed before it even proceeds thus a bug that I spent a few hours trying to find.
          var xhttp = new XMLHttpRequest();
          xhttp.open("GET", b.href);
          xhttp.send();
        }
        checkLimiter(b);
        
      }
    });
  });

  var pURL = document.getElementById("products_link").value;

  function removeFilter(parameter) {
      //prefer to use l.search if you have a location/link object
      var urlparts = pURL.split('?');
      if (urlparts.length >= 2) {

          var prefix = encodeURIComponent(parameter) + '=';
          var pars = urlparts[1].split(/[&;]/g);

          //reverse iteration as may be destructive
          for (var i = pars.length; i-- > 0;) {    
              //idiom for string.startsWith
              if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                  pars.splice(i, 1);
              }
          }

          pURL = urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
      }
  }
  
  $("#next_page").click(function() { 
    removeFilter("page");
    var nParams = pURL.split('?').length;
    if (nParams == 1)
        pURL += "?";
    else if (nParams > 1)
        pURL += "&";
    pURL += "page="+(pageNumber+1);

    document.getElementById("products_link").value = pURL;
    reloadPage();
  });

  $("#prev_page").click(function() { 
    removeFilter("page");
    if (pageNumber-1 > 1) 
      pURL += "page="+(pageNumber-1);

    document.getElementById("products_link").value = pURL;
    reloadPage();
  });

  function reloadPage() {
    $(".content_np").fadeOut(300, function() {;
        $(".content_np").load(pURL);
        $(".content_np").fadeIn();
    });
  }
</script> 

