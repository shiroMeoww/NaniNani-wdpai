     <main>
   <section class="level-hero">
      <nav class="level-nav hw-filters">
        <a href="#" class="active" data-filter="all" data-filter-type="all">Wszystkie</a>
        <a href="#" data-filter="todo" data-filter-type="status">Do zrobienia</a>
        <a href="#" data-filter="doing" data-filter-type="status">W toku</a>
        <a href="#" data-filter="done" data-filter-type="status">Zrobione</a>
        <a href="#" data-filter="N5" data-filter-type="level">N5</a>
        <a href="#" data-filter="N4" data-filter-type="level">N4</a>
        <a href="#" data-filter="N3" data-filter-type="level">N3</a>
        <a href="#" data-filter="N2" data-filter-type="level">N2</a>
        <a href="#" data-filter="N1" data-filter-type="level">N1</a>
      </nav>
    </section>
    <section class="articles tasks-grid">
      <article class="article-card task-card" data-status="todo" data-level="N3">
        <div class="task-head">
          <span class="task-level tag tag-level">N3</span>
          <span class="task-status tag tag-status todo">Do zrobienia</span>
        </div>
        <h2 class="article-title">Czytanie: Krótki tekst o podróżach</h2>
        <p class="article-excerpt">
          Tekst + 6 pytań sprawdzających. Skup się na spójnikach i słownictwie transportowym.
        </p>
        <div class="task-meta">
          <span class="meta"><span class="ms">schedule</span> 15–20 min</span>
          <span class="dot"></span>
          <span class="meta"><span class="ms">event</span> Termin: 12.09</span>
        </div>
        <div class="article-actions">
          <a class="read-btn" href="#"><span class="ms">play_arrow</span> Start</a>
          <div class="progress-wrap"><div class="progress-bar"><div class="progress-fill" style="width: 0%"></div></div></div>
        </div>
      </article>
      <article class="article-card task-card" data-status="doing" data-level="N2">
        <div class="task-head">
          <span class="task-level tag tag-level">N2</span>
          <span class="task-status tag tag-status doing">W toku</span>
        </div>
        <h2 class="article-title">Gramatyka: 〜に違いない / 〜に相違ない</h2>
        <p class="article-excerpt">
          Ćwiczenia transformacyjne + dobieranie kolokacji. Zwróć uwagę na rejestr stylistyczny.
        </p>
        <div class="task-meta">
          <span class="meta"><span class="ms">schedule</span> 20–25 min</span>
          <span class="dot"></span>
          <span class="meta"><span class="ms">event</span> Termin: 14.09</span>
        </div>
        <div class="article-actions">
          <a class="read-btn" href="#"><span class="ms">play_arrow</span> Kontynuuj</a>
          <div class="progress-wrap"><div class="progress-bar"><div class="progress-fill" style="width: 45%"></div></div></div>
        </div>
      </article>
      <article class="article-card task-card" data-status="done" data-level="N5">
        <div class="task-head">
          <span class="task-level tag tag-level">N5</span>
          <span class="task-status tag tag-status done">Zrobione</span>
        </div>
        <h2 class="article-title">Słownictwo: Liczby 1–100</h2>
        <p class="article-excerpt">
          Powtórka liczb i wyjątków: 300, 600, 800. Krótkie quizy na czas.
        </p>
        <div class="task-meta">
          <span class="meta"><span class="ms">schedule</span> 10–12 min</span>
          <span class="dot"></span>
          <span class="meta"><span class="ms">event</span> Zakończono</span>
        </div>
        <div class="article-actions">
          <a class="read-btn" href="#"><span class="ms">visibility</span> Podgląd</a>
          <div class="progress-wrap"><div class="progress-bar"><div class="progress-fill" style="width: 100%"></div></div></div>
        </div>
      </article>
      <article class="article-card task-card" data-status="todo" data-level="N1">
        <div class="task-head">
          <span class="task-level tag tag-level">N1</span>
          <span class="task-status tag tag-status todo">Do zrobienia</span>
        </div>
        <h2 class="article-title">Styl: 書き言葉 vs 話し言葉</h2>
        <p class="article-excerpt">
          Porównaj cechy stylu pisanego i mówionego. Uzupełnij przykłady i wskaż nominalizacje.
        </p>
        <div class="task-meta">
          <span class="meta"><span class="ms">schedule</span> 25–30 min</span>
          <span class="dot"></span>
          <span class="meta"><span class="ms">event</span> Termin: 16.09</span>
        </div>
        <div class="article-actions">
          <a class="read-btn" href="#"><span class="ms">play_arrow</span> Start</a>
          <div class="progress-wrap"><div class="progress-bar"><div class="progress-fill" style="width: 0%"></div></div></div>
        </div>
      </article>

      <!-- Pusta karta dodawcza -->
     <!--  <article class="article-card task-card add-card">
        <div class="add-inner">
          <span class="ms big">add</span>
          Dodaj nowe zadanie
        </div>
      </article>-->
    </section>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const filterLinks = document.querySelectorAll('.hw-filters a');
        const taskCards = document.querySelectorAll('.task-card');

        function applyFilter(type, value) {
          taskCards.forEach(card => {
            if (type === 'all') {
              card.style.display = '';
              return;
            }

            const cardValue = card.dataset[type]?.toLowerCase();
            card.style.display = cardValue === value ? '' : 'none';
          });
        }

        filterLinks.forEach(link => {
          link.addEventListener('click', event => {
            event.preventDefault();
            filterLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            const filterType = link.dataset.filterType;
            const filterValue = link.dataset.filter?.toLowerCase();
            applyFilter(filterType, filterValue);
          });
        });
      });
    </script>
