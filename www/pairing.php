<?php
require_once "./component/Bootstrap.php";

$studentRepository = new StudentRepository();
$groupRepository = new GroupRepository();

$studentId = $studentRepository->getStudentId((string)($_SESSION['uid'] ?? ''));
if ($studentId === null) {
    die();
}

if (!empty($_POST['uidIn'])) {
    $groupRepository->joinGroup($studentId, (string)$_POST['uidIn']);
}

if (!empty($_POST['uidOut'])) {
    $groupRepository->leaveGroup($studentId, (string)$_POST['uidOut']);
}

$groups = $groupRepository->getAllGroups();
foreach ($groups as $index => $group) {
    $groupUid = (string)$group['uid'];
    $groups[$index]['memberCount'] = $groupRepository->getMemberCount($groupUid);
    $groups[$index]['memberNames'] = $groupRepository->getMemberNames($groupUid);
    $groups[$index]['isMember'] = $groupRepository->isMember($studentId, $groupUid);
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
      <?php foreach ($groups as $group): ?>
          <article class="group-card">
            <div class="info">
              <div class="top">
                <h2><?php echo View::escape((string)$group['name']); ?></h2>
                <span class="badge level-n<?php echo View::escape((string)$group['level']); ?>">N<?php echo View::escape((string)$group['level']); ?></span>
              </div>
              <p class="desc"><?php echo View::escape((string)$group['description']); ?></p>
              <div class="meta">
                <span class="chip soft">
                  Członkowie:
                  <strong><?php echo View::escape((string)$group['memberCount']); ?></strong>
                </span>
                <?php if ($group['isMember']): ?>
                  <span class="chip status">Jesteś w tej grupie</span>
                <?php endif; ?>
              </div>

              <?php if (!empty($group['memberNames'])): ?>
                <div class="member-list">
                  <span class="member-label">Uczestnicy:</span>
                  <ul>
                    <?php foreach ($group['memberNames'] as $memberName): ?>
                      <li><?php echo View::escape((string)$memberName); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <div class="actions">
                <form action="pairing.php" method="post">
                  <?php if (!$group['isMember']): ?>
                      <button type="submit" name="uidIn" value="<?php echo View::escape((string)$group['uid']); ?>" class="btn-primary"><span class="ms">group_add</span>Zapisz się</button>
                  <?php else: ?>
                      <button type="submit" name="uidOut" value="<?php echo View::escape((string)$group['uid']); ?>" class="btn-off"><span class="ms">group_off</span>Wypisz się</button>
                  <?php endif; ?>
                </form>
              </div>
            </div>
          </article>
      <?php endforeach; ?>
    </section>
  </div>
</main>
