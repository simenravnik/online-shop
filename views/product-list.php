<?php

$url = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);
$validationRules = ['do' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
            "regexp" => "/^(add_into_cart|update_cart|purge_cart)$/"
        ]
    ],
    'id' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 0]
    ],
    'kolicina' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 0]
    ]
];

$data = filter_input_array(INPUT_POST, $validationRules);


switch ($data["do"]) {
    case "add_into_cart":
        try {
            $product = ProductDB::get(array("id" => $data["id"]));

            if (isset($_SESSION["cart"][$product["id"]])) {
                $_SESSION["cart"][$product["id"]] ++;
            } else {
                $_SESSION["cart"][$product["id"]] = 1;
            }
        } catch (Exception $exc) {
            die($exc->getMessage());
        }
        break;
    case "update_cart":
        if (isset($_SESSION["cart"][$data["id"]])) {
            if ($data["kolicina"] > 0) {
                $_SESSION["cart"][$data["id"]] = $data["kolicina"];
            } else {
                unset($_SESSION["cart"][$data["id"]]);
            }
        }
        break;
    case "purge_cart":
        unset($_SESSION["cart"]);
        break;
    default:
        break;
}
?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
        <link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
        <meta charset="UTF-8" />
        <title>Seznam artiklov</title>
    <head>
    <body>
        <h1>Spletna trgovina</h1>

        <p>[
        <?php if(!isset($_SESSION["loggedin"])) { ?>
            <a href="<?= BASE_URL . "registration" ?>">Registracija</a> |
            <a href="<?= BASE_URL . "login" ?>">Prijava za stranke</a> |
            <a href="<?= BASE_URL . "certificate" ?>">Prijava s certifikatom</a>
        <?php } else {
            if($_SESSION["type"] == 1) { ?>
                <a href="<?= BASE_URL . "products/add" ?>">Dodaj nov artikel</a> |
                <a href="<?= BASE_URL . "users" ?>">Vsi uporabniki</a> |
                <a href="<?= BASE_URL . "orders" ?>">Vsa naročila</a> |
            <?php } else if ($_SESSION["type"] == 0) { ?>
                <a href="<?= BASE_URL . "users" ?>">Vsi uporabniki</a> |
        <?php } else if ($_SESSION["type"] == 2 ){ ?>
                <a href="<?= BASE_URL . "orders" ?>">Vsa naročila</a> |
        <?php } ?>
            <a href="<?= BASE_URL . "profile/" . $_SESSION["id"] ?>">Uredi profil</a> |
            <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
        <?php } ?>
        ]</p>

        <div id ="main">
            <?php
            foreach ($products as $product): ?>
                <div class="article">
                    <form action="<?= BASE_URL . "products" ?>" method="post">
                        <input type="hidden" name="do" value="add_into_cart" />
                        <input type="hidden" name="id" value="<?= $product["id"] ?>" />
                        <p><?= $product["title"] ?></p>
                        <b><p><?= number_format($product["price"], 2) ?> €<br/></b>
                        <a href="<?= BASE_URL . "products/" . $product["id"] ?>" class="btn btn-info details"><i class="fas fa-info-circle"></i> Podrobnosti</a>
                        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
                            <?php if($product["activated"] == 1) { ?>
                                <button type="submit" class="btn btn-info add-to-cart"><i class="fas fa-cart-plus"></i> V košarico</button>
                            <?php } else { ?>
                                <p>Trenutno ni na voljo</p>
                            <?php } ?>
                        <?php } ?>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
            <div class="cart">
                <h3>Košarica</h3>

                <?php
                $kosara = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];

                if ($kosara) {
                    $znesek = 0;
                    foreach ($kosara as $id => $kolicina):
                        $product = ProductDB::get(array("id" => $id));
                        $znesek += $product["price"] * $kolicina;
                        ?>
                        <form action="<?= BASE_URL . "products" ?>" method="post">
                            <input type="hidden" name="do" value="update_cart" />
                            <input type="hidden" name="id" value="<?= $product["id"] ?>" />
                            <input type="number" name="kolicina" value="<?= $kolicina ?>"
                                   class="short_input" />
                            &times; <?=
                            (strlen($product["title"]) < 50) ?
                                    $product["title"] :
                                    substr($product["title"], 0, 26) . " ..."
                            ?> (<?= number_format($product["price"], 2) ?> €)
                            <button class="update-cart" type="submit"><i class="fas fa-sync-alt"></i> Posodobi</button>
                        </form>
                    <?php endforeach; ?>

                    <p>Skupaj: <b><?= number_format($znesek, 2) ?> EUR</b></p>

                    <form action="<?= BASE_URL . "orders/confirmation" ?>" method="POST">
                          <button type="submit" class="btn btn-success gumb"><i class="fas fa-receipt"></i></i> Pripravi naročilo</button>
                    </form>
                    <form action="<?= BASE_URL . "products" ?>" method="POST">
                        <input type="hidden" name="do" value="purge_cart" />
                        <button type="submit" class="btn btn-danger gumb"><i class="fas fa-trash-alt"></i> Izprazni košarico</button>
                    </form>

                <?php } elseif(!isset($_SESSION["loggedin"])) { ?>
                    Za dodajanje v košarico se je potrebno prijaviti.
                <?php } else { ?>
                    Košara je prazna.
                <?php } ?>
            </div>
        <?php } ?>
    </body>
</html>
