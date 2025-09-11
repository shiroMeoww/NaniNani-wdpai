<?php
  require_once "./component/db.php";
  /** @var PDO $pdo */
  global $pdo;
  $query = $pdo->prepare("select id from \"student\" where \"userUid\" = ?");
  $query->execute([$_SESSION["uid"]]);
  $res = $query->fetch(PDO::FETCH_ASSOC);
  if ($res) {
    $studentId = $res["id"];
  }
  $query = $pdo->prepare("select id from \"teacher\" where \"userUid\" = ?");
  $query->execute([$_SESSION["uid"]]);
  $res = $query->fetch(PDO::FETCH_ASSOC);
  if ($res) {
    $teacherId = $res["id"];
  }
?>
<header class="navbar">
  <div class="logo">NaniNani</div>
  <nav class="nav-links">
    <?php
      $pages = array(
        "/index.php" => "Nauczyciele",
      );
      if ($teacherId || $studentId) {
        $pages = array_merge(array(
          "/dashboard.php" => "Strona Główna",
        ), $pages, array(
          "/materials.php" => "Materiały",
        ));
      }
      if ($studentId) {
        $pages = array_merge($pages, array(
          "/pairing.php" => "Wspólna Nauka",
          "/notes.php" => "Notatki",
        ));
      }
      foreach ($pages as $key => $val) {
        echo "<a href=\"" . $key . "\"";
        if ($key == $_SERVER["SCRIPT_NAME"]) {
          echo " class=\"active\"";
        }
        echo ">" . $val . "</a>";
      }
    ?>
  </nav>
  <div class="nav-buttons">
    <?php
      if ($_SESSION["uid"]) {
        ?>
          <a href="dashboard.php" class="btn-account">
            <span class="ms">person</span>
            Moje konto
          </a>
          <a href="logout.php" class="btn-logout">Wyloguj</a>
        <?php
      } else {
        ?>
          <a href="login.php" class="btn-login">Zaloguj</a>
        <?php
      }
    ?>
  </div>
</header>
