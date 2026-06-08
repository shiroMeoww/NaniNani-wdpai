<?php
// Controller passes variables: $page and $pagePath
?>
<main class="image">
  <div class="main">
    <section class="level-hero">
      <nav class="level-nav">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <a href="/level.php?page=<?php echo $i; ?>"<?php echo $page === (string)$i ? ' class="active"' : ''; ?>>N<?php echo $i; ?></a>
        <?php endfor; ?>
      </nav>
    </section>
    <?php
      if ($pagePath !== null) {
          require $pagePath;
      } else {
          echo '<div class="empty-state">Nieprawidłowy poziom.</div>';
      }
    ?>
  </div>
</main>
