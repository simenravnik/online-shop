<!DOCTYPE html>
<link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Podrobnosti artikla</title>

<h1>Podrobnosti o artiklu: <?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "products" ?>">Vsi artikli</a>
    ]</p>

<ul>
    <li>Naziv: <b><?= $title ?></b></li>
    <li>Cena: <b><?= $price ?> €</b></li>
    <li>Status: <b><?= $activated ? "Artikel je na voljo." : "Artikel trenutno ni na voljo." ?></b></li>
    <li>Opis: <i><?= $description ?></i></li>
</ul>

<?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 1) { ?>
<p>[ <a href="<?= BASE_URL . "products/edit/" . $id ?>">Urejanje artikla</a> ] </p>
<?php } ?>
