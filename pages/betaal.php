<?php
session_start();
require "database/connection.php";

if (!isset($_SESSION['id'])) {
    header("Location: /login-form");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$carId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$carId) {
    die("Ongeldige auto ID");
}

try {
    $stmt = $pdo->prepare("
        SELECT id, naam, kosten, image 
        FROM auto 
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $carId]);

    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Auto niet gevonden");
    }

} catch (PDOException $e) {
    die("Database fout");
}

require "includes/header.php";
?>

    <main class="payment-page">
        <div class="payment-container">

            <h2>Betaling</h2>

            <div class="car-summary">
                <img src="/assets/images/products/<?= htmlspecialchars($car['image'], ENT_QUOTES, 'UTF-8') ?>" alt="">
                <div>
                    <h3><?= htmlspecialchars($car['naam'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>
                        €<span id="price" data-price="<?= htmlspecialchars($car['kosten'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= number_format($car['kosten'], 2) ?>
                        </span> / dag
                    </p>
                </div>
            </div>

            <form action="/actions/pay.php" method="POST" class="payment-form">

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                <label>Aantal dagen</label>
                <input type="number" name="days" id="days" min="1" value="1" required>

                <div class="total">
                    Totaal: €<span id="total"><?= htmlspecialchars($car['kosten'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <p style="opacity:0.6; font-size:14px;">
                    Dit is een demo betaling (geen echte kaart nodig)
                </p>

                <button type="submit" class="button-primary">
                    Betaal nu
                </button>
            </form>

        </div>
    </main>

    <script>
        const price = parseFloat(document.getElementById('price').innerText);
        const daysInput = document.getElementById('days');
        const totalEl = document.getElementById('total');

        daysInput.addEventListener('input', function () {
            const days = parseInt(this.value) || 1;
            totalEl.innerText = (price * days).toFixed(2);
        });
    </script>

<?php require "includes/footer.php"; ?>