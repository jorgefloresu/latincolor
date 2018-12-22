<?php foreach ($data['result'] as $provider => $content) {                   
    foreach ($content as $item => $data) {
        if (is_array($data)) {
            foreach ($data as $key => $images) {
                if( !empty($images['thumburl']) ) {
                    $jsonArray[] = [
                        'url' => $images['thumburl'],
                        'width' => $images['width'],
                        'height' => $images['height'] 
                        ];
                }
            }
        }
    }
}
//return json_encode($jsonArray);
echo "Hello";
?>