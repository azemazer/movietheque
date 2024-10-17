<?php
require_once "../repositories/MovieRepository.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>

</head>
<body>
<div>
    <h1><a href="../index.php">Le Fabuleux Site De La Moviethèque</a></h1>
    <h2>Fiche film</h2>
</div>
<?php
if (!isset($_GET["id"])){
    ?>
    <p>404 Film Not Found</p>
    <?php return;
} else {

}
$repo = new MovieRepository;
$movie = $repo->getMovieById($_GET["id"]);
?>
    <div>
        <h3><?= $movie->getTitle() ?></h3>
        <p><?= $movie->getYear() ?> - <?= $movie->getGenre() ?></p>
        <hr/>
        <p>Synopsis: <?= $movie->getDescription() ?></p>
        <?php if(count($movie->getActors()) > 0){ ?>
            <div>
                <h4>Actors</h4>
                <ul>
                    <?php foreach($movie->getActors() as $actor){
                        ?>
                        <li>
                            <strong><?= $actor->getName() ?></strong> : <?= $actor->getBiography() ?>
                        </li>
                        <?php
                    } ?>
                </ul>
            </div>
        <?php }
        if(count($movie->getFilmmakers()) > 0){ ?>
            <div>
                <h4>Filmmakers</h4>
                <ul>
                    <?php foreach($movie->getFilmmakers() as $filmmaker){
                        ?>
                        <li>
                            <strong><?= $filmmaker->getName() ?></strong> : <?= $filmmaker->getBiography() ?>
                        </li>
                        <?php
                    } ?>
                </ul>
            </div>
        <?php }
            if(count($movie->getStudios()) > 0){ ?>
            <div>
                <h4>Studios</h4>
                <ul>
                    <?php foreach($movie->getStudios() as $studio){
                        ?>
                        <li>
                            <?= $studio->getName() ?>
                        </li>
                        <?php
                    } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</body>
</html>