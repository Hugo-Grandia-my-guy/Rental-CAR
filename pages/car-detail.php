<?php require "includes/header.php"; ?>
<?php require "database/connection.php"; ?>

<?php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Geen auto gekozen.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM auto WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    $car = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database fout");
}

if (!$car) {
    die("Auto niet gevonden.");
}

function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>

    <main class="car-detail">
        <div class="grid">

            <div class="row">
                <div class="advertorial">
                    <h2><?= e($car['naam']) ?></h2>

                    <p>
                        <?= e($car['kort-beschrijving'] ?? 'Geen beschrijving beschikbaar.') ?>
                    </p>

                    <img src="assets/images/products/<?= e($car['image']) ?>" alt="">
                    <img src="/assets/images/header-circle-background.svg" alt="" class="background-header-element">
                </div>
            </div>

            <div class="row white-background">
                <h2><?= e($car['naam']) ?></h2>

                <div class="rating">
                    <div class="stars">
                        <span class="<?= e($car['star'] ?? '') ?>"></span>
                    </div>
                    <span><?= (int)($car['reviews'] ?? 0) ?>+ Reviews</span>
                </div>

                <p><?= e($car['details'] ?? '') ?></p>

                <div class="car-type">

                    <div class="grid">
                        <div class="row">
                            <span class="accent-color">Type Car</span>
                            <span><?= e($car['type']) ?></span>
                        </div>
                        <div class="row">
                            <span class="accent-color">Capacity</span>
                            <span><?= (int)$car['capacity'] ?> person</span>
                        </div>
                    </div>

                    <div class="grid">
                        <div class="row">
                            <span class="accent-color">Steering</span>
                            <span><?= e($car['steering']) ?></span>
                        </div>
                        <div class="row">
                            <span class="accent-color">Gasoline</span>
                            <span><?= (int)$car['gasoline'] ?>L</span>
                        </div>
                    </div>

                    <div class="call-to-action">
                        <div class="row">
                        <span class="font-weight-bold">
                            €<?= number_format($car['kosten'], 2) ?>
                        </span> / dag
                        </div>

                        <div class="row">
                            <a href="/betaal?id=<?= (int)$car['id'] ?>" class="button-primary rent-button">
                                Huur nu
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

<?php require "includes/footer.php"; ?>