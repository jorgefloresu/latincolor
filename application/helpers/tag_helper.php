<?
//Dynamically add Javascript files to header page
if(!function_exists('add_js')){
    function add_js($file='')
    {
        $str = '';
        $ci = &get_instance();
        $ci->config->load('tags');
        $header_js  = $ci->config->item('header_js');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
              switch ($item) {
                case 'api':
                  //array_splice($header_js, 9, 0, 'js/'.$item.'.functions.js');
                  $header_js[] = 'js/'.$item.'.functions.js';
                  $header_js[] = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js';
                  $header_js[] = 'js/imagesloaded.pkgd.min.js';
                  $header_js[] = 'js/jquery.justifiedGallery.min.js';
                  $header_js[] = 'js/magnific-popup.min.js';
                  break;
                case 'user':
                  $header_js[] = 'js/'.$item.'.functions.js';
                  $header_js[] = 'js/perfect-scrollbar.min.js';
                  break;
                default:
                  $header_js[] = 'js/'.$item.'.functions.js';
              }
            }
            $ci->config->set_item('header_js',$header_js);
        }else{
            $str = $file;
            $header_js[] = 'js/'.$str.'.functions.js';
            $ci->config->set_item('header_js',$header_js);
        }
    }
}

//Dynamically add CSS files to header page
if(!function_exists('add_css')){
    function add_css($file='')
    {
        $str = '';
        $ci = &get_instance();
        $ci->config->load('tags');
        $header_css = $ci->config->item('header_css');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_css[] = 'css/'.$item.'.css';
            }
            $ci->config->set_item('header_css',$header_css);
        }else{
            $str = $file;
            $header_css[] = 'css/'.$str.'.css';
            $ci->config->set_item('header_css',$header_css);
        }
    }
}

if(!function_exists('put_headers')){
    function put_headers($type='', $userData='')
    {
        $str = '';
        $ci = &get_instance();
        $ci->config->load('tags');
        $header_css = $ci->config->item('header_css');
        $header_js  = $ci->config->item('header_js');

        if ($type=='css' || $type=='')
          foreach($header_css AS $item){
              $media = (substr($item,0,15)=='materialize/css' || substr($item,0,14)=='css/home-style'
                        ? 'media="screen,projection"' : '');
              $str .= '<link rel="stylesheet" href="'.base_url().$item.'" type="text/css" '.$media.'/>'."\n";
          }

        if ($type=='js' || $type=='') {
          $str .= "<script type='text/javascript'>\n";

          if ( $userData !== '')
              $str .= "var userData = JSON.parse('".$userData."');\n";
          else
              $str .= "var userData = JSON.parse('{}');\n";
          $str .= "</script>\n";
          foreach($header_js AS $item){
              $base_url = (substr($item,0,5)=='https' ? '' : base_url());
              $str .= '<script type="text/javascript" src="'.$base_url.$item.'"></script>'."\n";
          }
          // $str .= "<script type='text/javascript'>\n";
          // $str .= "$('body').data(userData);\n";
          // $str .= "</script>\n";
        }

        return $str;
    }
}

if(!function_exists('sumar_valores')){
    function sumar_valores($array=[],$col_name='')
    {
      return number_format(array_sum(array_column($array,$col_name)),2);
    }
}

if(!function_exists('build_data_table')){
    function build_data_table($detalles, $attrib)
    {
      $table = "<table {$attrib}><thead><tr>";
      if (! strpos($attrib, 'responsive')) {
        $table .= "<th></th>";
      }
      foreach ($detalles as $key => $columns) {
        if ($key>0) break;
        foreach ($columns as $column => $value) {
          $table .= "<th>{$column}</th>";
        }
      }
      $table .= "</tr></thead><tbody>";
      foreach ($detalles as $key => $detalle) {
        $table .= "<tr>";
        if (! strpos($attrib, 'responsive')) {
          $table .= "<td></td>";
        }
        foreach ($detalle as $key => $value) {
          if ($key == 'thumb')
            $table .= "<td class='detalle'><img src='{$value}' width='50px' height='auto'></td>";
          else
            $table .= "<td class='detalle'>{$value}</td>";
        }
        $table .= "</tr>";
      }
      $table .= "<tbody></table>";

      return $table;
    }
}

?>
