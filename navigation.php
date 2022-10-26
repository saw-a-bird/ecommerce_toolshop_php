<div class="left_content">
    <label for="type_select" class="nav_box type_box">Type :</label>
    <select name="type_select" id="type_select" onchange="selectType(this)" style = "display:inline;">
        <option value="" selected>Tous</option>
        <option value="batteries">Batteries</option>
    </select>

    <div class="nav_box filter_box">Filters</div>
    <div class="filters_menu">
        <ul class="segment_menu">
          <div class="nav_box title_box">Fabricants :</div>
            <li class="odd">
                <input class="checkbox-input" id = "Bosch" value="marque" type="checkbox">
                <label class="checkbox-label" for="Bosch"> <a> Bosch </a> </label>
            </li>
            <li class="even">
                <input class="checkbox-input" id = "Varta" value="marque" type="checkbox">
                <label class="checkbox-label" for="Varta"> <a>Varta</a> </label>
            </li>
            <li class="odd">
                <input class="checkbox-input" id = "ASSAD" value="marque" type="checkbox">
                <label class="checkbox-label" for="ASSAD"> <a>Assad</a> </label>
            </li>
            <li class="even">
                <input class="checkbox-input" id = "Valeo" value="marque" type="checkbox">
                <label class="checkbox-label" for="Valeo"> <a>Valeo</a> </label>
            </li>
        </ul>

        <ul class="segment_menu">
            <div class="nav_box title_box">Amperage :</div>
            <li class="odd">
                <input class="checkbox-input" id = "70ah" value="amperage" type="checkbox">
                <label class="checkbox-label" for="70ah"> <a> 70 Ah </a> </label>
            </li>
            <li class="even">
                <input class="checkbox-input" id = "52ah" value="amperage" type="checkbox">
                <label class="checkbox-label" for="52ah"> <a> 52 Ah </a> </label>
            </li>
            <li class="odd">
                <input class="checkbox-input" id = "54ah" value="amperage" type="checkbox">
                <label class="checkbox-label" for="54ah"> <a> 54 Ah </a> </label>
            </li>
        </ul>

        <ul class="segment_menu">
            <div class="nav_box title_box">Dimensions :</div>
            <li class="even">
                <input class="checkbox-input" id = "225x175x260" value="dimensions" type="checkbox">
                <label class="checkbox-label" for="225x175x260"> <a> 225 x 175 x 260 </a> </label>
            </li>
            <li class="odd">
                <input class="checkbox-input" id = "175x207x190" value="dimensions" type="checkbox">
                <label class="checkbox-label" for="175x207x190"> <a> 175 X 207 X 190 </a> </label>
            </li>
        </ul>

        <ul class="segment_menu">
            <div class="nav_box title_box">Prix :</div>
            <li class="even">
                <input class="checkbox-input" id = "110ap" value="prix" type="checkbox">
                <label class="checkbox-label" for="110ap"> <a> À partir de 110 TND </a> </label>
            </li>
            <li class="odd">
                <input class="checkbox-input" id = "100ap" value="prix" type="checkbox">
                <label class="checkbox-label" for="100ap"> <a> À partir de 100 TND </a> </label>
            </li>
            <li class="even">
                <input class="checkbox-input " id = "30ap" value="prix" type="checkbox">
                <label class="checkbox-label" for="30ap"> <a> À partir de 30 TND </a> </label>
            </li>
        </ul>
    </div> 

    <input type = "hidden" id = "products_link" value = "/toolshop/products.php">

    <script>
        var pURL = document.getElementById("products_link");
        function reloadProducts() {
            document.getElementById("products_link").value = pURL;
            $(".content_np").fadeOut(500, function() {;
                $(".content_np").load(pURL);
                $(".content_np").fadeIn();
            });
        }

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

        function selectType(typeInput) {
            var value = typeInput.value;
            
            var nParams = pURL.split('?').length;
            if (nParams == 1)
                pURL += "?";
            else if (nParams > 1)
                pURL += "&";

            reloadProducts(pURL + "type="+value);
        }

        $('.checkbox-input').on('change', function(e) {
            var id = $(this).attr('id');
            var checked = $(this).is(':checked');
            var type = $(this).attr('value');

            if (type.localeCompare("amperage") == 0 || type.localeCompare("prix") == 0) {
                id = id.substr(0, id.length - 2);
            }

            removeFilter(type); // remove old
            if (checked) {
                var nParams = pURL.split('?').length;
                if (nParams == 1)
                    pURL += "?";
                else if (nParams > 1)
                    pURL += "&";
                pURL += type +"="+ id; // add anew
                $(this).parent().siblings().find('input:checkbox').prop('checked', false);
            }
            reloadProducts(pURL); // reload
        });

        
    </script>
</div>