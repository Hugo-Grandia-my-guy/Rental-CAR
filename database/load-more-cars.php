<?php
require __DIR__ . '/connection.php';

$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

try {
    $stmt = $pdo->prepare("
        SELECT id, naam, type, image, gasoline, steering, capacity, kosten
        FROM auto
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Ошибка БД: " . $e->getMessage());
}

if (!$cars) {
    exit;
}

foreach ($cars as $car): ?>
    <div class="car-details">
        <div class="car-brand">
            <h3><?= htmlspecialchars($car['naam']) ?></h3>
            <div class="car-type"><?= htmlspecialchars($car['type']) ?></div>
        </div>

        <img src="assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">

        <div class="car-specification">
            <span><img src="/assets/images/icons/gas-station.svg"> <?= htmlspecialchars($car['gasoline']) ?>l</span>
            <span><img src="/assets/images/icons/car.svg"> <?= htmlspecialchars($car['steering']) ?></span>
            <span><img src="/assets/images/icons/profile-2user.svg"> <?= htmlspecialchars($car['capacity']) ?> Personen</span>
        </div>

        <div class="rent-details">
            <span>
                <span class="font-weight-bold">€<?= htmlspecialchars($car['kosten']) ?></span> / dag
            </span>
            <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
        </div>
    </div>
<?php endforeach; ?>