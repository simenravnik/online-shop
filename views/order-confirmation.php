<!DOCTYPE html>
<link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<meta charset="UTF-8" />
<title>Potrditev naročila</title>

<h1>Potrditev naročila</h1>

<p>[
    <a href="<?= BASE_URL . "orders" ?>">Vsa naročila</a>
    ]</p>

<?php
    $uporabnik = UsersController::getUserDetails($_SESSION["id"]);
    $posta = PostController::get($uporabnik["zipcode_id"]);
?>


<h4>Podatki o stranki</h4>
<ul>
    <li>Ime in priimek: <b><?= $uporabnik["name"] ?> <?= $uporabnik["lastName"] ?></b></li>
    <li>Naslov: <b><?= $uporabnik["address"] ?></b></li>
    <li>Pošta: <b><?= $posta["zipcode"] ?></b></li>
    <li>Telefon: <b><?= $uporabnik["phone"]?> </b></li>
</ul>

<br>
<h4>Podatki o naročilu</h4>
<ul>
    <li>Artikli:</li>
    <ul>
        <?php
        $total = 0;
        if(isset($_SESSION["cart"])) $kosara = $_SESSION["cart"];
        foreach ($kosara as $id => $kolicina):
            $product = ProductsController::getProductDetails($id);
            $total += $kolicina * $product["price"]; ?>
        <li><?= $kolicina ?> &times; <?= $product["title"] ?>, <b><?= $product["price"] ?> €</b></li>
        <?php endforeach;    ?>
        <p>Skupaj: <b><?= number_format($total, 2) ?> €</p>
    </ul>
</ul>
<br>
<form action="<?= BASE_URL . "orders/submit" ?>" method="POST">
    <button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> Oddaj naročilo</button>
</form>
