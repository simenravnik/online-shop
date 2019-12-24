<!DOCTYPE html>
<link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />

<?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 0) { ?>
    <title>Seznam prodajalcev</title>
    <h1>Vsi prodajalci</h1>
<?php } else if($_SESSION["type"] == 1) { ?>
    <title>Seznam strank</title>
    <h1>Vse stranke</h1>
<?php } ?>

<p>[
<?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 0) { ?>
    <a href="<?= BASE_URL . "users/add" ?>">Dodaj novega prodajalca</a> |
    <a href="<?= BASE_URL . "profile/" . $_SESSION["id"] ?>">Uredi profil</a> |
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
<?php } else if (isset($_SESSION["loggedin"]) && $_SESSION["type"] == 1) { ?>
    <a href="<?= BASE_URL . "users/add" ?>">Dodaj novo stranko</a>  |
    <a href="<?= BASE_URL . "products" ?>">Seznam artiklov</a>
<?php } ?>
]</p>

<ul>
    <?php if(isset($_SESSION["loggedin"])) {
        if ($_SESSION["type"] == 0) {
            foreach ($users as $user):
                if ($user["type"] == 1) { ?>
                    <li><a href="<?= BASE_URL . "users/" . $user["id"] ?>">
                    <?= $user["name"] ?> <?= $user["lastName"] ?></a></li>
        <?php   }
            endforeach; ?>
    <?php } else if ($_SESSION["type"] == 1) {
            foreach ($users as $user):
                if ($user["type"] == 2) { ?>
                    <li><a href="<?= BASE_URL . "users/" . $user["id"] ?>">
                    <?= $user["name"] ?> <?= $user["lastName"] ?></a></li>
        <?php   }
            endforeach; ?>
        <?php } ?>
    <?php } ?>
</ul>
