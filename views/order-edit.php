<?php if($_SESSION["type"] == 1) { ?>
<!DOCTYPE html>
<link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Posodabljanje statusa naročila</title>

<h1>Posodabljanje statusa naročila ID <?= $id ?></h1>

<p>[
    <a href="<?= BASE_URL . "orders" ?>">Vsa naročila</a>
    ]</p>

<form action="<?= BASE_URL . "orders/edit/" . $id ?>" method="post">
    <input type="hidden" name="id" value="<?= $id ?>"  />
    <input type="hidden" name="id_user" value="<?= $id_user ?>" />
    <input type="hidden" name="id_seller" value="<?= $_SESSION["id"]  ?>" />
    <p><label>Status:
    <select name="status" value="<?= $status ?>">
        <?php // ce je narocilo trenutno v statusu ODDANO
            if($status == 1) {
        ?>
            <option value="0">Potrjeno</option>
            <option value="1" selected>Oddano</option>
            <?php } else if($status == 0) {
              // ce je narocilo trenutno v statusu POTRJENO ?>
            <option value="0" selected>Potrjeno</option>
            <option value="2">Stornirano</option>
            <?php } else if($status == 2) {  ?>
            <option value="2" selected>Stornirano</option>
            <?php } ?>
    </select></label></p>
    <p><button>Posodobi status naročila</button></p>
</form>

<!--<form action="<?//= BASE_URL . "products/delete/" . $id ?>" method="post">
    <label>Izbris artikla? <input type="checkbox" name="delete_confirmation" required /></label>
    <button type="submit" class="important">Izbriši artikel</button>
</form>-->
<?php } ?>
