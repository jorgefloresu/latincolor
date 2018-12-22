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
    function material_input($size, $id, $name, $label)
    {
      $col = "class='input-field col {$size}'";
      $attrib = "id='{$id}' name='{$name}' type='text'";
      $input = "<div {$col}><input {$attrib}><label for='{$id}'>{$label}</label></div>";
      return $input;
    }
}

if(!function_exists('material_password'))
{
    function material_password($size, $id, $name, $label)
    {
      $col = "class='input-field col {$size}'";
      $attrib = "id='{$id}' name='{$name}' type='password'";
      $passwd = "<div {$col}><input {$attrib} required='' aria-required='true'><label for='{$id}'>{$label}</label></div>";
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
      $colors = ['gradient-45deg-red-pink','gradient-45deg-amber-amber','gradient-45deg-green-teal'];
      $html = '';
      $i = 0;
      foreach ($cards as $key => $item) {
        $col = "class='col {$size}'";
        $card = "<div class='plan-pref card'>";
        $card_content = "<div class='card-content center'>";
        $header_icon = "<h4><i class='small material-icons white-text {$colors[$i]} gradient-shadow background-round'>{$item[0]}</i></h4>";
        $header_title="<h5 class='planes-card'>{$key}</h5>";
        $legend1 = "<p class='light grey-text'>{$item[1]}</p>";
        $legend2 = "<p class='legend2'>{$item[2]}</p>";
        $ddtrigger = "<a class='dropdown-trigger btn-flat' href='#' data-activates='{$item[3]}-list'>{$item[4]}";
        $ddtrigger.= "<i class='material-icons medium right'>arrow_drop_down</i></a>";
        // $ddtrigger.= "<span class='selected-{$item[3]} blue white-text'></span>";
        $ddstruct = "<ul id='{$item[3]}-list' class='dropdown-{$item[3]} dropdown-content'></ul>";
        $card_action = "<div class='card-action'>";
        $chip = "<div class='chip-plan chip-{$item[3]} center {$colors[$i]}'></div>";
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
      return $html;
    }
}

if(!function_exists('material_collection'))
{
    function material_collection($items)
    {
      $collection = "<ul class='collection'>";
      foreach ($items as $key => $value) {
        $collection .= "<li class='collection-item'>";
        $collection .= "<i class='tiny material-icons green-text'>check</i>{$value}</li>";
      }
      $collection .= "</ul>";
      return $collection;

    }
}

if(!function_exists('material_button'))
{
    function material_button($style, $text)
    {
      $button = "<button class='waves-effect waves-light {$style}'>{$text}</button>";
      return $button;
    }
}

if(!function_exists('material_paquete_card'))
{
    function material_paquete_card($size, $color, $title, $sign, $valor, $desc, $items)
    {
      $col = "class='col {$size}'";
      $card = "<div class='card hoverable'>";
      $card_image = "<div class='card-image {$color} waves-effect'>";
      $card_title = "<div class='card-title'>{$title}</div>";
      $price = "<div class='price'>";
      $currency = "<sup class='currency'>{$sign}</sup>{$valor}";
      $millar = "<sup class='millar'>mil</sub></div>";
      $desc = "<div class='price-desc'>{$desc}</div></div>";
      $card_content = "<div class='card-content'>";
      $card_action = "<div class='card-action center-align'>";

      $html = "<article {$col}>"
                .$card
                  .$card_image
                    .$card_title
                    .$price
                      .$currency
                      .$millar
                    .$desc
                  .$card_content
                    .material_collection($items)
                  ."</div>"
                  .$card_action
                    .material_button("btn blue accent-2", "Select pack")
                ."</div></div>"
              ."</article>";

      return $html;
    }
}

if(!function_exists('material_servicio_card'))
{
    function material_servicio_card($style, $image, $title, $title_size, $text, $text_link)
    {
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
                  ."<span class='card-title grey-text text-darken-4'>"
                  ."<i class='material-icons right circle'>arrow_back</i>{$title}</span>"
                  ."<p>{$text}</p>"
                  ."<a class='waves-effect btn-flat square'>{$text_link}</a>"
                  ."</div>"
                  ."</div>";

      return $serv_card;
    }
}
