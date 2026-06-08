<?php

declare(strict_types=1);

class FrontController
{
    private AuthService $authService;
    private UserRepository $userRepository;
    private StudentRepository $studentRepository;
    private GroupRepository $groupRepository;
    private NoteRepository $noteRepository;
    private LessonRepository $lessonRepository;
    private LevelService $levelService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->userRepository = new UserRepository();
        $this->studentRepository = new StudentRepository();
        $this->groupRepository = new GroupRepository();
        $this->noteRepository = new NoteRepository();
        $this->lessonRepository = new LessonRepository();
        $this->levelService = new LevelService();
    }

    public function handle(string $scriptName): array
    {
        $params = [
            'bad' => false,
            'pageTitle' => $this->getPageTitle($scriptName),
        ];

        $params = array_merge($params, $this->loadNavState());

        switch ($scriptName) {
            case '/login.php':
                return array_merge($params, $this->handleLogin());
            case '/register.php':
                return array_merge($params, $this->handleRegister());
            case '/logout.php':
                $this->handleLogout();
                break;
            case '/dashboard.php':
                return array_merge($params, $this->handleDashboard());
            case '/notes.php':
                return array_merge($params, $this->handleNotes());
            case '/pairing.php':
                return array_merge($params, $this->handlePairing());
            case '/calendar.php':
                return array_merge($params, $this->handleCalendar());
            case '/level.php':
                return array_merge($params, $this->handleLevel());
            case '/teachers.php':
                break;
            case '/index.php':
                break;
            default:
                if (!$this->isAuthenticated()) {
                    $this->redirect('/login.php');
                }
                break;
        }

        return $params;
    }

    private function loadNavState(): array
    {
        $userUid = (string)($_SESSION['uid'] ?? '');
        if ($userUid === '') {
            return [
                'studentId' => null,
                'teacherId' => null,
            ];
        }

        return [
            'studentId' => $this->studentRepository->getStudentId($userUid),
            'teacherId' => $this->studentRepository->getTeacherId($userUid),
        ];
    }

    private function handleLogin(): array
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/index.php');
        }

        $params = ['bad' => false];
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $userUid = $this->authService->authenticate($_POST['email'], $_POST['password']);
            if ($userUid !== null) {
                $_SESSION['uid'] = $userUid;
                $this->redirect('/dashboard.php');
            }
            $params['bad'] = true;
        }

        return $params;
    }

    private function handleRegister(): array
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/index.php');
        }

        $params = ['bad' => false];
        if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['name']) && !empty($_POST['surname'])) {
            $result = $this->authService->register($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['password']);
            if ($result) {
                $this->redirect('/login.php');
            }
            $params['bad'] = true;
        }

        return $params;
    }

    private function handleLogout(): void
    {
        $this->authService->logout();
        $this->redirect('/index.php');
    }

    private function handleDashboard(): array
    {
        $userUid = (string)($_SESSION['uid'] ?? '');
        $studentId = $this->studentRepository->getStudentId($userUid);

        if (array_key_exists('about', $_POST)) {
            $this->userRepository->updateDescription($userUid, $_POST['about'] ?? null);
        }

        $user = $this->userRepository->findByUid($userUid);

        return [
            'description' => $user['description'] ?? '',
            'studentId' => $studentId,
        ];
    }

    private function handleNotes(): array
    {
        $userUid = (string)($_SESSION['uid'] ?? '');
        $studentId = $this->studentRepository->getStudentId($userUid);
        if ($studentId === null) {
            $this->redirect('/index.php');
        }

        if (!empty($_POST['content'])) {
            $this->noteRepository->addNote($studentId, trim($_POST['content']));
        }

        return [
            'notes' => $this->noteRepository->getNotes($studentId),
            'studentId' => $studentId,
        ];
    }

    private function handlePairing(): array
    {
        $userUid = (string)($_SESSION['uid'] ?? '');
        $studentId = $this->studentRepository->getStudentId($userUid);
        if ($studentId === null) {
            $this->redirect('/index.php');
        }

        if (!empty($_POST['uidIn'])) {
            $this->groupRepository->joinGroup($studentId, (string)$_POST['uidIn']);
        }

        if (!empty($_POST['uidOut'])) {
            $this->groupRepository->leaveGroup($studentId, (string)$_POST['uidOut']);
        }

        $groups = $this->groupRepository->getAllGroups();
        foreach ($groups as $index => $group) {
            $groupUid = (string)$group['uid'];
            $groups[$index]['memberCount'] = $this->groupRepository->getMemberCount($groupUid);
            $groups[$index]['memberNames'] = $this->groupRepository->getMemberNames($groupUid);
            $groups[$index]['isMember'] = $this->groupRepository->isMember($studentId, $groupUid);
        }

        return [
            'groups' => $groups,
            'studentId' => $studentId,
        ];
    }

    private function handleCalendar(): array
    {
        $userUid = (string)($_SESSION['uid'] ?? '');
        $studentId = $this->studentRepository->getStudentId($userUid);
        $teacherId = $this->studentRepository->getTeacherId($userUid);
        if ($studentId === null && $teacherId === null) {
            $this->redirect('/index.php');
        }

        if (!empty($_POST['uid'])) {
            $this->lessonRepository->deleteLesson((string)$_POST['uid']);
        }

        $lessons = [];
        if ($studentId !== null) {
            $lessons = array_merge($lessons, $this->lessonRepository->getLessonsForStudent($studentId));
        }
        if ($teacherId !== null) {
            $lessons = array_merge($lessons, $this->lessonRepository->getLessonsForTeacher($teacherId));
        }

        $lessonEntries = [];
        foreach ($lessons as $lesson) {
            $date = substr((string)$lesson['datetime'], 0, 10);
            $lessonEntries[$date] = [
                'uid' => $lesson['uid'],
                'title' => $lesson['name'] . ': ' . substr((string)$lesson['datetime'], 11, 5) . ' (' . $lesson['duration'] . 'min)',
                'link' => 'https://meet.jit.si/random',
            ];
        }

        return [
            'lessonEntries' => $lessonEntries,
            'studentId' => $studentId,
            'teacherId' => $teacherId,
        ];
    }

    private function handleLevel(): array
    {
        $page = $_GET['page'] ?? '';

        return [
            'page' => $page,
            'pagePath' => $this->levelService->getPagePath($page),
        ];
    }

    private function isAuthenticated(): bool
    {
        return !empty($_SESSION['uid']);
    }

    private function redirect(string $location): void
    {
        header('Location: ' . $location, true, 301);
        exit;
    }

    private function getPageTitle(string $scriptName): string
    {
        $titles = [
            '/index.php' => 'Nauka japońskiego online',
            '/login.php' => 'Logowanie',
            '/register.php' => 'Rejestracja',
            '/dashboard.php' => 'Moje konto',
            '/teachers.php' => 'Nauczyciele',
        ];
        return $titles[$scriptName] ?? 'NaniNani';
    }
}
