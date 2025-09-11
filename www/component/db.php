<?php
  global $pdo;
  $pdo = new PDO("pgsql:host=db;port=5432;dbname=postgres;", "postgres", $_ENV["POSTGRES_PASSWORD"]);
?>
