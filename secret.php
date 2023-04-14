<?php
session_start();
$code = $_GET['code'];
$url = "https://connect.deezer.com/oauth/access_token.php?app_id=&secret=69&code=".$code;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false) {
    echo "Erreur cURL : " . curl_error($ch);
} else {
    if (strpos($result, "access_token=") !== false) {
        $start = strpos($result, "access_token=") + 13;
        $end = strpos($result, "&", $start);
        $access_token = substr($result, $start, $end - $start);
        // Utilisez la variable $access_token pour enregistrer la valeur dans une variable de session ou pour toute autre utilisation.
        $_SESSION['token'] = $access_token;
    } 

}

        header('Location: https://deezeruserview.000webhostapp.com/index.php');


?>