<?php

use Cloudinary\Api;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require (APPPATH. 'vendor/autoload.php');
require (APPPATH. 'vendor/cloudinary/cloudinary_php/samples/PhotoAlbum/lib/rb.php');

class Cloud extends CI_Controller {

        public $api;

        public function __construct()
        {
                parent::__construct();
                $this->load->helper(array('form', 'url'));
                \Cloudinary::config(array( 
                        "cloud_name" => "latincolor", 
                        "api_key" => "238135328693765", 
                        "api_secret" => "J2vbxQtFa6ckrb02oMfQunoEg9k", 
                        "secure" => true
                      ));
                R::setup('mysql:host=127.0.0.1;port=8889;dbname=photo_album', 'root', 'root');
                $this->api = new Api();
       
        }

        function array_to_table($array)
        {
                $saved_error_reporting = error_reporting(0);
                $resp = '<table class="info">';
                foreach ($array as $key => $value) {
                        if ($key != 'class') {
                        if ($key == 'url' || $key == 'secure_url') {
                                $display_value = '"' . $value . '"';
                        } else {
                                $display_value = json_encode($value);
                        }
                        $resp .= "<tr><td>{$key}:</td><td>{$display_value}</td></tr>";
                        }
                }
                $resp .= '</table>';
                error_reporting($saved_error_reporting);
                return $resp;
        }

        public function index()
        {

              //$data['image'] = cloudinary_url("sample.jpg", array("width" => 235, "height" => 158, "crop" => "fill"));
              //$data['thumbs_params'] = array('format' => 'jpg', 'height' => 150, 'width' => 150, 'class' => 'thumbnail inline');

              $res = $this->api->resources(array(
                      'type' =>'upload',
                      'prefix' => ''
                ));
              $folders = $this->api->root_folders();
              foreach ($folders['folders'] as $key => $f) {
                $data['folders'][$key] = $f;
              }
              foreach ($res['resources'] as $key => $r) {
                if (! strpos($r['public_id'],'/')) {
                        $data['res'][$key] = ['id' => $r['public_id'], 'url' => $r['url'], 'folder'=>'', 'image' => cloudinary_url($r['public_id'], 
                        array("width" => 470, "height" => 316, "crop" => "fill", "format" => "jpg", "gravity" => "face", "quality" => "auto"))];
                }
                $photos = \R::find('photo', 'public_id = ?', [$r['public_id']]);
                if (!count($photos)){
                        $photo = \R::dispense('photo');
                        $photo->public_id = $r['public_id'];
                        \R::store($photo);
                }
                
              }
              $data['root'] = true;
              //header('Content-Type: application/json');
              //print_r($res);
              $this->load->view('cloudinary_view', $data);
        }

        public function open_image()
        {
              $img = $this->input->get('img');
              $data = $this->api->resource($img);
              $data['folder'] = $this->input->get('folder');
              //header('Content-Type: application/json');
              //print_r ($data);
              $this->load->view('cloudinary_image', $data);
        }

        public function open_folder($folder='')
        {
                $res = $this->api->resources(array(
                        'type' =>'upload',
                        'prefix' => ''
                ));
                $folders = $this->api->subfolders($folder);
                foreach ($folders['folders'] as $key => $f) {
                        $data['folders'][$key] = $f;
                }
                foreach ($res['resources'] as $key => $r) {
                        if (strstr($r['public_id'], $folder.'/')) {
                                $data['res'][$key] = ['id' => $r['public_id'], 'url' => $r['url'], 'folder'=>$folder, 'image' => cloudinary_url($r['public_id'], 
                                array("width" => 470, "height" => 316, "crop" => "fill", "format" => "jpg", "gravity" => "face", "quality" => "auto"))];
                        }
                        $photos = \R::find('photo', 'public_id = ?', [$r['public_id']]);
                        if (!count($photos)){
                                $photo = \R::dispense('photo');
                                $photo->public_id = $r['public_id'];
                                \R::store($photo);
                        }
                }
                $data['root'] = $folder != '' ? false : true;

                $this->load->view('cloudinary_view', $data);
        
        }

        public function do_transform($img)
        {
                $texto = $this->input->post('texto');
                $ubicacion = $this->input->post('ubicacion');
                $font_family = $this->input->post('fontfamily');
                $font_size = $this->input->post('fontsize');
                $font_weight = $this->input->post('fontweight');
                $letter_spacing = $this->input->post('letterspacing');
                $text_decoration = $this->input->post('textdecoration');
                $xmargin = $this->input->post('xmargin');
                $ymargin = $this->input->post('ymargin');
                $width = $this->input->post('width');
                $height = $this->input->post('height');
                $color = $this->input->post('color');
                $rostro = $this->input->post('rostro');
                $bordes = $this->input->post('bordes');
                $crop = $this->input->post('crop');
                $format = $this->input->post('format');

                $overlay = array(
                        "font_family"=>$font_family, 
                        "font_size"=>$font_size,
                        "font_weight"=>$font_weight, 
                        "text_decoration"=>$text_decoration, 
                        "letter_spacing"=>$letter_spacing,
                        "text"=>$texto
                );
                
                $transopt2 = array(
                        "gravity"=>$ubicacion,
                        "overlay" => $overlay,
                        "color"=>$color
                );

                $cloudinary_options = array(
                        "transformation"=> array(
                                array(
                                        "width"=>$width,
                                        "height"=>$height,
                                        "y" => $ymargin,
                                        "x" => $xmargin,
                                        "crop"=>$crop
                                )                                
                        ));

                if ($rostro) {
                        $cloudinary_options["transformation"][0]["gravity"] = "face";
                        $cloudinary_options["transformation"][0]["crop"] = "thumb";
                }
                if ($bordes) {
                        $cloudinary_options["transformation"][0]["radius"] = $bordes;
                }

                if ($texto) {
                        $cloudinary_options["transformation"][] = $transopt2;
                }
                /* if ($ubicacion =='face') {
                        $cloudinary_options = array(
                                "transformation"=> array(
                                        array(
                                                "width"=>$width,
                                                "height"=>$height,
                                                "gravity"=>"face", 
                                                "crop"=>"thumb"
                                        ),
                                        $transopt2
                                ));
                        
                } else {
                        $cloudinary_options = array(
                                "transformation" => array(
                                        array(
                                                "width"=>$width,
                                                "height"=>$height, 
                                                "crop"=>"scale"
                                        ),
                                        $transopt2
                                ));
                } */

                return cloudinary_url($img, $cloudinary_options);   
        }

        public function transformar()
        {
                $img = $this->input->post('transform');

                $url = $this->do_transform($img);   
                echo json_encode(array('file'=>$url));
        }

        public function descargar()
        {
                $filename = $this->input->post('transform');
                $isdir = strpos($filename,'/');
                $realname = $isdir ? substr($filename, $isdir, strlen($filename)) : $filename;
                $format = $this->input->post('format');
                $outFileName = FCPATH . 'zip/' . $realname . '.' . $format;

                if ($this->input->post('res')=='imodif') {
                        $url = $this->input->post('img');
                        $url .= '.' . $format;
                } else {
                        $url = cloudinary_url($this->input->post('transform'));
                }

                if(is_file($url)) {
                    copy($url, $outFileName); 
                } else {
                    $options = array(
                      CURLOPT_FILE    => fopen($outFileName, 'w'),
                      CURLOPT_TIMEOUT =>  28800, // set this to 8 hours so we dont timeout on big files
                      CURLOPT_URL     => $url
                    );
          
                    $ch = curl_init();
                    curl_setopt_array($ch, $options);
                    curl_exec($ch);
                    curl_close($ch);
                }
                echo json_encode(array('file'=>$url, 'orig'=>$outFileName, 'realname'=>$realname.'.'.$format));

        }
        
}
