<main class="gradient">
  <div class="register-box">
    <div class="headline">
      <h1>WITAJ</h1>
      <?php
        if ($bad) {
          echo "<p class=\"incorrect\">Błędne dane rejestracji</p>";
        } else {
          echo "<p>Wprowadź swoje dane</p>";
        }
      ?>
    </div>

    <form action="register.php" method="post">
      <div class="input-group">
        <label for="firstName">Imię</label>
        <input type="text" name="name" placeholder="Jan" />
      </div>

      <div class="input-group">
        <label for="lastName">Nazwisko</label>
        <input type="text" name="surname" placeholder="Kowalski" />
      </div>

      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="example@gmail.com" />
      </div>

      <div class="input-group">
        <label for="password">Hasło</label>
        <input type="password" name="password" placeholder="********" />
      </div>

      <button class="register-btn" type="submit">Zarejestruj</button>
      <a href="login.php" class="login-btn" role="button" aria-label="Przejdź do logowania">Zaloguj</a>
    </form>
  </div>
</main>
