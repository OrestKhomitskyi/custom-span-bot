<?php

require(__DIR__ . '/vendor/paralleldots/apis/autoload.php');

$api_key = "UMdMIPiyi0xlKbivrG5Eahx68gscgK4DoAkclkrAmlw";

set_api_key($api_key);



$response_array = json_decode(facial_emotion("./neutral.jpg"), TRUE);

$max = $response_array['facial_emotion'][0];
for($i = 1; $i < count($response_array); $i++) {
    if ($response_array[i]['score'] > $max['score'] ) {
        $max = $response_array[i];
    }
}

echo $max['tag'];