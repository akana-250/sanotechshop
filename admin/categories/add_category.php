<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../../config/config.php";
require_once "../../includes/admin_check.php";


$message = "";


if(isset($_POST['save'])){


    $category_name = trim($_POST['category_name']);
    $description   = trim($_POST['description']);



    if(empty($category_name)){


        $message = "Category name is required";


    }else{


        // Check duplicate category

        $check = $conn->prepare(
            "SELECT id FROM categories WHERE category_name = ?"
        );

        $check->execute([
            $category_name
        ]);


        if($check->rowCount() > 0){


            $message = "Category already exists";


        }else{


            $insert = $conn->prepare(
                "INSERT INTO categories
                (category_name, description)
                VALUES (?, ?)"
            );


            $insert->execute([

                $category_name,
                $description

            ]);


            header("Location: index.php");
            exit();


        }


    }


}


?>


<!DOCTYPE html>
<html>

<head>

<title>Add Category - SANO TECH SHOP</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body>


<div class="container mt-4">


<h2>
Add Category
</h2>


<?php if($message!=""): ?>

<div class="alert alert-danger">

<?= $message ?>

</div>

<?php endif; ?>



<form method="POST">


<div class="mb-3">

<label>
Category Name
</label>

<input type="text"
name="category_name"
class="form-control"
required>

</div>



<div class="mb-3">

<label>
Description
</label>


<textarea name="description"
class="form-control"
rows="4"></textarea>


</div>



<button type="submit"
name="save"
class="btn btn-success">

Save Category

</button>



<a href="index.php"
class="btn btn-secondary">

Back

</a>


</form>


</div>


</body>

</html>