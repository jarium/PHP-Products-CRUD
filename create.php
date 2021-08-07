<?php
include "functions.php";

$pdo = new PDO ('mysql:host=db;port=3306;dbname=testdatabase','root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors =[];

$title = '';
$price = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST ['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    if (!$title){
        $errors[] = 'Product title is required';
    }
    if (!$price){
        $errors[] = 'Product price is required';
    }
    if (!is_dir('images')){
        mkdir('images');
    }
    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;
        $imagepath = '';
        if ($image && $image['tmp_name']){
            $imagePath = 'images/'.randomString(8).'/'.$image['name'];
            mkdir(dirname($imagePath));

            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date) 
                      VALUES (:title, :image, :description, :price, :date)");

        $statement->bindValue('title', $title);
        $statement->bindValue('image', $imagePath);
        $statement->bindValue('description', $description);
        $statement->bindValue('price', $price);
        $statement->bindValue('date', $date);
        $statement->execute();
        header('Location: index.php');
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Products CRUD</title>
</head>
<body>

<p>
    <a href="index.php" class="btn btn-secondary">Go Back to Products </a>
</p>

<h1>Create New Product</h1>

<?php if (!empty($errors)): ?>
 <div class = "alert alert-danger">
    <?php foreach ($errors as $error): ?>
      <div><?php echo $error ?> </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form action="" method="post" enctype = "multipart/form-data">
    <div class="form-group">
        <label>Product Image</label>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label>Product Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
    </div>
    <div class="form-group">
        <label">Product Description</label>
        <textarea class="form-control" name="description"><?php echo $description ?></textarea>
    </div>
    <div class="form-group">
        <label>Product Price</label>
        <input type="number" step=".01" name="price" value ="<?php echo $price ?>" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</body>
</html>