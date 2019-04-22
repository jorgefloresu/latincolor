<?

if(!function_exists('material_select'))
{
    function material_select($size, $id, $name, $label)
    {
      $col = "class='input-field col {$size}'";
      $attrib = "id='{$id}' name='{$name}'";
      $select = "<div {$col}><select {$attrib}></select><label>{$label}</label></div>";
      return $select;
    }
}

if(!function_exists('material_input'))
{
    function material_input($size, $id, $name, $label, $required=FALSE)
    {
      $col = "class='input-field col {$size}'";
      $attrib = "id='{$id}' name='{$name}' type='text'";
      $text_required = "class='validate' required oninvalid=\"this.setCustomValidity('El dato es requerido!')\" onkeyup=\"setCustomValidity('')\"";
      $input = "<div {$col}><input {$attrib} {$text_required}><label for='{$id}'>{$label}</label></div>";
      return $input;
    }
}

if(!function_exists('material_password'))
{
    function material_password($size, $id, $name, $label)
    {
      $col = "class='input-field col {$size}'";
      $attrib = "id='{$id}' name='{$name}' type='password'";
      $passwd = "<div {$col}><input {$attrib} ><label for='{$id}'>{$label}</label></div>";
      return $passwd;
    }
}

if(!function_exists('material_submit'))
{
    function material_submit($size, $name='action', $label='Enviar', $icon='send', $message='Datos guardados')
    {
      $col = "class='input-field col {$size}'";
      $attrib = "class='btn waves-effect waves-light' name='{$name}' type='submit'";
      $submit = "<div class='row'><div {$col}><button {$attrib}>{$label}<i class='material-icons right'>{$icon}</i></button></div></div>";
      $submit .= "<div id='message'><p class='error_msg medium-small'>$message</p></div>";

      return $submit;
    }
}

if(!function_exists('material_tabs'))
{
    function material_tabs($size, $labels)
    {
      $size = "class='tab col {$size}'";
      $tabs = "<ul class='tabs light-blue-text'>";
      foreach ($labels as $key => $value) {
        $tabs .= "<li {$size}><a href='#{$key}' class='light-blue-text'>{$value}</a></li></li>";
      }
      $tabs .= "</ul>";
      return $tabs;
    }
}

if(!function_exists('material_plan_card'))
{
    function material_plan_card($size, $cards) //$icon, $title, $activates, $label, $span, $id, $ddown, $chip)
    {
      //$colors = ['gradient-45deg-red-pink','gradient-45deg-amber-amber','gradient-45deg-green-teal'];
      $colors = ['blue','orange','indigo','green'];
      $html = '';
      $i = 0;
      foreach ($cards as $key => $item) {
        $col = "class='col {$size}'";
        $card = "<div class='plan-pref card no-shadow' style='background-color:transparent'>";
        $card_content = "<div class='card-content center'>";
        //$header_icon = "<h4><i class='small material-icons white-text {$colors[$i]} gradient-shadow background-round'>{$item[0]}</i></h4>";
        $header_icon = "<h4 style='display:inline'><i class='small material-icons white-text {$colors[$i]} background-round'>{$item[0]}</i></h4>";
        $header_title="";//"<h5 class='planes-card'>{$key}</h5>";
        $legend1 = "";//"<p class='light grey-text'>{$item[1]}</p>";
        $legend2 = "";//"<p class='legend2'>{$item[2]}</p>";
        $ddtrigger = "<a class='dropdown-trigger btn-flat' href='#' data-activates='{$item[3]}-list'>{$item[4]}";
        $ddtrigger.= "<i class='material-icons medium right'>arrow_drop_down</i></a>";
        // $ddtrigger.= "<span class='selected-{$item[3]} blue white-text'></span>";
        $ddstruct = "<ul id='{$item[3]}-list' class='dropdown-{$item[3]} dropdown-content' style='width:164px'></ul>";
        $card_action = "<div class='card-action' style='border:none'>";
        //$chip = "<div class='chip-plan chip-{$item[3]} center {$colors[$i]}'></div>";
        $chip = "<div class='chip-plan chip-{$item[3]} center'></div>";
        $html .= "<div {$col}>"
                    .$card
                      .$card_content
                        .$header_icon
                        .$header_title
                        .$legend1
                        .$legend2
                        .$ddtrigger.$ddstruct
                      ."</div>"
                      .$card_action
                        .$chip
                  ."</div></div></div>";
        $i++;
      }
      //$html .= "<table><thead><tr><th class='chip-plan chip-{$cards['FRECUENCIA'][3]}'></th></tr></thead><tbody><tr><td>Body</td></tr></tbody></table>";
      return $html;
    }
}

if(!function_exists('material_collection'))
{
    function material_collection($items, $plan=null)
    {
      $collection = '';
      if ($plan) {
        $logo_provider = base_url("img/".$plan->data->provider.".png");
        $collection .= "<div class='center' style='margin-top:15px'><img src='$logo_provider' height='25'/></div>";
      }
      $collection .= "<ul class='collection' style='border:none; padding-left:2.6em; list-style-image:url(\"".base_url('img/check_circle.png')."\")'>";
      foreach ($items as $key => $value) {
        $collection .= "<li class='collection-item' style='border-bottom:none; background-color:transparent'>";
        $collection .= "<span style='top:-5px; position:relative'>{$value}</span></li>";
      }
      $collection .= "</ul>";
      return $collection;

    }
}

if(!function_exists('material_button'))
{
    function material_button($style, $text='', $plan=null)
    {
      if ($plan) {
        $p = $plan->data;
        $logo_provider = base_url("img/$p->provider.png");
        $desc  = "Planes de $p->medio para descarga ".strtolower($p->frecuencia). " de $p->cantidad ";
        $desc .= ($p->medio=='Fotos'?'imágenes':'videos') . " durante $p->tiempo " . ($p->tiempo==1?'mes':'meses');
        $button = "<a data-id='$p->id' data-img='$p->id'"
                 ." data-price='$p->valor' data-iva='$plan->iva' data-thumb='$logo_provider'"
                 ." data-tco='$plan->tco' data-provider='$p->provider' data-desc='$desc'"
                 ." data-size='N/A' data-sizelbl='-' data-license='standard' data-trantype='compra_plan' data-idplan='$p->offerId'"
                 ." class='comprar-paquete-btn btn waves-effect waves-light blue'><i class='material-icons tiny'>add_shopping_cart</i></a>";
      } else {
        $button = "<a class='waves-effect waves-light {$style}'><i class='material-icons tiny'>add_shopping_cart</i>{$text}</a>";
      }
      return $button;
    }
}

if(!function_exists('material_paquete_card'))
{
    function material_paquete_card($size, $color, $title, $sign, $valor, $desc, $items, $plan)
    {
      $col = "class='col {$size}'";
      $card = "<div class='card hoverable'>";
      $card_image = "<div class='card-image waves-effect'>";
      $card_title = "<div class='card-title {$color}-text text-darken-4' style='background:none;font-weight:bold'>{$title}</div>";
      $price = "<div class='price'>";
      $currency = "<sup class='currency'>{$sign}</sup>{$valor}";
      $millar = "<sup class='millar'>mil</sub>";
      $desc = "</div><div class='price-desc' style='background:rgba(0,0,0,0.1)'>{$desc}</div></div>";
      $card_content = "<div class='card-content' style='height:330px'>";
      $card_action = "<div class='card-action center-align'>";

      $html = "<article {$col}>"
                .$card
                  .$card_image
                    .$card_title
                    .$price
                      .$currency
                      //.$millar
                    .$desc
                  .$card_content
                    .material_collection($items, $plan)
                  ."</div>"
                  .$card_action
                    .material_button("btn blue accent-2",'',$plan)
                ."</div></div>"
              ."</article>";

      return $html;
    }
}

if(!function_exists('material_servicio_card'))
{
    function material_servicio_card($style, $image, $title, $title_size, $text, $text_link)
    {
      $mi_consultor = base_url('main/consultor');
      $serv_card = "<div class='servicios card {$style}'>"
                  ."<div class='card-image waves-effect waves-block waves-light'>"
                  ."<img class='activator' src='".base_url("{$image}")."'>"
                  ."</div>"
                  ."<div class='card-content'>"
                  ."<span class='card-title activator white-text ".$title_size."'>{$title}"
                  ."<i class='small material-icons right activator circle'>arrow_forward</i>"
                  ."</span>"
                  ."</div>"
                  ."<div class='card-reveal'>"
                  ."<span class='card-title {$title_size} grey-text text-darken-4'>"
                  ."<i class='material-icons right circle'>arrow_back</i>{$title}</span>"
                  ."<p class='{$title_size}'>{$text}</p>";

      if ($text_link) {
        $serv_card .= "<a href='{$mi_consultor}' class='waves-effect btn-flat square'>{$text_link}</a>";
      } else {
        $wapp = base_url('img/whatsapp-30.png');
        $serv_card .= 'Necesitas un servicio específico?, contáctanos en <a href="https://api.whatsapp.com/send?l=es&phone=573142958463&text=Buen%20dia,%20tengo%20una%20consulta"'
                    . 'target="_blank" class="phone" style="padding-left: 30px;">'
                    . "<img src='{$wapp}' style='margin: -5px -30px;position: absolute;'>"
                    .' + (57) 314 295 8463</a>';
      }

      $serv_card .="</div>"
                  ."</div>";

      return $serv_card;
    }
}
