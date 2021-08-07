<?php
include "functions.php";

$pdo = new PDO ('mysql:host=db;port=3306;dbname=testdatabase','root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_POST['id'] ?? null;

if (!$id){
    redirect('index.php');
    exit;
}

$statement = $pdo -> prepare('DELETE FROM products WHERE id = :id');
$statement->bindValue(':id',$id);
$statement->execute();

redirect('index.php');