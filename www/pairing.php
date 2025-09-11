<?php
  require_once "./component/db.php";
  /** @var PDO $pdo */
  global $pdo;
  $query = $pdo->prepare("select id from \"student\" where \"userUid\" = ?");
  $query->execute([$_SESSION["uid"]]);
  $res = $query->fetch(PDO::FETCH_ASSOC);
  if ($res) {
    $id = $res["id"];
  } else {
    die();
  }
  if ($_POST["uidIn"]) {
    $query = $pdo->prepare("insert into \"studentGroup\" (\"studentId\", \"groupUid\") values (?, ?)");
    try {
      $query->execute([$id, $_POST["uidIn"]]);
    } catch (PDOException $e) {}
  }
  if ($_POST["uidOut"]) {
    $query = $pdo->prepare("delete from \"studentGroup\" where \"studentId\" = ? and \"groupUid\" = ?");
    $query->execute([$id, $_POST["uidOut"]]);
  }
?>
<main>
  <section class="level-hero">
    <div class="level-title">
      <h1>Dołącz do grupy nauki</h1>
    </div>
  </section>

  <div class="groups-page">
    <section class="cards">
      <?php
        $query = $pdo->prepare("select * from \"group\"");
        $query->execute();
        $res = $query->fetchAll();
        foreach($res as $group) {
          ?>
          <article class="group-card">
            <div class="info">
              <div class="top">
                <h2>
                  <?php
                    echo $group["name"];
                  ?>
                </h2>
                <?php
                  echo "<span class=\"badge level-n" . $group["level"] . "\">N" . $group["level"] . "</span>";
                ?>
              </div>
              <p class="desc">
                <?php
                  echo $group["description"];
                ?>
              </p>
              <div class="meta">
                <span class="chip soft">
                  Czałonkowie:
                  <strong>
                    <?php
                      $query = $pdo->prepare("select count(1) from \"studentGroup\" where \"groupUid\" = ?");
                      $query->execute([$group["uid"]]);
                      $res2 = $query->fetch(PDO::FETCH_ASSOC);
                      echo $res2["count"];
                    ?>
                  </strong>
                </span>
              </div>
              <div class="actions">
                <form action="pairing.php" method="post">
                  <?php
                    $query = $pdo->prepare("select count(1) from \"studentGroup\" where \"groupUid\" = ? and \"studentId\" = ?");
                    $query->execute([$group["uid"], $id]);
                    $res2 = $query->fetch(PDO::FETCH_ASSOC);
                    if ($res2["count"] == 0) {
                      echo "<button type=\"submit\" name=\"uidIn\" value=\"" . $group["uid"] . "\" class=\"btn-primary\"><span class=\"ms\">group_add</span>Zapisz się</button>";
                    } else {
                      echo "<button type=\"submit\" name=\"uidOut\" value=\"" . $group["uid"] . "\" class=\"btn-off\"><span class=\"ms\">group_off</span>Wypisz się</button>";
                    }
                  ?>
                </form>
              </div>
            </div>
          </article>
          <?php
        }
      ?>
    </section>
  </div>
</main>
