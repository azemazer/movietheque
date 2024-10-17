<?php
require_once "../repositories/MovieRepository.php";

$repo = new MovieRepository;
$movies = $repo->getFormattedMovies();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>

    </head>
    <body>
        <div>
            <h1><a href="../index.php">Le Fabuleux Site De La Moviethèque</a></h1>
            <h2>Les films</h2>
        </div>
        <div>
            <?php 
            foreach($movies as $movie){
                ?>
                <hr/>
                <h4><a href="movie.php?id=<?= $movie["movie_id"] ?>"><?= $movie["movie_title"] ?></h4></a> - <p><?= $movie["movie_year"] ?></p>
                <p>Film de style: <?= $movie["movie_genre"] ?></p>
                <ul>
                    <li><strong>Réalisateur(s): </strong><?= $movie["filmmakers"] ?></li>
                    <li><strong>Acteurs: </strong><?= $movie["actors"] ?></li>
                    <li><strong>Studio(s): </strong><?= $movie["studios"] ?></li>
                </ul>
                <?php
            }
            ?>
        </div>
    </body>
</html>