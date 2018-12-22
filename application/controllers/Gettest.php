<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gettest extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ingimages_api');
    }

	public function index()
	{
		/*
		$params = array(
			IngimagesParams::SEARCH_QUERY => 'business',
			IngimagesParams::SEARCH_PAGE => 1,
			IngimagesParams::SEARCH_LIMIT => 12
			);
		$res = $this->ingimages_api->search($params);
		*/
		$res = $this->ingimages_api->getMediaData('03C06184');
		$data = array(
				'colortype' => $res->colorType,
				'orientation' => $res->orientation,
				'keywords' => $res->keywords

			);
		//$res = $this->ingimages_api->getMediaPreview('03C06184.jpg',450);
		print_r($res);
		echo "<br/><br/>";

		print_r( $res->availableAssetTypes);
		//echo "<img src='$res' />";
		//echo $res->image['code'];
		//echo $res->image->imgcaption;
		/*
		foreach ($res as $images) {
					echo $images['code'] . "<br/>";
					echo $images->imgcaption . "<br/>";
				}		
		/*
		$obj = simplexml_load_string($res, NULL, LIBXML_NOCDATA);
		echo htmlentities($res);
		echo "<br/><br/>";
		print_r ($obj);
		echo $obj->image['code'];
		echo $obj->image->imgcaption;
		echo "<br/><br/>";
		$json = json_encode($obj);      
		print_r($json);
		*/
	}

}

?>