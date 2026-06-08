<?php
// Controller passes variables: $notes and $studentId
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
      <?php if (count($notes) === 0): ?>
          <div class="empty-state">
            <span class="ms">sticky_note_2</span>
            Brak notatek. Dodaj pierwszą powyżej.
          </div>
      <?php else: ?>
          <div class="cards">
          <?php foreach ($notes as $card): ?>
              <div class="note-card"><?php echo View::escape((string)$card['content']); ?></div>
          <?php endforeach; ?>
          </div>
      <?php endif; ?>
    </section>
  </div>
</main>
