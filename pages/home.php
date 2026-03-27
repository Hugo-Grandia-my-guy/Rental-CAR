<?php require "includes/header.php"; ?>
<?php require "database/connection.php"; ?>

<?php
try {
    $stmt = $pdo->query("SELECT * FROM auto LIMIT 12");
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Verzoekfout: " . $e->getMessage());
}
?>

    <header>
        <div class="advertorials">
            <div class="advertorial">
                <h2>Het platform om een auto te huren</h2>
                <p>Snel en eenvoudig een auto huren. Natuurlijk voor een lage prijs.</p>
                <a href="/car-detail" class="button-primary">Huur nu een auto</a>
                <img src="/assets/images/car-rent-header-image-1.WEBP" alt="">
                <img src="/assets/images/header-circle-background.svg" alt="" class="background-header-element">
            </div>
            <div class="advertorial">
                <h2>Wij verhuren ook bedrijfswagens</h2>
                <p>Voor een vaste lage prijs met prettig voordelen.</p>
                <a href="/car-detail" class="button-primary">Huur een bedrijfswagen</a>
                <img src="/assets/images/car-rent-header-image-2.WEBP" alt="">
                <img src="/assets/images/header-block-background.svg" alt="" class="background-header-element">
            </div>
        </div>
    </header>

    <main>
        <h2 class="section-title">Populaire auto's</h2>
        <div class="cars">
            <?php for ($i = 0; $i < 4 && $i < count($cars); $i++):
                $car = $cars[$i]; ?>
                <div class="car-details">
                    <div class="car-brand">
                        <h3><?= htmlspecialchars($car['naam']) ?></h3>
                        <div class="car-type">
                            <?= htmlspecialchars($car['type']) ?>
                        </div>
                    </div>

                    <img src="assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">

                    <div class="car-specification">
                        <span><img src="/assets/images/icons/gas-station.svg"> <?= htmlspecialchars($car['gasoline']) ?>l</span>
                        <span><img src="/assets/images/icons/car.svg"> <?= htmlspecialchars($car['steering']) ?></span>
                        <span><img src="/assets/images/icons/profile-2user.svg"> <?= htmlspecialchars($car['capacity']) ?> Personen</span>
                    </div>

                    <div class="rent-details">
                        <span><span class="font-weight-bold">€<?= htmlspecialchars($car['kosten']) ?></span> / dag</span>
                        <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <h2 class="section-title">Aanbevolen auto's</h2>
        <div class="cars">
            <?php for ($i = 4; $i < 12 && $i < count($cars); $i++):
                $car = $cars[$i]; ?>
                <div class="car-details">
                    <div class="car-brand">
                        <h3><?= htmlspecialchars($car['naam']) ?></h3>
                        <div class="car-type">
                            <?= htmlspecialchars($car['type']) ?>
                        </div>
                    </div>

                    <img src="assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">

                    <div class="car-specification">
                        <span><img src="/assets/images/icons/gas-station.svg"> <?= htmlspecialchars($car['gasoline']) ?>l</span>
                        <span><img src="/assets/images/icons/car.svg"> <?= htmlspecialchars($car['steering']) ?></span>
                        <span><img src="/assets/images/icons/profile-2user.svg"> <?= htmlspecialchars($car['capacity']) ?> Personen</span>
                    </div>

                    <div class="rent-details">
                        <span><span class="font-weight-bold">€<?= htmlspecialchars($car['kosten']) ?></span> / dag</span>
                        <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="show-more">
            <a class="button-primary" href="/ons-aanbod">Toon alle</a>
        </div>
    </main>

<?php require "includes/footer.php"; ?>