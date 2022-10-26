<?php
include "header.php";
?>
		<!-- /BREADCRUMB -->
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},900);
				});
			});
</script>
		<script>
    
    (function (global) {
	if(typeof (global) === "undefined")
	{
		throw new Error("window is undefined");
	}
    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";
		// making sure we have the fruit available for juice....
		// 50 milliseconds for just once do not cost much (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };	
	// Earlier we had setInerval here....
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };
    global.onload = function () {        
		noBackPlease();
		// disables backspace on page except on input fields and textarea..
		document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };		
    };
})(window);
</script>


<div id="main_content_product">
  

  <!-- end of left content -->
  	<div class="product_content">
		<?php 
			include 'db.php';
			$product_id = $_GET['p'];
			$product_quantity = 1;


			$product_query = "SELECT * FROM products WHERE REF = '$product_id'";

			$pro_alt = "";
			$run_query = mysqli_query($connection, $product_query);
			if(mysqli_num_rows($run_query) > 0) {
				$row = mysqli_fetch_array($run_query);
				$pro_id    = $row['REF'];
				$pro_max  = $row['stock'];
				$pro_price = $row['prix'];
				$pro_alt = explode(";", $row['alternatives']);
				$pro_img = explode(";", $row['img']);
				$pro_marque = $row['marque'];
				$pro_reduction = $pro_price+($row['prix']*$row['remise']/100);
				$pro_amp = $row["amperage"];
				$pro_puiss = $row["puissance_au_dem"];
				$stockStorage = $pro_max - (isset($_SESSION["trunk"][$pro_id]) ? $_SESSION["trunk"][$pro_id] : 0);

				$img_div = '';
				foreach($pro_img as $i =>$key) {
					$img_div .= "<img class = 'pdimage_con' src='$key' onclick='toggleIModal(\"$key\")' />";
				}
				
				if (isset($_SESSION["email"]) && $_SESSION["email"] == "admin@cipa.com") {
					echo "
					<br/>
					<div class = 'edit_p'><a href='edit_product.php?REF=$pro_id' class = 'edit_button'> <i class='fa fa-cog'></i> Modifier</a></div><br/><br/>";
				}

				echo "
					<br/>
					
					<h1 class = 'pdinfo_titre'>
						Batterie de démarrage ".$pro_amp."Ah ".$pro_puiss."A
					</h1>

					<div style='text-align: center;'>
						<img class = 'pdinfo_mark' src='images/marques/$pro_marque.png'	 />
					</div>
					<br/>

					<h2 class = 'pdinfo_ref'>REF : $pro_id</h2>

					<br/>
					<br/>


					<div class = 'pdimages_con'>
						$img_div
					</div>

					<div class='modal_image'>
						<div class='modal-content'>
							<span class='close-button'></span>
							<img class = 'pdimage_focus' src='' />
						</div>
					</div>

					<br/>

					<h2 class = 'price_'>À partir de <span class = 'price_tag'> $pro_price TND </span></h2>

					<div class = 'pdinfo_buttons_con'>
						<h3 id = 'stock_check' style = 'color:green;'> $pro_max articles en stock </h3>
						<input type='number' min='1' max='$stockStorage' value='1' class = 'pd_quantity' id = 'pd_quantity'>
						<a href='add-to-cart.php?pid=$pro_id' target='target' class = 'shopper_button' id = 'shopper_butt'>Ajouter au panier</a>
					</div>

					<br/>
					<br/>
					<hr/>
					<br/>
					<br/>

					<h2 class = 'pdinfo_titre'>
						CARACTÉRISTIQUES TECHNIQUES
					<h2/>
					<br/>

					<div class = 'pdinfo_con'>
						<ul class = 'pdtab_con'>
							<li class = 'odd'>
								<span class = 'rtitle'>Reference</span>
								<span>$pro_id</span>
							</li>
							<li class = 'even'>
								<span class = 'rtitle'>Type</span>
								<span>Batterie de démarrage</span>
							</li>
							<li class = 'odd'>
								<span class = 'rtitle'>Marque</span>
								<span>$pro_marque</span>
							</li>
							<li class = 'even'>
								<span class = 'rtitle'>Amperage</span>
								<span>".$row['amperage']." Ah</span>
							</li>
							<li class = 'odd'>
								<span class = 'rtitle'>Puissance au demarrage</span>
								<span>".$row['puissance_au_dem']." A</span>
							</li>
							<li class = 'even'>
								<span class = 'rtitle'>Polarité</span>
								<span>".$row['polarite']."</span>
							</li>
							<li class = 'odd'>
								<span class = 'rtitle'>Dimensions en mm (haut x larg x long)</span>
								<span>".$row['dimensions']."</span>
							</li>
							";

							if (!empty($row['type_de_bac'])) 
								echo "<li class = 'even'>
									<span class = 'rtitle'>Type au bac</span>
									<span>".$row['type_de_bac']."</span>
								</li>";
							echo "
						</ul>
					</div>
				"; 

				$pro_intensity = 1;

				if ($pro_puiss >= 450)
					$pro_intensity = 2;
				else if ($pro_puiss >= 600)
					$pro_intensity = 3;
				else if ($pro_puiss >= 750)
					$pro_intensity = 4;

					
				$pro_resistance = 1;

				if ($pro_amp > 40)
					$pro_resistance = 2;
				else if ($pro_amp > 60)
					$pro_resistance = 3;
				else if ($pro_amp > 80)
					$pro_resistance = 4;

				echo "
					<br/>
					<h2 class = 'pdinfo_titre'>
						INFORMATIONS TECHNIQUES
					<h2/>
					<br/>
					<div class = 'pdinfo_tech_con'>
						<div class = 'pdinfo_tech'>
							<span class = 'pdinfo_tech_t'> Intensité au démarrage </span>
							<img src = 'images/s$pro_intensity.png'/>
						</div>
						<div class = 'pdinfo_tech'>
							<span class = 'pdinfo_tech_t'> Résistance au froid </span>
							<img src = 'images/s$pro_resistance.png'/>
						</div>
					</div>";
				};
			?>

			<h2 id = "alter_titre" class = 'pdinfo_titre'>
				ALTERNATIVES
			</h2>

			<div id = 'altCarousel' class='carousel js-carousel' data-ride="carousel">
				<div class='carousel__container js-carousel-container'>
					<div class='carousel__list js-carousel-list'>
					
						<?php

							$strarg = "";
							foreach ($pro_alt as $alt) {
								if (!empty($strarg)) {
									$strarg .= " OR ";
								}
								$strarg .= "REF = '$alt'";
							}

							$alt_query = "SELECT * FROM products WHERE $strarg";
							$try_sql = mysqli_query($connection	, $alt_query);

							if(mysqli_num_rows($try_sql) > 0) {
								
								while($row = mysqli_fetch_array($try_sql)){
									$pro_id    = $row['REF'];
									$pro_img = explode(";", $row['img'])[0];
									$pro_amp = $row["amperage"];
									$pro_puiss = $row["puissance_au_dem"];
									
									echo "
									<div class='carousel__item js-carousel-item'>
										<div class='alt_box'>
											<div class='center_alt_box'>
											<div class='product_title'>
												<a href='/toolshop/product.php?p=$pro_id'>Batterie de démarrage ".$pro_amp."Ah ".$pro_puiss."A</a>
											</div>
											<div class='product_img'>
												<a href='/toolshop/product.php?p=$pro_id'> 
												<img class = 'prod_i' src='$pro_img' alt='' border='0' />
												</a>
											</div>
											</div>
										</div>
									</div>
								  ";
								}
							}
						?>
					</div>
					<button class='carousel__button--prev js-carousel-button' data-slide='prev'>
						<svg viewBox="0 0 24 24" focusable="false">
							<path d="M0 0h24v24H0z" fill="none"></path>
							<path d="M8.59,16.59L13.17,12L8.59,7.41L10,6l6,6l-6,6L8.59,16.59z"></path>
						</svg>
					</button>

					<button class='carousel__button--next js-carousel-button' data-slide='next'>
						<svg viewBox="0 0 24 24" focusable="false">
							<path d="M0 0h24v24H0z" fill="none"></path>
							<path d="M8.59,16.59L13.17,12L8.59,7.41L10,6l6,6l-6,6L8.59,16.59z"></path>
						</svg>
					</button>
				</div>
				<ol class='carousel-indicators'>

				</ol>

			</div>



			<br/>
			<br/>

			<script>

				var imgFocus = document.querySelector(".pdimage_focus");
				const imgModal = document.querySelector(".modal_image");
                const cButton = document.querySelectorAll(".close-button")[1];

                function toggleIModal(img = "") {
                    imgModal.classList.toggle("show-modal");
					if (img) {
						imgFocus.src = img;
					}
                }

                function windowOnClick(event) {
                    if (event.target === imgModal) {
                        toggleIModal();
                    }
                }

                cButton.addEventListener("click", toggleIModal);
                window.addEventListener("click", windowOnClick);

				$(".js-carousel").each(function(){
					var prevSlider = document.querySelector(".carousel__button--prev");
					var nextSlider = document.querySelector(".carousel__button--next");
					var carouselIndicators = document.querySelector(".carousel-indicators");

					var $carousel = $(this),
						$carouselContainer = $carousel.find(".js-carousel-container"),
						$carouselList = $carousel.find(".js-carousel-list"),
						$carouselItem = $carousel.find(".js-carousel-item"),
						$carouselButton = $carousel.find(".js-carousel-button"),
						
						initialization = function() {
							var $button = $(this),
								containerWidth = $carouselContainer.outerWidth(),
								listWidth = $carouselList.outerWidth(),
								carouselPages = Math.ceil(listWidth/containerWidth);

							prevSlider.style.visibility = "hidden";
							if ($carouselItem.length == 0) {
								var titre_alter = document.getElementById("alter_titre");
								titre_alter.style.visibility = "hidden";
								titre_alter.style.marginTop = "0px";
								document.getElementsByClassName("carousel__container")[0].style.height = "0px";
							} else {
								for (var i = 0; i < carouselPages; i++) {
									var tagLi = document.createElement("li");
									if (i == 0) {
										tagLi.setAttribute("class", "activeP");
									}
									tagLi.setAttribute("id", "carousel__ip");
									tagLi.setAttribute("name", i);
									
									carouselIndicators.appendChild(tagLi);
								}
							}

							if (carouselPages == 1) {
								nextSlider.style.visibility = "hidden";
							}
						},

						setItemWidth = function(){
							$carouselList.removeAttr("style");
							var curWidth = $($carouselItem[0]).outerWidth() * $carouselItem.length;
							$carouselList.css("width", curWidth);
						},

						slide = function(){
							
							var $button = $(this),
								dir = $button.data("slide"),
								curPos = parseInt($carouselList.css("left")) || 0,
								moveto = 0,
								containerWidth = $carouselContainer.outerWidth(),
								listWidth = $carouselList.outerWidth(),
								before = (curPos + containerWidth),
								carouselPages = Math.ceil(listWidth/containerWidth),
								after = listWidth + (curPos - containerWidth);

							if(dir=="next"){
								moveto = (after < containerWidth) ? curPos - after : curPos - containerWidth;
							} else {
								moveto = (before >= 0) ? 0 : curPos + containerWidth;
							}
					
							var carouselInd = document.querySelectorAll("#carousel__ip");
							for (var currPage = 0; currPage < carouselInd.length; currPage++) {
								var ind = carouselInd[currPage];
								if (ind.getAttribute("class") == "activeP") {
									ind.className = "";
									if(dir == "next" && (currPage+1 <= carouselPages)) {
										carouselInd[currPage+1].className = "activeP";
									} else if (dir == "prev" && (currPage-1 >= 0)) {
										carouselInd[currPage-1].className = "activeP";
									}
									break;
								}
							}
							
							if (moveto == 0) { 
								prevSlider.style.visibility = "hidden";
							} else {
								prevSlider.style.visibility = "visible";
							}
						
							if (containerWidth == ((curPos + containerWidth)*-1)) {
								nextSlider.style.visibility = "hidden";
							} else {
								nextSlider.style.visibility = "visible";
							};

							$carouselList.animate({
								left: moveto
							});
						};
					$(window).resize(function(){
						setItemWidth();
					});
					setItemWidth();
					initialization();
					$carouselButton.on("click", slide);
				});

				var b = document.getElementById('shopper_butt');
				var o_link = b.href;
				var q = document.getElementById('pd_quantity');
				var stock_check = document.getElementById('stock_check');

				function checkLimiter() {
					if (q.value > q.max)
						q.value = q.max;

					if (q.max <= 0) {
						stock_check.style = "color:red;"
						stock_check.innerHTML = "Pas de stock"
						q.min = 0;
						b.style = "background-color: rgb(186, 195, 240);color: #fff; border-color:#f0f0f0;cursor:default;";
              		 	 b.innerHTML = "Stock pas disponible";
						b.href = "javascript:void(0)";
					} else {
						stock_check.innerHTML = q.max+" articles en stock"
					}
				}
				checkLimiter();

				b.addEventListener("click", function() {
					if (parseInt(q.max) >= parseInt(q.value) && parseInt(q.max) > 0) {
						b.href = o_link +"&pq="+q.value;
						q.max -= q.value;
						if (q.max <= 0) {
							var xhttp = new XMLHttpRequest();
							xhttp.open("GET", b.href);
							xhttp.send();
						}
						toggleModal("Produit ajouté au panier avec succès.");
						checkLimiter();
					}
				});

			</script>
	<!-- end of center content -->
		</div>
	<!-- end of main content -->
	</div>
<!-- end of main_container -->
</body>
	<?php
		include "footer.php";
	?>
