<?php 
    ob_start();
    include 'token.php'; 
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>DeezerStats</h1>
        <?php 
            if (isset($_SESSION["token"])){
                echo '<form  action="./index.php" method="post">
                <input type="submit" value="Se déconnecter" name="logout"/>
                </form>';
                echo "<br>" . $_SESSION["token"] ."<br>";

            } else {
                echo '<form  action="./index.php" method="post">
                <input type="submit" value="Se connecter" name="login"/>
                </form>';
            }
            if (isset($_POST["logout"])){
                session_destroy();
                header("Location: ./index.php");
            }
            
            
            
            
if (isset($_SESSION["token"])){   
$url = "https://api.deezer.com/user/me?access_token=" . $_SESSION['token'];

// Options de flux de contexte pour désactiver la vérification du certificat SSL
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false
    )
);
$context = stream_context_create($options);

// Récupérer la réponse JSON à partir de l'URL en utilisant le flux de contexte
$response = file_get_contents($url, false, $context);
// Convertir la réponse JSON en un tableau associatif PHP
$data = json_decode($response, true);

// Récupérer la valeur de l'ID
$id = $data['id'];
$nom = $data['name'];
// Afficher la valeur de l'ID
echo "Bienvenue " . $nom;
echo "<br>La valeur de l'ID est : " . $id;

}
        ?>
    </form>
</body>
</html>