<main class="gradient">
  <div class="login-box">
    <div class="headline">
      <h1>WITAJ PONOWNIE</h1>
      <?php
        if ($bad) {
          echo "<p class=\"incorrect\">Błędne dane logowania</p>";
        } else {
          echo "<p>Wprowadź swoje dane</p>";
        }
      ?>
    </div>
    <form action="login.php" method="post">
      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="example@gmail.com" />
      </div>
      <div class="input-group">
        <label for="password">Hasło</label>
        <input type="password" name="password" placeholder="********" />
      </div>
      <button type="submit" class="login-btn">Zaloguj</button>
      <a href="register.php" class="register-btn">Zarejestruj</a>
    </form>
  </div>
</main>
