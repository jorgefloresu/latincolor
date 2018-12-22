<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getpanther extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $this->load->library('panthermedia_api');
        }

        function show($info)
        {
            return '<pre>'.print_r($info, true).'</pre>';
        }

        function index()
        {
            $dot = $this->input->get('f');
            if (!$dot)
                $res = '';
            $media = $this->input->get('media');
            $id_article = $this->input->get('id_article');

            $output = '<div style="margin-left:190px">';

            if ($dot == 'search'){
	            $params = array(
	                PantherParams::SEARCH_QUERY => 'family tv ipad',
	                PantherParams::SEARCH_PAGE => 0,
	                PantherParams::SEARCH_LIMIT => 5 
	                );
			   $res = $this->panthermedia_api->search($params, 'title,author_username,preview', 'license:commercial;sort:rel;');
               //echo $this->show($res);
			   $output .= $this->show($res);
		      
               if (!empty($res)) {
    			   if($res['items']['items']>1){
    			      foreach($res['items']['media'] as $value){
    			         if(isset($value['thumb'])){
                            //echo $res['items']['media']['id'] . "<br/>";
    			            $output .= $value['title'].'<br><img src="'.$value['thumb'].'"><br>Author: '.$value['author-username'].'<br><br>';
    			         }
    			      }
    			      
    			   }
    			   elseif($res['items']['items']==1){
                            $output .= $res['items']['media']['title'].'<br><img src="'.$res['items']['media']['thumb'].'"><br>Author: '.$res['items']['media']['author-username'].'<br><br>';
                   }
                   else
                        $output .= $this->show('no result<br><br>');
                }
                else
                    $output .= $this->show('no result<br><br>');
            }

            if ($dot == 'mdata'){
            	$res = $this->panthermedia_api->get_media_info($media, true);
                $output .= $this->show(date_format(date_create($res['metadata']['date']),'d-m-Y'));
            	$output .= $this->show($res);
                $res = $this->panthermedia_api->download_image_preview($media);
                $output .= $this->show($res);

				$output .= '<a href="data:'.$res['media']['mimetype'].';charset=utf-8;base64,'.$res['media']['base64'].'" download="image.jpg">Download</a>';
            }

            if ($dot == 'getmedia'){
            	$res = $this->panthermedia_api->download_image($media, $id_article);
            	$output .= $this->show($res);
            	//$data = 'data:image/jpeg;charset=utf-8;base64,'.$res['media']['base64'];
       			//$output .= base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

				$output .= '<a href="data:application/octet-stream;charset=utf-8;base64,'.$res['media']['base64'].'" download="img_download.jpg">Download</a>';
            }

            $output .= '</div>';

            $output .= '<div style="z-index: 1;position: absolute;left: 0;top: 0;">';
            $attr = array('method'=>'get');
            $output .= form_open(__CLASS__, $attr);
            $output .= form_radio('f', 'search', true) .'SearchResult <br/>';
            $output .= form_fieldset(form_radio('f', 'mdata') .'Media details');
            $output .= 'Media: '.'<br/>';
            $output .= form_input('media', $media) .'<br/>';
            $output .= 'Article: '.'<br/>';
            $output .= form_input('id_article', $id_article) .'<br/>';
            $output .= form_fieldset_close();
            $output .= form_fieldset('Download & Purchases');
            $output .= form_radio('f', 'getmedia') .'Download Media <br/>';
            $output .= form_submit('submit', 'submit');
            $output .= form_fieldset_close();
            $output .= form_close();
            $output .= "</div>";
            echo $output;

        }
}
