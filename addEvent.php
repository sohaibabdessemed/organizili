<?php
session_start();
extract($_REQUEST);

if(isset($_SESSION['activestaff']) || isset($_SESSION['activeadmin'])){
    try{
        require("../database/connection.php");

        if(isset($type)){

            $addSQL= "insert into product (type,choice,salle,year,people,price,Menu,status) values ('$type','$choice','$salle','$year','$people','$price','$Menu','1')";
            $addSQL = $db->exec($addSQL);
            $ID  = $db->lastInsertId();

            mkdir("../img/".$ID);

            $countfiles = count($_FILES['fileToUpload']['choice']);

             for($i=0;$i<$countfiles;$i++){
                 $x =$i+1;
                $target_dir = "../img/".$ID."/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["choice"][$i]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_choice"][$i]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                    }
                }
                // Check if file already exists
                if (file_exists($target_file)) {
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["fileToUpload"]["size"][$i] > 10000000) {
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg") {
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_choice"][$i], '../img/'.$ID.'/'.$x.'.jpg')) {

                    } else {
                    }
                }
            }
            header("location: addEvent.php?flag=ok");
        }
    }

    catch(PDOException $e)
        {
        echo "Connection failed: " . $e->getMessage();
        }

}
else {
    header("location: index.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <script src="https://kit.fontawesome.com/0547f82a88.js" crossorigin="anonymous"></script>
    <title>NMC</title>
</head>
<body class=" bg-light">
  <!-- nav bar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light ">
          <div class="container shadow-sm p-3 mb-2 bg-white rounded">
            <!-- Brand -->
            <a class="navbar-brand" href="#">
              Organizili.
            </a>
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
              <!-- Nav -->
              <ul class="navbar-nav mx-auto">
                <li class="nav-item ">
                  <!-- Toggle -->
                  <a class="nav-link active" href="dashboard.php?page=1&ipp=2">Events</a>
                </li>
                <li class="nav-item ">
                  <!-- Toggle -->
                  <a class="nav-link" href="employee.php?page=1&ipp=2">Admins</a>
                </li>
                <li class="nav-item ">
                  <!-- Toggle -->
                  <a class="nav-link" href="customer.php?page=1&ipp=2">Customer</a>
                </li>
              </ul>
              <!-- Nav -->
              <ul class="navbar-nav flex-row">
                <li class="nav-item ml-lg-n4">
                  <a class="nav-link" href="profile.php">
                      <i class="fas fa-user"></i>
                  </a>
                </li>
                <li class="nav-item ml-lg-n2 ml-3">
                  <a class="nav-link" href="../database/logout.php">
                      Logout
                  </a>
                </li>
              </ul>
            </div>
          </div>
      </nav>
<div class="mr-sm-5 ml-sm-5">
    <div class="row">
      <div class="col-lg-10 col-xl-9 mx-auto">
        <div class="card card-signin flex-row my-5">
          <div class="card-body">
          <div class="d-flex justify-content-between">
            <div></div>
            <div>
                <h5 class="card-title font-weight-bold">Add Event</h5>
            </div>
            <div>
                <button class="btn btn-outline-primary" onclick="window.document.location.href='dashboard.php?page=1&ipp=2'"><i class="fas fa-arrow-circle-left"></i></button>
            </div>
          </div>
          <?php if(isset($flag) && $flag == 'ok') {?>
            <h6 class='font-weight-bold text-success mt-2 mb-3'>Added Successfully !</h6>
            <?php }?>
            <form class="form-signin" method="post" action="addEvent.php"enctype='multipart/form-data'>
              <div class="form-group p-2 font-weight-bold">
                <label for="type">Event Type:</label>
                <select class="form-control" name="type" id="type">
                    <option value='birthday'>Birthday</option>
                    <option value='wedding'>Wedding</option>
                    <option value='babyshower'>Baby Shower</option>
                    <option value='graduation'>Graduation Party</option>
                    <option value='proposal'>Proposal</option>
                    <option value='other'>Other</option>

                </select>
              </div>

              <hr>

              <div class="form-label-group">
                <input name="choice" type="text" id="inputchoice" class="form-control" placeholder="Plan" required autofocus>
                <label for="inputchoice">Plan</label>
              </div>

              <hr>

              <div class="form-label-group">
                <input name="salle" type="text" id="inputsalle" class="form-control" placeholder="Party Hall" required autofocus>
                <label for="inputsalle">Party Hall</label>
              </div>

              <hr>



              <hr>

              <div class="form-label-group">
                <input name="people" type="text" min='1' id="inputpeople" class="form-control" placeholder="Number of people" required>
                <label for="inputpeople">Number of people</label>
              </div>

              <hr>

              <div class="form-label-group">
                <input name="Menu" type="text" id="inputMenu" class="form-control" placeholder="Menu" required>
                <label for="inputMenu">Menu</label>
              </div>

              <hr>

              <div class="form-label-group">
                <input name="price" type="number" id="inputPrice" class="form-control" placeholder="Price" required>
                <label for="inputPrice">Price</label>
              </div>

              <hr>

              <div class="form-group p-2 font-weight-bold">
                <label for="inputPic">Images</label>
                <input name="fileToUpload[]" type="file" id="inputPic" class="form-control" placeholder="Images" required multiple>
              </div>

              <button name="submit" class="btn btn-lg btn-primary font-weight-bold h3 btn-block text-uppercase" type="submit">ADD</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<footer class="bg-dark text-left">
    <div class="border-bottom border-gray-700 pt-5 pb-5">
        <div class="container">
        <div class="row">
            <div class="col-12 col-md-3">
            <!-- Heading -->
            <h4 class="mb-6 text-warning">ORGANIZILI</h4>
            <!-- Social -->
            <ul class="list-unstyled list-inline mb-7 mb-md-0">
                <li class="list-inline-item">
                <a href="#!" class="text-warning">
                    <i class="fab fa-facebook-f"></i>
                </a>
                </li>
                <li class="list-inline-item">
                <a href="#!" class="text-warning">
                    <i class="fab fa-youtube"></i>
                </a>
                </li>
                <li class="list-inline-item">
                <a href="#!" class="text-warning">
                    <i class="fab fa-twitter"></i>
                </a>
                </li>
                <li class="list-inline-item">
                <a href="#!" class="text-warning">
                    <i class="fab fa-instagram"></i>
                </a>
                </li>
            </ul>
            </div>
            <div class="text-right col-6 col-sm">
            <!-- Heading -->
            <h4 class="mb-6 text-warning">
                Contact
            </h4>
            <!-- Links -->
            <ul class="list-unstyled mb-0 text-light">
                <li>+213699874562</li>
                <li><a class="text-light" href="#">organizli@help.com</a></li>
            </ul>
            </div>
        </div>
        </div>
    </div>
    <div class="py-1">
        <div class="container">
        <div class="row">
            <div class="col">
            <!-- Copyright -->
            <p class="mb-3 mb-md-0 font-size-xxs text-white text-center">
                2021 All rights reserved
            </p>
        </div>
        </div>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>
  var today = new Date();
  var inpYear = document.getElementById("year");

  var yyyy = today.getFullYear();;
  html = '';

  for (var i = 0; i < 50; i++, yyyy--) {
      html = html + '<option>' + yyyy + '</option>';
  }

  inpYear.innerHTML=html;

</script>

</html>
