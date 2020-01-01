<?php

 $con = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME) or die('Unable to Connect');

 if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['img'])){

   $path = $_POST['img'];
   $sql = "DELETE FROM images WHERE img='$path';";

   if ($con->query($sql) === TRUE) {
      echo "";
   } else {
       echo "Error deleting record: " . $con->error;
   }

   $con->close();

 } else if($_SERVER['REQUEST_METHOD']=='POST'){

 //checking the required parameters from the request
 if(isset($_FILES['image']['name'])){


 //getting file info from the request
 $fileinfo = pathinfo($_FILES['image']['name']);

 //getting the file extension
 $extension = $fileinfo['extension'];

 if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif"))
 	{
        echo 'Unknown image format.';
    }
//jpg-jpeg
if($extension=="jpg" || $extension=="jpeg" )
    {
        $uploadedfile = $_FILES['image']['tmp_name'];
        $src = imagecreatefromjpeg($uploadedfile);
        list($width,$height)=getimagesize($uploadedfile);

        //set new width
        $newwidth1=900;
        $newheight1=($height/$width)*$newwidth1;
        $tmp1=imagecreatetruecolor($newwidth1,$newheight1);

        imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
        //new random name
        $temp = explode(".", $_FILES["image"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        $filename1 = "static/img/". $newfilename;

        imagejpeg($tmp1,$filename1,100);

        imagedestroy($src);
        imagedestroy($tmp1);
        //insert in database
        $insert=mysqli_query($con, "INSERT INTO images (img, id_product) VALUES ('$filename1', $id_product);");
        echo "<html>
		<head>
		</head>
		<body>
			<meta http-equiv='REFRESH' content='0 ; url='#''>
			<script>
				alert('The image has been uploaded .');
			</script>
		</body>
        </html>";
    }
//png
    else if($extension=="png")
    {
        $uploadedfile = $_FILES['image']['tmp_name'];
        $src = imagecreatefrompng($uploadedfile);
        list($width,$height)=getimagesize($uploadedfile);
        //set new width
        $newwidth1=900;
        $newheight1=($height/$width)*$newwidth1;
        $tmp1=imagecreatetruecolor($newwidth1,$newheight1);

        imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);

        //new random name
        $temp = explode(".", $_FILES["image"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        $filename1 = "static/img/". $newfilename;

        imagejpeg($tmp1,$filename1,100);

        imagedestroy($src);
        imagedestroy($tmp1);
        //insert in database
        $insert=mysqli_query($con, "INSERT INTO images (img, id_product) VALUES ('$filename1', $id_product);");
        echo "<html>
		<head>
		</head>
		<body>
			<meta http-equiv='REFRESH' content='0 ; url='#''>
			<script>
				alert('The image has been uploaded .');
			</script>
		</body>
        </html>";
    }
    else if($extension=="gif") {
    $uploadedfile = $_FILES['image']['tmp_name'];

    //new random name
    $temp = explode(".", $_FILES["image"]["name"]);
    $newfilename = round(microtime(true)) . '.' . end($temp);

    $filename1 = "static/img/". $newfilename;
	move_uploaded_file($uploadedfile,$filename1);
    //insert in database
    $insert=mysqli_query($con, "INSERT INTO images (img, id_product) VALUES ('$filename1', $id_product);");

    echo "<html>
    <head>
    </head>
    <body>
        <meta http-equiv='REFRESH' content='0 ; url='#''>
        <script>
            alert('The image has been uploaded .');
        </script>
    </body>
    </html>";
    }
    else
    {
        echo 'error';
    }
}
}
?>

<?php if(isset($_SESSION["loggedin"]) && $_SESSION["type"] == 1) { ?>
   <!DOCTYPE html>
   <html>
       <head>
           <meta charset="UTF-8">
           <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
           <meta name="description" content="Online shop">
           <meta name="author" content="Simen Ravnik">

           <title>Shop</title>

           <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

           <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
           <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "bootstrap4-retro.min.css" ?>">
           <link rel ="stylesheet" type="text/css" href="<?= CSS_URL . "shop-homepage.css" ?>">

           <link href="https://fonts.googleapis.com/css?family=Montserrat|Shrikhand" rel="stylesheet">

       <head>
       <body>
           <!-- Navigation -->
           <nav class="navbar navbar-expand-sm navbar-light fixed-top" style="background-color: #ffffff;">
             <div class="container">
               <a class="navbar-brand" href="<?= BASE_URL . "products" ?>">Online Shop</a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                   <li class="nav-item">
                     <a class="nav-link" href="<?= BASE_URL . "products" ?>">Home
                       <span class="sr-only">(current)</span>
                     </a>
                   </li>
                   <?php if(!isset($_SESSION["loggedin"])) { ?>
                       <li class="nav-item">
                         <a class="nav-link" href="<?= BASE_URL . "registration" ?>">Register</a>
                       </li>
                       <li class="nav-item">
                         <a class="nav-link" href="<?= BASE_URL . "login" ?>">Login</a>
                       </li>
                       <li class="nav-item">
                         <a class="nav-link" href="<?= BASE_URL . "certificate" ?>">Admins/Sellers</a>
                       </li>
                   <?php } else {
                       if($_SESSION["type"] == 1) { ?>
                           <li class="nav-item">
                              <a class="nav-link active" href="<?= BASE_URL . "products/add" ?>">Add products</a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="<?= BASE_URL . "users" ?>">Customers</a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" href="<?= BASE_URL . "orders" ?>">Orders</a>
                           </li>
                       <?php } else if ($_SESSION["type"] == 0) { ?>
                           <li class="nav-item">
                              <a class="nav-link" href="<?= BASE_URL . "users" ?>">Sellers</a>
                           </li>
                        <?php } else if ($_SESSION["type"] == 2 ){ ?>
                           <li class="nav-item">
                              <a class="nav-link" href="<?= BASE_URL . "orders" ?>">My orders</a>
                           </li>
                        <?php } ?>
                           <li class="nav-item">
                              <a class="nav-link" href="<?= BASE_URL . "profile/" . $_SESSION["id"] ?>">Edit profile</a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link btn btn-logout" href="<?= BASE_URL . "logout" ?>">Logout</a>
                           </li>
                   <?php } ?>
                </ul>
               </div>
             </div>
           </nav>

           <!-- Page Content -->
           <div class="container">

             <div class="row">

               <div class="col-lg-3">
                  <a href="<?= BASE_URL . "products/" ?>" style="color: black;">
                    <h1 class="my-4">Products</h1>
                 </a>
               </div>
               <!-- /.col-lg-3 -->

               <div class="col-lg-9">

                  <div class="wrapper">
                      <br>
                      <h2>Upload product images</h2>
                      <br>

                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                         <input type="file" name="image"/>
                         <button type="submit">Upload</button>
                      </form>

                      <?php
                      $con = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME) or die('Unable to Connect');

                      $sql =mysqli_query($con, "SELECT * FROM images where id_product = $id_product;");

                      ?>
                      <br>
                      <br>

                       <h2>Product gallery</h2>

                       <hr class="mt-2 mb-5">

                       <div class="row text-center text-lg-left">
                         <?php while($row=mysqli_fetch_array($sql)) {
                           $img = $row['img'];
                           $path = BASE_URL . $img;
                         ?>
                         <div class="col-lg-3 col-md-4 col-6">
                             <img class="img-fluid img-thumbnail" src="<?= $path ?>" alt="">
                             <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"method="POST">
                                <input type="text" name="img" class="form-control" value="<?= $img ?>" hidden/>
                                <div class="mt-1 mb-3">
                                   <button type="submit" name="delete" class="btn btn-sm btn-block btn-outline-danger my-1 my-sm-0">Remove</button>
                                </div>
                             </form>
                         </div>
                        <?php } ?>
                       </div>

                       <br>
                       <div class="float-right">
                          <a href="<?= BASE_URL . "products/" . $id_product ?>" class="btn btn-md btn-primary my-1 my-sm-0">Done</a>
                       </div>

                     <br>
                  </div>

               </div>
               <!-- /.col-lg-9 -->

             </div>
             <!-- /.row -->

           </div>
           <!-- /.container -->
           <br>

           <script src='https://www.google.com/recaptcha/api.js'></script>
           <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
           <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
           <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

       </body>
   </html>
<?php } ?>
