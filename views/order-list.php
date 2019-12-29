<?php if($_SESSION["type"] != 0 ) { ?>
    <!DOCTYPE html>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap.min.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
    <meta charset="UTF-8" />

    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 0) { ?>
    <?php } else if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 1) { ?>
        <title>Seznam naročil</title>
        <h1>Seznam naročil</h1>
    <?php } else if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 2) { ?>
        <title>Moja naročila</title>
        <h1>Moja naročila</h1>
    <?php } ?>

    <p>[
    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] != 0) { ?>
        <a href="<?= BASE_URL . "products" ?>">Seznam artiklov</a>
    <?php } ?>
    ]</p>

    <ul>
        <?php if(isset($_SESSION["loggedin"])) {
            if ($_SESSION["type"] == 0) { ?>
        <?php } else if ($_SESSION["type"] == 1) { ?>
            <h3>Oddana naročila:</h3>
        <?php foreach ($orders as $order):
                if ($order["status"] == 1) { ?>
                    <li><a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                        Naročilo - ID <?= $order["id"] ?>
                    </a>
                    <form action="<?= BASE_URL . "orders/delete/" . $order["id"] ?>" method="post">
                        <label>Izbris naročila? <input type="checkbox" name="delete_confirmation" required /></label>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Prekliči naročilo</button>
                    </form>
                    </li>
                <?php } endforeach; ?>
            <h3>Potrjena naročila:</h3>
                <?php foreach ($orders as $order):
                if ($order["status"] == 0) { ?>
                    <li><a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                        Naročilo - ID <?= $order["id"] ?>
                    </a></li>
                <?php } endforeach; ?>
            <h3>Stornirana naročila:</h3>
                <?php foreach ($orders as $order):
                if ($order["status"] == 2) { ?>
                    <li><a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                        Naročilo - ID <?= $order["id"] ?>
                    </a></li>
                <?php } endforeach; ?>
            <?php } else if ($_SESSION["type"] == 2) {
                foreach ($orders as $order):
                    if ($order["id_user"] == $_SESSION["id"]) { ?>
                        <li><a href="<?= BASE_URL . "orders/" . $order["id"] ?>">
                        Naročilo - ID <?= $order["id"] ?> </a>| Status naročila:
                        <?php if ($order["status"] == 0) { ?> POTRJENO
                        <?php } else if ($order["status"] == 1) { ?> ODDANO
                        <?php } else if ($order["status"] == 2) { ?> STORNIRANO
                        <?php } ?>
                            </li>
                <?php } endforeach; ?>
            <?php } ?>
        <?php } ?>
    </ul>
<?php } ?>
