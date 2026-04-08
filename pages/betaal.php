<?php require "includes/header.php"; ?>
<?php
require "database/connection.php";

if (!isset($_SESSION['id'])) {
    header("Location: /login-form");
    exit;
}

$carId = $_GET['id'] ?? null;

if (!$carId) {
    die("Geen auto geselecteerd");
}

try {
    $stmt = $pdo->prepare("
        SELECT id, naam, kosten, image 
        FROM auto 
        WHERE id = :id
    ");
    $stmt->execute([':id' => $carId]);

    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Auto niet gevonden");
    }

} catch (PDOException $e) {
    die("Database fout");
}
?>

<main class="payment-page">
    <div class="payment-container">

        <h2>Betaling</h2>

        <div class="car-summary">
            <img src="/assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">
            <div>
                <h3><?= htmlspecialchars($car['naam']) ?></h3>
                <p>€<?= htmlspecialchars($car['kosten']) ?> / dag</p>
            </div>
        </div>

        <form action="/actions/pay.php" method="POST" class="payment-form">

            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

            <label>Aantal dagen</label>
            <input type="number" name="days" min="1" value="1" required>

            <label>Naam op kaart</label>
            <input type="text" name="name" required>

            <label>Kaartnummer</label>
            <input type="text" name="card" placeholder="1234 5678 9012 3456" required>

            <label>Vervaldatum</label>
            <input type="text" name="expiry" placeholder="MM/YY" required>

            <label>CVC</label>
            <input type="text" name="cvc" required>

            <button type="submit" class="button-primary">
                Betaal nu
            </button>
        </form>

    </div>
</main>

<?php require "includes/footer.php"; ?>
