<?php
require_once "./component/Bootstrap.php";

$studentRepository = new StudentRepository();
$lessonRepository = new LessonRepository();
$teacherRepository = new TeacherRepository();

$studentId = $studentRepository->getStudentId((string)($_SESSION['uid'] ?? ''));

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Nieprawidłowa metoda żądania.']);
    exit;
}

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
