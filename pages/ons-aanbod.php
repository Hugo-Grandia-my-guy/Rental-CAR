<?php require "includes/header.php" ?>
<?php require "database/connection.php" ?>

<?php
$limit = 5;

$stmt = $conn->query("SELECT * FROM auto LIMIT $limit");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalStmt = $conn->query("SELECT COUNT(*) FROM auto");
$totalCars = $totalStmt->fetchColumn();

$hasMore = $totalCars > $limit;
?>

<main>
    <h2>Ons aanbod</h2>

    <div class="cars" id="cars-container">
        <?php foreach ($cars as $car): ?>
            <div class="car-details">
                <div class="car-brand">
                    <h3><?= htmlspecialchars($car['naam']) ?></h3>
                    <div class="car-type">
                        <?= htmlspecialchars($car['type']) ?>
                    </div>
                </div>

                <img src="assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">

                <div class="car-specification">
                    <span><img src="/assets/images/icons/gas-station.svg"> <?= $car['gasoline'] ?>l</span>
                    <span><img src="/assets/images/icons/car.svg"> <?= $car['steering'] ?></span>
                    <span><img src="/assets/images/icons/profile-2user.svg"> <?= $car['capacity'] ?> Personen</span>
                </div>

                <div class="rent-details">
                    <span><span class="font-weight-bold">€<?= $car['kosten'] ?></span> / dag</span>
                    <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($hasMore): ?>
        <div class="show-more">
            <button id="loadMoreBtn" class="button-primary">Toon meer</button>
        </div>
    <?php endif; ?>
</main>

<?php require "includes/footer.php" ?>

