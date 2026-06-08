<?php
require_once "./component/Bootstrap.php";

$studentRepository = new StudentRepository();
$lessonRepository = new LessonRepository();
$teacherRepository = new TeacherRepository();

$studentId = $studentRepository->getStudentId((string)($_SESSION['uid'] ?? ''));
$teacherId = $studentRepository->getTeacherId((string)($_SESSION['uid'] ?? ''));
if ($studentId === null && $teacherId === null) {
    die();
}

$teachers = [];
if ($studentId !== null) {
    $teachers = $teacherRepository->getAllTeachers();
}

if (!empty($_POST['action']) && $_POST['action'] === 'create') {
    header('Content-Type: application/json; charset=utf-8');

    if ($studentId === null) {
        echo json_encode(['success' => false, 'error' => 'Tylko uczniowie mogą rezerwować lekcje.']);
        exit;
    }

    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $teacherSelection = (int)($_POST['teacherId'] ?? 0);

    if ($date === '' || $time === '' || $teacherSelection <= 0) {
        echo json_encode(['success' => false, 'error' => 'Wypełnij datę, godzinę i wybierz nauczyciela.']);
        exit;
    }

    if (!$teacherRepository->exists($teacherSelection)) {
        echo json_encode(['success' => false, 'error' => 'Zły wybór nauczyciela.']);
        exit;
    }

    $datetime = $date . ' ' . $time . ':00';
    if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $datetime)) {
        echo json_encode(['success' => false, 'error' => 'Nieprawidłowa data lub godzina.']);
        exit;
    }

    if ($lessonRepository->teacherHasLessonAt($teacherSelection, $datetime)) {
        echo json_encode(['success' => false, 'error' => 'Ten nauczyciel ma już lekcję o tej godzinie.']);
        exit;
    }

    if ($lessonRepository->studentHasLessonAt($studentId, $datetime)) {
        echo json_encode(['success' => false, 'error' => 'Masz już lekcję o tej godzinie.']);
        exit;
    }

    $lessonUid = $lessonRepository->createLesson($studentId, $teacherSelection, $datetime);
    $teacherName = $teacherRepository->getTeacherName($teacherSelection) ?? 'nauczyciel';

    if (ob_get_level() > 0) {
        ob_clean();
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'teacherName' => $teacherName,
        'lesson' => [
            'uid' => $lessonUid,
            'title' => 'Lekcja: ' . substr($datetime, 11, 5) . ' z ' . $teacherName,
            'link' => 'https://meet.jit.si/random',
        ],
    ]);
    exit;
}

if (!empty($_POST['uid'])) {
    $lessonRepository->deleteLesson((string)$_POST['uid']);
}

$lessons = [];
if ($studentId !== null) {
    $lessons = array_merge($lessons, $lessonRepository->getLessonsForStudent($studentId));
}
if ($teacherId !== null) {
    $lessons = array_merge($lessons, $lessonRepository->getLessonsForTeacher($teacherId));
}

$lessonEntries = [];
foreach ($lessons as $lesson) {
    $date = substr((string)$lesson['datetime'], 0, 10);
    if (!isset($lessonEntries[$date])) {
        $lessonEntries[$date] = [];
    }

    $time = substr((string)$lesson['datetime'], 11, 5);
    $counterpart = $lesson['teacherName'] ?? ($lesson['studentName'] ?? null);
    $roleLabel = isset($lesson['teacherName']) ? 'z ' : 'z ';
    $counterpartText = $counterpart ? ' ' . $roleLabel . $counterpart : '';

    $lessonEntries[$date][] = [
        'uid' => $lesson['uid'],
        'title' => $lesson['name'] . ': ' . $time . ' (' . $lesson['duration'] . 'min)' . $counterpartText,
        'link' => 'https://meet.jit.si/random',
    ];
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

    <div class="booking-modal hidden" id="bookingModal">
      <div class="booking-panel">
        <button class="close-modal" id="bookingClose">×</button>
        <h2>Rezerwacja lekcji</h2>
        <form id="bookingForm">
          <input type="hidden" name="action" value="create">
          <label>Wybrany dzień
            <input type="text" id="bookingDate" name="date" readonly>
          </label>
          <label>Godzina
            <input type="time" id="bookingTime" name="time" required>
          </label>
          <label>Nauczyciel
            <select id="teacherId" name="teacherId" required>
              <option value="">Wybierz nauczyciela</option>
            </select>
          </label>
          <?php if ($studentId !== null && empty($teachers)) : ?>
            <p class="booking-warning">Brak dostępnych nauczycieli. Skontaktuj się z administratorem.</p>
          <?php endif; ?>
          <div class="booking-error" id="bookingError"></div>
          <button type="submit" class="btn-book">Zarezerwuj lekcję</button>
        </form>
      </div>
    </div>
  </div>
</main>
<script>
document.lessons = <?php echo json_encode($lessonEntries, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
document.teachers = <?php echo json_encode($teachers, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
document.canBook = <?php echo json_encode($studentId !== null); ?>;
</script>
<script src="calendar.js"></script>

