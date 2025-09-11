<?php
  session_start();
  switch ($_SERVER["SCRIPT_NAME"]) {
    case "/login.php":
      if ($_SESSION["uid"]) {
        header('Location: /index.php', true, 301);
        die();
      }
      if ($_POST["email"] && $_POST["password"]) {
        require_once "./component/db.php";
        /** @var PDO $pdo */
        global $pdo;
        $query = $pdo->prepare("select uid, password from \"user\" where email = ?");
        $query->execute([$_POST["email"]]);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
          if(password_verify($_POST["password"], $res["password"]) ) {
            $_SESSION["uid"] = $res["uid"];
            header('Location: /dashboard.php', true, 301);
            die();
          }
        }
        $bad = true;
      }
      break;
    case "/register.php":
      if ($_SESSION["uid"]) {
        header('Location: /index.php', true, 301);
        die();
      }
      if ($_POST["email"] && $_POST["password"] && $_POST["name"] && $_POST["surname"]) {
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        require_once "./component/db.php";
        /** @var PDO $pdo */
        global $pdo;
        try {
          $query = $pdo->prepare("insert into \"user\" (name, surname, email, password) values (?, ?, ?, ?)");
          $query->execute([$_POST["name"], $_POST["surname"], $_POST["email"], $password]);

          $query = $pdo->prepare("select uid from \"user\" where email = ?");
          $query->execute([$_POST["email"]]);
          $res = $query->fetch(PDO::FETCH_ASSOC);

          $query = $pdo->prepare("insert into \"student\" (\"userUid\") values (?)");
          $query->execute([$res["uid"]]);
          header('Location: /login.php', true, 301);
          die();
        } catch (PDOException) {}
        $bad = true;
      }
      break;
    case "/index.php":
      break;
    case "/logout.php":
      unset($_SESSION["uid"]);
    default:
      if (!$_SESSION["uid"]) {
        header('Location: /index.php', true, 301);
        die();
      }
      break;
  }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php
      $titles = array(
        "/index.php" => "Nauka japońskiego online"
      );
      echo $titles[$_SERVER["SCRIPT_NAME"]];
    ?>
  </title>
  <link rel="stylesheet" href="/style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" />
  <?php
    $css = substr($_SERVER["SCRIPT_NAME"], 0, -3) . "css";
    echo "<link rel=\"stylesheet\"\" href=\"" . $css . "\" />";
  ?>
</head>
<body>
  <?php
    switch ($_SERVER["SCRIPT_NAME"]) {
      case "/login.php":
      case "/register.php":
        break;
      default:
        require "./component/nav.php";
    }
    require "." . $_SERVER["SCRIPT_NAME"];
  ?>
</body>
<?php
  global $pdo;
  if ($pdo) {
    unset($pdo);
  }
?>
