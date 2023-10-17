<?php
include 'connect.php';
$id = $_GET['id'];


$pdoStatement = $pdo->prepare("
SELECT `id`, `model`,`image`, `color`, `year`, `price`, `company` FROM `cars` WHERE id=$id
");

if (!$pdoStatement->execute()) {
    echo 'Error';
}

$data = $pdoStatement->fetch();

if (isset($_POST['submit'])) {
    $color = $_POST['color'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $year = $_POST['year'];
    $company = $_POST['company'];

    // img
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $errors = array();
        $file_name = $image['name'];
        $file_size = $image['size'];
        $file_tmp = $image['tmp_name'];
        $file_type = $image['type'];
        $tmp = explode('.', $file_name);
        $file_ext = end($tmp);

        $extensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be excately 2 MB';
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/" . $file_name);
        } else {
            print_r($errors);
        }
    }
    //img    



    $pdoStatementPost = $pdo->prepare("
    UPDATE `cars` SET `model`=:model,`image`=:image, `color`=:color,`year`=:year,`price`=:price,`company`=:company WHERE id=:id
    ");

    $pdoStatementPost->bindParam('id', $id);
    $pdoStatementPost->bindParam('color', $color);
    $pdoStatementPost->bindParam('model', $model);
    $pdoStatementPost->bindParam('price', $price);
    $pdoStatementPost->bindParam('year', $year);
    $pdoStatementPost->bindParam('company', $company);
    $pdoStatementPost->bindParam('image', $file_name);


    if ($pdoStatementPost->execute()) {
        header('location: index.php');
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <title>Table</title>
</head>

<body>

    <div class="container mt-3">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Edit User</h1>
            </div>
            <div class="col-12 text-end mb-3">
                <a class="btn btn-success btn-sm" href="index.php">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="col-12">
                <form action="edit.php?id=<?= $data['id'] ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="model">Model</label>
                        <input class="form-control" value="<?= $data['model'] ?>" type="text" id="model" name="model" placeholder="Enter model">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="image">Image</label>
                        <input class="form-control" value="<?=$data['image']?>" type="file" id="image" name="image">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="color">Color</label>
                        <input class="form-control" value="<?= $data['color'] ?>" type="text" id="color" name="color" placeholder="Enter color">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="year">Year</label>
                        <input class="form-control" value="<?= $data['year'] ?>" type="number" id="year" name="year" placeholder="Enter year">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="price">Price</label>
                        <input class="form-control" value="<?= $data['price'] ?>" type="number" id="price" name="price" placeholder="Enter price">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="company">Company</label>
                        <input class="form-control" value="<?= $data['company'] ?>" type="text" id="company" name="company" placeholder="Enter company">
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>