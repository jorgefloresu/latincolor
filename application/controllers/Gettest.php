<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require (APPPATH. 'vendor/autoload.php');

class Gettest extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Ingimages_api');
    }

	public function index()
	{
    $this->load->library('mongo_db', array('activate'=>'default'), 'mongo_db');
    $res = $this->mongo_db->get('membership');

    // $res = new MongoDB\Driver\Manager("mongodb://jorgeflores:jafu6921@ds060009.mlab.com:60009/ci_test");
    echo '<pre>';
    print_r($res);
/*
    $user = "jorgeflores";
    $pwd = 'jafu6921';
    //$mongo = new MongoDB\Client("mongodb://${user}:${pwd}@ds060009.mlab.com:60009/?authSource=ci_test");
    //var_dump($mongo);

    $mongo = new MongoDB\Driver\Manager("mongodb://${user}:${pwd}@ds060009.mlab.com:60009/ci_test");
    $rp = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
    $server = $mongo->selectServer($rp);

    //var_dump($server->getInfo());

    $filter = ['first_name' => 'Smart'];
    $options = [
        'maxTimeMS' => 1000,
    ];

    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongo->executeQuery('ci_test.membership', $query);

    foreach ($cursor as $document) {
         print_r($document);
    }
*/
    //print_r($mongo);
    //$collection = $mongo->ci_test->membership;
    //var_dump($collection);
    //$dbs = $mongo->listDatabases();
    //print_r($dbs);
    //$result = $collection->findOne();

    //print_r($result);
		/*
		$params = array(
			IngimagesParams::SEARCH_QUERY => 'business',
			IngimagesParams::SEARCH_PAGE => 1,
			IngimagesParams::SEARCH_LIMIT => 12
			);
		$res = $this->ingimages_api->search($params);
		*/
		// $res = $this->ingimages_api->getMediaData('03C06184');
		// $data = array(
		// 		'colortype' => $res->colorType,
		// 		'orientation' => $res->orientation,
		// 		'keywords' => $res->keywords
    //
		// 	);
		//$res = $this->ingimages_api->getMediaPreview('03C06184.jpg',450);
		// print_r($res);
		// echo "<br/><br/>";
    //
		// print_r( $res->availableAssetTypes);
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
