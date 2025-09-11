<main class="image">
  <div class="main">
    <?php
      if (is_numeric($_GET["page"])) {
    ?>
    <section class="level-hero">
      <nav class="level-nav">
        <?php
          for ($i = 1; $i <= 5; $i ++) {
            echo "<a href=\"/level.php?page=" . $i . "\"";
            if ($i == $_GET["page"]) {
              echo " class=\"active\"";
            }
            echo ">N" . $i . "</a>"; 
          }
        ?>
      </nav>
    </section>
    <?php
        require "./levels/n" . $_GET["page"] . ".php";
      } else {
        require "./levels/" . $_GET["page"] . ".php";
      }
    ?>
  </div>
</main>
