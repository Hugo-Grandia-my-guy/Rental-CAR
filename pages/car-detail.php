<?php require "includes/header.php"; ?>
<?php require "database/connection.php"; ?>

<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Geen auto gekozen.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM auto WHERE id = :id");
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();

    $car = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

if (!$car) {
    die("Auto niet gevonden.");
}
?>

    <main class="car-detail">
        <div class="grid">

            <div class="row">
                <div class="advertorial">
                    <h2><?= htmlspecialchars($car['naam']) ?></h2>

                    <p>
                        <?= htmlspecialchars($car['kort-beschrijving'] ?? 'Geen beschrijving beschikbaar.') ?>
                    </p>

                    <img src="assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">
                    <img src="/assets/images/header-circle-background.svg" alt="" class="background-header-element">
                </div>
            </div>

            <div class="row white-background">
                <h2><?= htmlspecialchars($car['naam']) ?></h2>

                <div class="rating">
                    <span class="<?= htmlspecialchars($car['star'] ?? '') ?>"></span>
                    <span><?= htmlspecialchars($car['reviews'] ?? 0) ?>+ Reviews</span>
                </div>

                <p>
                    <?= htmlspecialchars($car['details'] ?? 'Geen beschrijving beschikbaar.') ?>
                </p>

                <div class="car-type">

                    <div class="grid">
                        <div class="row">
                            <span class="accent-color">Type Car</span>
                            <span><?= htmlspecialchars($car['type']) ?></span>
                        </div>
                        <div class="row">
                            <span class="accent-color">Capacity</span>
                            <span><?= htmlspecialchars($car['capacity']) ?> person</span>
                        </div>
                    </div>

                    <div class="grid">
                        <div class="row">
                            <span class="accent-color">Steering</span>
                            <span><?= htmlspecialchars($car['steering']) ?></span>
                        </div>
                        <div class="row">
                            <span class="accent-color">Gasoline</span>
                            <span><?= htmlspecialchars($car['gasoline']) ?>L</span>
                        </div>
                    </div>

                    <div class="call-to-action">
                        <div class="row">
                        <span class="font-weight-bold">
                            €<?= htmlspecialchars($car['kosten']) ?>
                        </span> / dag
                        </div>

                        <div class="row">
                            <a href="#" class="button-primary">Huur nu</a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

<?php require "includes/footer.php"; ?>