<?php if($_SESSION["type"] != 0 && !(($_SESSION["type"] == 2 && $_SESSION["id"] != $id_user))) { ?>
    <!DOCTYPE html>
    <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
    <meta charset="UTF-8" />
    <title>Podrobnosti naročila</title>

    <h1>Podrobnosti o naročilu ID <?= $id ?></h1>

    <p>[
        <a href="<?= BASE_URL . "orders" ?>">Vsa naročila</a>
        ]</p>

    <?php
        $uporabnik = UsersController::getUserDetails($id_user);
        if ($id_seller != 0) {
            $prodajalec = UsersController::getUserDetails($id_seller);
        }
        $posta = PostController::get($uporabnik["zipcode_id"]);
        $order_products = OrderProductsController::getOrderProductsById($id);
    ?>


    <h4>Podatki o stranki</h4>
    <ul>
        <li>Ime in priimek: <b><?= $uporabnik["name"] ?> <?= $uporabnik["lastName"] ?></b></li>
        <li>Naslov: <b><?= $uporabnik["address"] ?></b></li>
        <li>Pošta: <b><?= $posta["zipcode"] ?></b></li>
        <li>Telefon: <b><?= $uporabnik["phone"]?> </b></li>
    </ul>

    <?php if(isset($prodajalec)) { ?>
    <h4>Podatki o prodajalcu</h4>
    <ul>
        <li>Ime in priimek prodajalca: <b><?= $prodajalec["name"] ?> <?= $prodajalec["lastName"] ?></b></li>
    </ul>
    <?php } ?>

    <br>
    <h4>Podatki o naročilu</h4>
    <ul>
        <li>Artikli:</li>
        <ul>
            <?php
            $total = 0;
            foreach ($order_products as $order_product):
                $product = ProductsController::getProductDetails($order_product["id_product"]);
                $total += $order_product["amount"] * $product["price"]; ?>
            <li><?= $order_product["amount"] ?> &times; <?= $product["title"] ?>, <b><?= $product["price"] ?> €</b></li>
            <?php endforeach;    ?>
            <p>Skupaj: <b><?= number_format($total, 2) ?> €</p>
        </ul>
        <li>Status naročila: <b>
                <?php if ($status == 0) { ?> POTRJENO
                    <?php } else if ($status == 1) { ?> ODDANO
                    <?php } else if ($status == 2) { ?> STORNIRANO
                            <?php } ?></b></li>
    </ul>

    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 1) {
            if($status != 2) { ?>
                <p>[ <a href="<?= BASE_URL . "orders/edit/" . $id ?>">Urejanje naročila</a> ] </p>
            <?php } else { ?>
                <p>Naročilo je bilo stornirano, zato sprememba statusa naročila ni mogoča.</p>
            <?php } ?>
    <?php } ?>
<?php } ?>
