    <main class="teacher-dashboard">
        <div class="column">
            <div class="section profile">
                <div class="avatar-wrap">
                    <div class="avatar-circle">
                        <span class="ms big-human red-icon">account_circle</span>
                    </div>
                </div>

                <label for="tAboutMe" class="visually-hidden">O mnie</label>
                <textarea
                    id="tAboutMe"
                    class="about-me-input"
                    placeholder="O mnie (np. specjalizacje, sposób prowadzenia zajęć, dostępne sloty)…"
                    spellcheck="false"
                    rows="6"
                    aria-label="Pole edycji: O mnie"
                ></textarea>

                <button type="submit" class="big-button" style="margin-top:14px">
                    Zapisz <span class="ms">check</span>
                </button>
            </div>
        </div>

        <div class="column">
    <div class="section calendar-section">
        <div class="card-icon">
            <span class="ms white-icon big-calendar">calendar_month</span>
        </div>
        <h2 class="section-title center white">Kalendarz</h2>
        <button class="big-button light" onclick="location.href='calendar.php'">
            Otwórz kalendarz <span class="ms">arrow_forward</span>
        </button>
    </div>
    
    <a href="materials.php" class="additional-tile" style="text-decoration: none; color: inherit;">
        <span class="ms">school</span>
        <h3>Materiały dydaktyczne</h3>
        <p>Zarządzaj swoimi materiałami, planami lekcji i zasobami edukacyjnymi</p>
    </a>
</div>
    </main>