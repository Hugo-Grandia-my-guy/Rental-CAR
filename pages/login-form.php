<?php
require "includes/header.php";

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

    <main>
        <form action="/actions/login.php" method="post" class="account-form">
            <h2>Log in</h2>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="message"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="message"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <label for="email">Uw e-mail</label>
            <input type="email" name="email" id="email"
                   placeholder="johndoe@gmail.com"
                   value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>"
                   required autofocus>
            <label for="password">Uw wachtwoord</label>
            <input type="password" name="password" id="password"
                   placeholder="Uw wachtwoord" required>

            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <input type="submit" value="Log in" class="button-primary">
        </form>
    </main>

<?php require "includes/footer.php"; ?>