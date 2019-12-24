<?php if($type > $_SESSION["type"]) { ?>
    <!DOCTYPE html>
    <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
    <meta charset="UTF-8" />
    <title>Podrobnosti uporabnika</title>

    <h1>Podrobnosti o uporabniku: <?= $name ?> <?= $lastName ?></h1>

    <p>[
        <a href="<?= BASE_URL . "users/add" ?>">Dodaj novega uporabnika</a> |
        <a href="<?= BASE_URL . "users" ?>">Vsi uporabniki</a>
        ]</p>

    <ul>
        <li>Ime in priimek: <b><?= $name ?> <?= $lastName ?></b></li>
        <li>E-naslov: <b><?= $email ?></b></li>
        <li>Tip uporabnika: <?php if($type == 0) { ?> <b>Administrator</b>
        <?php } else if($type == 1) { ?> <b>Prodajalec</b>
        <?php } else if($type == 2) { ?> <b>Stranka</b>
        <?php } ?>
        </li>
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
            <li>Naslov: <b><?= $address ?></b></li>
            <li>Poštna številka: <b><?= $zipcode_id ?></b></li>
            <li>Telefon: <b><?= $phone ?></b></li>
        <?php } ?>
        <li>Aktiviran: <b><?= $activated ? "DA" : "NE" ?></b></li>
    </ul>

    <?php if($type > $_SESSION["type"]) { ?>
    <p>[ <a href="<?= BASE_URL . "users/edit/" . $id ?>">Urejanje uporabnika</a> ] </p>
    <?php } ?>
        <!--<a href="<?//= BASE_URL . "users" ?>">Nazaj na seznam uporabnikov</a>-->
<?php } ?>
