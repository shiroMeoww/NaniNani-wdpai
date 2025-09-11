<?php
  require_once "./component/db.php";
  /** @var PDO $pdo */
  global $pdo;
  $query = $pdo->prepare("select id from \"student\" where \"userUid\" = ?");
  $query->execute([$_SESSION["uid"]]);
  $res = $query->fetch(PDO::FETCH_ASSOC);
  if ($res) {
    $id = $res["id"];
  }
  if (array_key_exists("about", $_POST)) {
    $query = $pdo->prepare("update \"user\" set description = ? where uid = ?");
    $query->execute([$_POST["about"], $_SESSION["uid"]]);
  }
  $query = $pdo->prepare("select * from \"user\" where uid = ?");
  $query->execute([$_SESSION["uid"]]);
  $res = $query->fetch(PDO::FETCH_ASSOC);
?>
<main>
  <div class="column">
    <div class="section profile">
      <div class="avatar-wrap">
        <div class="avatar-circle">
          <span class="ms big-human red-icon">account_circle</span>
        </div>
      </div>
      <label for="aboutMe" class="visually-hidden">O mnie</label>
      <form action="dashboard.php" method="post">
        <textarea
          id="aboutMe"
          name="about"
          class="about-me-input"
          placeholder="O mnie..."
          spellcheck="false"
          rows="4"
          aria-label="Pole edycji: O mnie"
        ><?php
            echo $res["description"]
        ?></textarea>
        <button type="submit" class="big-button" style="margin-top:14px">
            Zapisz <span class="ms">check</span>
       </button>
      </form>
    </div>
  </div>
  <?php
    if ($id) {
      ?>
      <div class="column">
        <div class="section progress-section">
          <h2 class="section-title">Twój progress:</h2>
          <div class="progress-row">
            <div class="level">N3</div>
            <div class="progress-bar">
              <div class="progress-fill" style="width:60%"></div>
            </div>
          </div>
          <button class="big-button"onclick="location.href='progress.php'">
            Dalej <span class="ms">arrow_forward</span>
          </button>
        </div>
        <div class="section announcements-section">
          <div class="card-icon">
            <svg class="icon-star-shine" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 -960 960 960" aria-hidden="true" focusable="false">
              <path d="M852-212 732-332l56-56 120 120-56 56ZM708-692l-56-56 120-120 56 56-120 120Zm-456 0L132-812l56-56 120 120-56 56ZM108-212l-56-56 120-120 56 56-120 120Zm246-75 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-120l65-281L80-590l288-25 112-265 112 265 288 25-218 189 65 281-247-149-247 149Zm247-361Z"/>
            </svg>
          </div>
          <h2 class="section-title center white">Ogłoszenia</h2>
          <button class="big-button light" onclick="location.href='announcements.php'">
            Dalej <span class="ms">arrow_forward</span>
          </button>
        </div>
      </div>
      <?php
    }
  ?>
  <div class="column">
    <?php
      if ($id) {
        ?>
        <div class="section homework-section">
          <div class="card-icon">
            <span class="ms white-icon big-book">menu_book</span>
          </div>
          <h2 class="section-title center">Zadania domowe</h2>
          <button class="big-button" onclick="location.href='homework.php'">
            Dalej <span class="ms">arrow_forward</span>
          </button>
        </div>
        <?php
      }
    ?>
    <div class="section calendar-section">
      <div class="card-icon">
        <span class="ms white-icon big-calendar">calendar_month</span>
      </div>
      <h2 class="section-title center white">Kalendarz</h2>
      <button class="big-button light" onclick="location.href='calendar.php'">
        Dalej <span class="ms">arrow_forward</span>
      </button>
    </div>
  </div>
</main>
