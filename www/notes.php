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
  if ($_POST["content"]) {
    $query = $pdo->prepare("insert into \"note\" (\"studentId\", \"content\") values (?, ?)");
    $query->execute([$id, $_POST["content"]]);
  }
?>
<main>
  <section class="level-hero">
    <div class="level-title">
      <h1>Twoje notatki</h1>
    </div>
  </section>

  <div class="notes-page">
    <section class="note-composer">
      <form action="notes.php" method="post" class="composer-form" autocomplete="off">
        <textarea name="content" rows="5" placeholder="Zapisz szybką notatkę z lekcji, nowe słówka albo pomysł do powtórki..." required></textarea>
        <div class="composer-actions">
          <button type="confirm" class="btn-primary"><span class="ms">save</span> Zapisz</button>
        </div>
      </form>
    </section>

    <section class="notes-list">
      <?php
        $query = $pdo->prepare("select content from \"note\" where \"studentId\" = ? order by id desc");
        $query->execute([$id]);
        $res = $query->fetchAll();
        if (!count($res)) {
          ?>
          <div class="empty-state">
            <span class="ms">sticky_note_2</span>
            Brak notatek. Dodaj pierwszą powyżej.
          </div>
          <?php
        } else {
          ?>
          <div class="cards">
          <?php
            foreach ($res as $card) {
              ?>
              <div class="note-card">
              <?php
              echo $card["content"];
              ?>
              </div>
              <?php
            }
          ?>
          </div>
          <?php
        }
      ?>
    </section>
  </div>
</main>
