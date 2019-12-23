<!DOCTYPE html>
<link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Dodajanje artikla</title>

<h1>Dodaj nov artikel</h1>

<p>[
<a href="<?= BASE_URL . "products" ?>">Vsi artikli</a>
]</p>

<form action="<?= BASE_URL . "products/add" ?>" method="post">
    <p><label>Naziv: <input type="text" name="title" value="<?= $title ?>" autofocus required /></label></p>
    <p><label>Cena: <input type="number" name="price" step="0.01" min="0" value="<?= $price ?>" required /></label></p>
    <input type="hidden" name="activated" value=0>
    <p><label>Aktiviran: <input type="checkbox" name="activated" /></label></p>
    <p><label>Opis: <br/><textarea name="description" cols="70" rows="10" required><?= $description ?></textarea></label></p>
    <p><button>Dodaj artikel</button></p>
</form>
