<?php require "includes/header.php"; ?>
<?php require "database/connection.php"; ?>

<?php
$limit = 10;
$search = $_GET['q'] ?? '';

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

    $sql .= " ORDER BY id DESC LIMIT :limit";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($search)) {
        $countStmt = $pdo->prepare("
            SELECT COUNT(*) FROM auto 
            WHERE naam LIKE :search OR type LIKE :search
        ");
        $countStmt->bindValue(':search', "%$search%");
        $countStmt->execute();
        $totalCars = $countStmt->fetchColumn();
    } else {
        $totalCars = $pdo->query("SELECT COUNT(*) FROM auto")->fetchColumn();
    }

} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

$hasMore = $totalCars > $limit;
?>

    <main>
        <h2>Ons aanbod</h2>

        <div class="cars" id="cars-container">
            <?php if (empty($cars)): ?>
                <p style="font-size: 18px; margin: 20px 0;">
                    Geen auto gevonden voor "<?= htmlspecialchars($search) ?>"
                </p>
            <?php else: ?>
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
                            <span><img src="/assets/images/icons/gas-station.svg" alt="tank"> <?= htmlspecialchars($car['gasoline']) ?>l</span>
                            <span><img src="/assets/images/icons/car.svg" alt="besturingsysteem"> <?= htmlspecialchars($car['steering']) ?></span>
                            <span><img src="/assets/images/icons/profile-2user.svg" alt="aantal personen"> <?= htmlspecialchars($car['capacity']) ?> Personen</span>
                        </div>

                        <div class="rent-details">
                    <span>
                        <span class="font-weight-bold">€<?= htmlspecialchars($car['kosten']) ?></span> / dag
                    </span>
                            <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($hasMore && !empty($cars)): ?>
            <div class="show-more">
                <button id="loadMoreBtn" class="button-primary">Toon meer</button>
            </div>
        <?php endif; ?>
    </main>

    <script src="/assets/javascript/main.js">

    </script>

<?php require "includes/footer.php"; ?>