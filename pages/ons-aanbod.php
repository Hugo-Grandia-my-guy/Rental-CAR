<?php require "includes/header.php"; ?>
<?php require "database/connection.php"; ?>

<?php
$limit = 10;

$search = $_GET['q'] ?? '';
$types = $_GET['type'] ?? [];
$capacities = $_GET['capacity'] ?? [];
$maxPrice = $_GET['max_price'] ?? '';

try {

    $sql = "
        SELECT id, naam, type, image, gasoline, steering, capacity, kosten
        FROM auto
        WHERE 1=1
    ";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (naam LIKE :search OR type LIKE :search)";
        $params[':search'] = "%$search%";
    }

    if (!empty($types)) {
        $placeholders = [];
        foreach ($types as $i => $t) {
            $key = ":type$i";
            $placeholders[] = $key;
            $params[$key] = $t;
        }
        $sql .= " AND type IN (" . implode(',', $placeholders) . ")";
    }

    if (!empty($capacities)) {
        $placeholders = [];
        foreach ($capacities as $i => $c) {
            $key = ":cap$i";
            $placeholders[] = $key;
            $params[$key] = $c;
        }
        $sql .= " AND capacity IN (" . implode(',', $placeholders) . ")";
    }

    if (!empty($maxPrice)) {
        $sql .= " AND kosten <= :maxPrice";
        $params[':maxPrice'] = $maxPrice;
    }

    $sql .= " ORDER BY id DESC LIMIT :limit";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countSql = "SELECT COUNT(*) FROM auto WHERE 1=1";

    if (!empty($search)) {
        $countSql .= " AND (naam LIKE :search OR type LIKE :search)";
    }

    if (!empty($types)) {
        $placeholders = [];
        foreach ($types as $i => $t) {
            $placeholders[] = ":type$i";
        }
        $countSql .= " AND type IN (" . implode(',', $placeholders) . ")";
    }

    if (!empty($capacities)) {
        $placeholders = [];
        foreach ($capacities as $i => $c) {
            $placeholders[] = ":cap$i";
        }
        $countSql .= " AND capacity IN (" . implode(',', $placeholders) . ")";
    }

    if (!empty($maxPrice)) {
        $countSql .= " AND kosten <= :maxPrice";
    }

    $countStmt = $pdo->prepare($countSql);

    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }

    $countStmt->execute();
    $totalCars = $countStmt->fetchColumn();

} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

$hasMore = $totalCars > $limit;
?>

    <style>
        .catalog-layout {
            display: flex;
            gap: 20px;
        }

        .filters {
            width: 13%;
            background: white;
            padding: 20px;
            border-radius: 12px;
        }

        .filter-group {
            margin-bottom: 25px;
        }

        .filter-group h4 {
            font-size: 12px;
            color: #90a3bf;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .filter-group label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #1a202c;
            margin-bottom: 8px;
            cursor: pointer;
        }

        .filter-group input[type="checkbox"] {
            accent-color: #3563e9;
            width: 16px;
            height: 16px;
        }

        input[type="range"] {
            width: 100%;
        }

        .filters .button-primary {
            width: 100%;
            margin-top: 10px;
        }

        .car-details {
            break-inside: avoid;
            grid-auto-columns: auto;
            width: 300px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-sizing: border-box;
            transition: 0.2s ease;
        }

        .car-details:hover {
            transform: translateY(-2px);
        }
        .catalog-content{
            column-count: 3;
            gap: 20px;
        }
    </style>

    <main>
        <h2>Ons aanbod</h2>

        <div class="catalog-layout">

            <aside class="filters">
                <form method="GET" action="/ons-aanbod">

                    <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">

                    <div class="filter-group">
                        <h4 style = "color: #3563e9;">TYPE</h4>

                        <label><input type="checkbox" name="type[]" value="modern" <?= in_array('modern', $types) ? 'checked' : '' ?>>
                            modern</label>
                        <label><input type="checkbox" name="type[]" value="crossover" <?= in_array('crossover', $types) ? 'checked' : '' ?>>
                            crossover</label>
                        <label><input type="checkbox" name="type[]" value="luxe" <?= in_array('luxe', $types) ? 'checked' : '' ?>>
                            luxe</label>
                        <label><input type="checkbox" name="type[]" value="off-road" <?= in_array('off-road', $types) ? 'checked' : '' ?>>
                            off-road</label>
                        <label><input type="checkbox" name="type[]" value="elektrisch" <?= in_array('elektrisch', $types) ? 'checked' : '' ?>>
                            elektrisch</label>
                    </div>

                    <div class="filter-group">
                        <h4 style = "color: #3563e9;">CAPACITY</h4>

                        <label><input type="checkbox" name="capacity[]" value="2" <?= in_array('2', $capacities) ? 'checked' : '' ?>>
                            2 Person</label>
                        <label><input type="checkbox" name="capacity[]" value="4" <?= in_array('4', $capacities) ? 'checked' : '' ?>>
                            4 Person</label>
                        <label><input type="checkbox" name="capacity[]" value="6" <?= in_array('6', $capacities) ? 'checked' : '' ?>>
                            6 Person</label>
                        <label><input type="checkbox" name="capacity[]" value="8" <?= in_array('8', $capacities) ? 'checked' : '' ?>>
                            8 or More</label>
                    </div>

                    <div class="filter-group">
                        <h4 style = "color: #3563e9;">PRICE</h4>

                        <input type="range" name="max_price" min="0" max="200" value="<?= $maxPrice ?: 100 ?>" id="priceRange">
                        <p>Max. €<span id="priceValue"><?= $maxPrice ?: 100 ?></span></p>
                    </div>

                    <button type="submit" class="button-primary">Apply Filter</button>
                </form>
            </aside>

            <div class="catalog-content">

                <div class="cars" id="cars-container">
                    <?php if (empty($cars)): ?>
                        <p>Geen auto gevonden<?= $search ? ' voor "' . htmlspecialchars($search) . '"' : '' ?></p>
                    <?php else: ?>
                        <?php foreach ($cars as $car): ?>
                            <div class="car-details">
                                <div class="car-brand">
                                    <h3><?= htmlspecialchars($car['naam']) ?></h3>
                                    <div class="car-type"><?= htmlspecialchars($car['type']) ?></div>
                                </div>

                                <img src="assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">

                                <div class="car-specification">
                                    <span><img src="/assets/images/icons/gas-station.svg" alt=""><?= htmlspecialchars($car['gasoline']) ?>l</span>
                                    <span><img src="/assets/images/icons/car.svg" alt=""><?= htmlspecialchars($car['steering']) ?></span>
                                    <span><img src="/assets/images/icons/profile-2user.svg" alt=""><?= htmlspecialchars($car['capacity']) ?> Personen</span>
                                </div>

                                <div class="rent-details">
                                    <span><b>€<?= htmlspecialchars($car['kosten']) ?></b> / dag</span>
                                    <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($hasMore && !empty($cars)): ?>
            <div class="show-more">
                <button id="loadMoreBtn" class="button-primary" style="cursor: pointer">Toon meer</button>
            </div>
        <?php endif; ?>
    </main>

    <script>
        let page = 2;
        const totalCars = <?= (int)$totalCars ?>;
        let loadedCars = <?= count($cars) ?>;

        const loadMoreBtn = document.getElementById('loadMoreBtn');

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {

                const params = new URLSearchParams(<?= json_encode($_GET) ?>);
                params.set('page', page);

                fetch('/database/load-more-cars.php?' + params.toString())
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
                        loadedCars += 10;

                        if (loadedCars >= totalCars) {
                            loadMoreBtn.style.display = 'none';
                        }
                    });
            });
        }

        const range = document.getElementById('priceRange');
        const value = document.getElementById('priceValue');

        if (range) {
            range.addEventListener('input', () => {
                value.textContent = range.value;
            });
        }
    </script>

<?php require "includes/footer.php"; ?>