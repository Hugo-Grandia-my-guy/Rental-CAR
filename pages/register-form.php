<?php
require "includes/header.php";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$emailValue = $_SESSION['email'] ?? '';
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message'], $_SESSION['email']);
?>


<main>
    <form action="/actions/register.php" method="post" class="account-form">
        <h2>Maak een account aan</h2>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <label for="email">Uw e-mail</label>
        <input type="email" name="email" id="email" placeholder="johndoe@gmail.com" value="<?= htmlspecialchars($emailValue) ?>" required autofocus>

        <label for="password">Uw wachtwoord</label>
        <input type="password" name="password" id="password" placeholder="Uw wachtwoord" required>

        <label for="confirm-password">Herhaal wachtwoord</label>
        <input type="password" name="confirm-password" id="confirm-password" placeholder="Uw wachtwoord" required>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <input type="submit" value="Maak account aan" class="button-primary">
    </form>
</main>
<?php require "includes/footer.php"; ?>
