<?php
?>
<header class="navbar">
  <div class="logo">NaniNani</div>
  <nav class="nav-links">
    <?php
      $pages = array();

      if (!$teacherId && !$studentId) {
        $pages["/teachers.php"] = "Nauczyciele";
      }

      if ($teacherId || $studentId) {
        $pages["/dashboard.php"] = "Strona Główna";
        $pages["/materials.php"] = "Materiały";
      }

      if ($studentId) {
        $pages["/teachers.php"] = "Nauczyciele";
        $pages["/pairing.php"] = "Wspólna Nauka";
        $pages["/notes.php"] = "Notatki";
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
      if (isset($_SESSION["uid"]) && $_SESSION["uid"]) {
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