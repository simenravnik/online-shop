<!DOCTYPE html>
<link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Posodabljanje artikla</title>

<h1>Posodabljanje zapisa artikla: <?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "products/add" ?>">Dodaj nov artikel</a> |
    <a href="<?= BASE_URL . "products" ?>">Vsi artikli</a>
    ]</p>

<form action="<?= BASE_URL . "products/edit/" . $id ?>" method="post">
    <input type="hidden" name="id" value="<?= $id ?>"  />
    <p><label>Naziv: <input type="text" name="title" value="<?= $title ?>" autofocus required /></label></p>
    <p><label>Cena: <input type="number" name="price" step="0.01" min="0" value="<?= $price ?>" required /></label></p>
    <p><label>Aktiviran: <input type="checkbox" name="activated" <?php if(isset($activated) && $activated == 1){print " checked=\"checked\"";} ?> /></label></p>
    <p><label>Opis: <br/><textarea name="description" cols="70" rows="10" required ><?= $description ?></textarea></label></p>
    <p><button>Posodobi zapis artikla</button></p>
</form>

<!--<form action="<?//= BASE_URL . "articles/delete/" . $id ?>" method="post">
    <label>Izbris artikla? <input type="checkbox" name="delete_confirmation" required /></label>
    <button type="submit" class="important">Izbriši artikel</button>
</form>-->
