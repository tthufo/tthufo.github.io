<?php
    
$sandbox = $_POST['taskOption'];
    
$deviceToken = $_POST['registrationIDs'];
    
$passphrase = $_POST['password_ck'];

$message = $_POST['message'];

$ctype = $_POST['ctype'];
    
$cid = $_POST['cid'];
    
$ctx = stream_context_create();
    
stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
    
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

    if($sandbox == "0")
    {
        $fp = stream_socket_client(
                                   'ssl://gateway.sandbox.push.apple.com:2195', $err,
                                   $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
    }
    else
    {
        $fp = stream_socket_client(
                                   'ssl://gateway.push.apple.com:2195', $err,
                                   $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
    }
    
//$fp = stream_socket_client(
//'ssl://gateway.push.apple.com:2195', $err,
//$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
    
if (!$fp)
exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
//$body['aps'] = array(
//	'alert' => $message,
//	'sound' => 'default',
//	'badge' => '1'
//);
    
    $json = json_encode(array(
                              "aps" => array(
                                             'alert' => $message,
                                             'sound' => 'default',
                                             'badge' => '1'
                                                ),
                              "ctype" => $ctype,
                              "cntid" => $cid

                              ));

$payload = $json;//json_encode($mess);
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) .     $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));
    
echo "msg may be delivered";

    echo "---->";

echo $deviceToken;

// }
