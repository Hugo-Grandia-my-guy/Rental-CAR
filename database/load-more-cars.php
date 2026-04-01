<?php
require "connection.php";

$limit = 10;
$page = $_GET['page'] ?? 1;
$search = $_GET['q'] ?? '';

$offset = ($page - 1) * $limit;

try {

    $sql = "
        SELECT id, naam, type, image, gasoline, steering, capacity, kosten
        FROM auto
    ";

    $params = [];

    if (!empty($search)) {
        $sql .= " WHERE naam LIKE :search OR type LIKE :search ";
        $params[':search'] = "%$search%";
    }

    $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cars as $car): ?>
        <div class="car-details">
            <div class="car-brand">
                <h3><?= htmlspecialchars($car['naam']) ?></h3>
                <div class="car-type">
                    <?= htmlspecialchars($car['type']) ?>
                </div>
            </div>

            <img src="assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">

            <div class="car-specification">
                <span><?= htmlspecialchars($car['gasoline']) ?>l</span>
                <span><?= htmlspecialchars($car['steering']) ?></span>
                <span><?= htmlspecialchars($car['capacity']) ?> Personen</span>
            </div>

            <div class="rent-details">
                <span>
                    <span class="font-weight-bold">€<?= htmlspecialchars($car['kosten']) ?></span> / dag
                </span>
                <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
            </div>
        </div>
    <?php endforeach;

} catch (PDOException $e) {
    die("Fout: " . $e->getMessage());
}