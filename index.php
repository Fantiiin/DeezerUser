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
      <title>DeezSTATS</title>
      <link rel="stylesheet" href="style.css">
   </head>
   <body>
      <h1>DeezerStats</h1>
      <div id="entete">
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
            $photoProfileUser = $data['picture_small'];
            $id = $data['id'];
            $_SESSION['userid'] = $id;
            $nom = $data['name'];
            // Afficher la valeur de l'ID
            echo " Bienvenue " . $nom;
            echo "<br>La valeur de l'ID est : " . $id;
            echo "<img src =".$photoProfileUser.">";
            }
            ?>
      </div>
      <button onclick="window.location.href = 'playlist.php';">Editeur de playlist</button>
      <div id="history">
         <h2>Historique</h2>
         <p>Historique des ecoutes</p>
         <?php
            $genres_colors = array(
            'Autre' => '#000000',
            'Pop' => '#FFC0CB',
            'Livres audio' => '#FFFF00',
            'Rap/Hip Hop' => '#FF4500',
            'Rock' => '#00FFFF',
            'Dance' => '#FF69B4',
            'Disco' => '#FF9CFF',
            'R&B' => '#8A2BE2',
            'Alternative' => '#7FFF00',
            'Electro' => '#1E90FF',
            'Folk' => '#FFD700',
            'Reggae' => '#00FF00',
            'Jazz' => '#FFA500',
            'Techno/House' => '#6CFF69',
            'Chanson française' => '#FF1493',
            'Country' => '#ADFF2F',
            'Classique' => '#800080',
            'Films/Jeux vidéo' => '#FF0000',
            'Metal' => '#C0C0C0',
            'Soul & Funk' => '#FF7F50',
            'Blues' => '#0000FF',
            'Jeunesse' => '#00FA9A',
            'Latino' => '#FFDAB9',
            'Musique africaine' => '#808000',
            'Musique arabe' => '#4B0082',
            'Musique asiatique' => '#8B0000',
            'Musique brésilienne' => '#FF6347',
            'Musique indienne' => '#FFA07A',
            );
            
            // Tableau associatif de correspondance genre => compteur
            $genres_count = array();
            
            if (isset($_SESSION["token"])){   
            $url = "https://api.deezer.com/user/".$_SESSION['userid']."/history?access_token=" . $_SESSION['token'];
            $options = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false
            )
            );
            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $data = json_decode($response, true);
            $nbpage = 1;
            $tracks = array();
            for ($i = (($nbpage-1)*25); $i < ($nbpage*25) && $i < count($data['data']); $i++) {
            $track = array(
            'id' => $data['data'][$i]['id'],
            'title' => $data['data'][$i]['title'],
            'album' => $data['data'][$i]['album']['title'],
            'albumid' => $data['data'][$i]['album']['id'],
            'albumcover'=>  $data['data'][$i]['album']['cover'],
            'artistname' => $data['data'][$i]['artist']['name']
            );
            array_push($tracks, $track);
            }
            
            // Afficher les titres et id récupérés
            foreach ($tracks as $track) {
            $urll = "https://api.deezer.com/album/" . $track['albumid'];
            $optionss = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
            );
            $contextt = stream_context_create($optionss);
            $responsse = file_get_contents($urll, false, $contextt);
            $dataa = json_decode($responsse, true);
            
            $genres_data = $dataa['genres']['data'];
            $genres_names = array();
            foreach ($genres_data as $genre) {
            $genres_names[] = $genre['name'];
            }
            $genres_str = count($genres_names) > 0 ? implode(', ', $genres_names) : "Autre";
            if (strpos($genres_str, ", ") !== false) {
            $genres_array = explode(", ", $genres_str);
            foreach ($genres_array as $genre){
            if (!isset($genres_count[$genre])) {
                $genres_count[$genre] = 1;
            } else {
                $genres_count[$genre]++;
            }
            }
            $color = isset($genres_colors[$genre]) ? $genres_colors[$genre] : '#000000';
            } else {
            if (!isset($genres_count[$genres_str])) {
                $genres_count[$genres_str] = 1;
            } else {
                $genres_count[$genres_str]++;
            }
            $color = isset($genres_colors[$genres_str]) ? $genres_colors[$genres_str] : '#000000';
            }
            
            // Affichage du titre avec couleur de fond
            echo '<div style="color: '.$color.'">';
            echo $track['artistname'] . ' - ' . $track['title'] . '<br>';
            echo '<img src="'.$track['albumcover'].'"> Album: '.$track['album'].' Genre: '.$genres_str.'<br><br>';
            echo '</div>';
            
            
            }
            echo '<div id= "GenresRecents">';
            echo '<h2>Vos genres récents:</h2>';
            echo '<ul>';
            $dataPoints = array();
            foreach ($genres_count as $genre_name => $count) {
            $color = isset($genres_colors[$genre_name]) ? $genres_colors[$genre_name] : 'gray';
            echo '<li style="color:'.$color.'">'.$genre_name.': '.$count.'</li>';
            }
            echo '</ul>';
            echo '</div>';
            
            } else {
            echo "Connectez vous !";
            }
            ?>
      </div>
   </body>
</html>
