<?php 
session_start();
//quand l'utilisateur veut se connecter via index.php avec la methode submit post

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_SESSION['token'])) {
        header('Location: https://connect.deezer.com/oauth/auth.php?app_id=596684&redirect_uri=https://deezeruserview.000webhostapp.com/secret.php&perms=listening_history');
    }
}
?>