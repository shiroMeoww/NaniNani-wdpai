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
  if (!$studentId && !$teacherId) {
    die();
  }
?>
<main>
  <div class="calendar-page">
    <div class="calendar-header">
      <button class="nav-btn" id="prevMonth"><span class="ms">chevron_left</span></button>
      <h1 id="monthYear">Wrzesień 2025</h1>
      <button class="nav-btn" id="nextMonth"><span class="ms">chevron_right</span></button>
    </div>

    <div class="calendar-grid">
      <div class="day-name">Pon</div>
      <div class="day-name">Wt</div>
      <div class="day-name">Śr</div>
      <div class="day-name">Czw</div>
      <div class="day-name">Pt</div>
      <div class="day-name">Sob</div>
      <div class="day-name">Ndz</div>
    </div>
  </div>
</main>
<?php
  print_r($_POST);
?>
<script>
document.lessons = {
  <?php
    $sql = "select uid, datetime, duration, name from \"lesson\" join \"lessonType\" on type = id where ";
    foreach (array(array("studentId", $studentId),  array("teacherId", $teacherId)) as $x) {
      if (!empty($x[1])) {
        $query = $pdo->prepare($sql . "\"" . $x[0] . "\" = ?");
        $query->execute([$x[1]]);
        $res = $query->fetchAll();
        foreach ($res as $lesson) {
          if ($_POST["uid"] && $_POST["uid"] == $lesson["uid"]) {
            $query = $pdo->prepare("delete from \"lesson\" where uid = ?");
            $query->execute([$_POST["uid"]]);
            continue;
          }
          echo "\"" . substr($lesson["datetime"], 0, 10) . "\": { uid: \"" . $lesson["uid"] . "\", title: \"" . $lesson["name"] . ": " . substr($lesson["datetime"], 11, 5) . " (" . $lesson["duration"] . "min)\", link: \"https://meet.jit.si/random\" },";
        }
      }
    }
  ?>
};
</script>
<script src="calendar.js"></script>

