<?php
// parameters
$hubVerifyToken = 'XXXXXXXXXToken';
$accessToken = "XXXXXXXXXYourPageAccessToken";
// check token at setup
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}

// handle bot's anwser
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
//$answer = "I don't understand. Ask me 'Amazon then search keyword eg: Amazon One Plus 3'.";
if($messageText == "hi") {
    $answer = "Hello, Welcome to Amazon product search. eg: Search Amazon One plus 3";
    $response = [
    
        'recipient' => [ 'id' => $senderId ],
        'message' => [ 'text' => $answer ]
    ];
    $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);
}
elseif(strpos($messageText, "Amazon")==false){
    $answer = "I don't understand. Ask me 'Search Amazon keyword eg: Search Amazon One Plus 3'.";
    $response = [
    
        'recipient' => [ 'id' => $senderId ],
        'message' => [ 'text' => $answer ]
    ];
    $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);
}
elseif(strpos($messageText, "Amazon")>=0){
    $answer = "Got Amazon";
    $answer = "We are searching for the products please be patient.";
    $response = [
    
        'recipient' => [ 'id' => $senderId ],
        'message' => [ 'text' => $answer ]
    ];
    $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);
    //Change the path of the parser.py
    $command = 'python /home/ubuntu/workspace/parser.py "'.$messageText.'"';
    $output = exec($command);
    $output = json_decode($output, true);
    foreach ($output as $value) {
        $response = '{"recipient":{"id":"'.$senderId;
        $response = $response.'"},"message":{"attachment":{"type":"template","payload":{"template_type":"generic",';
        $response = $response.'"elements":[{"title":"'.str_replace('"','\"',$value['prodName']);
        $response = $response.'","image_url":"'.str_replace('"','\"',$value['imageUrl']);
        $response = $response.'","buttons":[{"type":"web_url","url":"'.str_replace('"','\"',$value['url']);
        $response = $response.'","title":"Open Web Url"}]}]}}}}';
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);
    }
}
?>