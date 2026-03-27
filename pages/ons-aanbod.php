<?php require "includes/header.php"; ?>
<?php require "database/connection.php"; ?>

<?php
$limit = 10;

try {
    $stmt = $pdo->prepare("
        SELECT id, naam, type, image, gasoline, steering, capacity, kosten
        FROM auto
        ORDER BY id DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalCars = $pdo->query("SELECT COUNT(*) FROM auto")->fetchColumn();

} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

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
        </div>

        <?php if ($hasMore): ?>
            <div class="show-more">
                <button id="loadMoreBtn" class="button-primary">Toon meer</button>
            </div>
        <?php endif; ?>
    </main>

    <script>
        let page = 2;
        const limit = 10;
        const totalCars = <?= (int)$totalCars ?>;
        let loadedCars = <?= count($cars) ?>;

        const loadMoreBtn = document.getElementById('loadMoreBtn');

        if (loadMoreBtn) {

            if (loadedCars >= totalCars) {
                loadMoreBtn.style.display = 'none';
            }

            loadMoreBtn.addEventListener('click', () => {
                fetch(`/database/load-more-cars.php?page=${page}`)
                    .then(res => res.text())
                    .then(data => {
                        if (!data.trim()) {
                            loadMoreBtn.style.display = 'none';
                            return;
                        }

                        document
                            .getElementById('cars-container')
                            .insertAdjacentHTML('beforeend', data);

                        page++;
                        loadedCars += limit;

                        if (loadedCars >= totalCars) {
                            loadMoreBtn.style.display = 'none';
                        }
                    })
                    .catch(console.error);
            });
        }
    </script>

<?php require "includes/footer.php"; ?>