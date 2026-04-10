<?php require "database/connection.php"; ?>
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: /login-form");
    exit;
}

$carId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$carId) {
    die("Ongeldige auto ID");
}

$stmt = $pdo->prepare("SELECT * FROM auto WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $carId]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$car) die("Auto niet gevonden");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require "includes/header.php";
?>

<style>
    .payment-page {
        background: #f6f7fb;
        padding: 40px 0;
        font-family: 'Inter', sans-serif;
    }

    .payment-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        padding: 0 20px;
    }

    .form-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .step {
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .step h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .step input[type="text"],
    .step input[type="date"],
    .step input[type="time"] {
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid #e4e7ec;
        background: #f9fafb;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
    }

    .step input:focus {
        border-color: #3563e9;
        background: #fff;
    }

    .step label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .button-primary {
        background: #3563e9;
        color: #fff;
        border: none;
        padding: 12px 18px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 500;
        transition: 0.2s;
    }

    .button-primary:hover {
        background: #2a4fcc;
    }

    .step button[type="button"] {
        background: #eef2ff;
        border: none;
        padding: 10px;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
    }

    .step button[type="button"]:hover {
        background: #e0e7ff;
    }

    .step .next-step,
    .step .prev-step {
        margin-top: 10px;
    }

    .summary-section {
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        height: fit-content;
    }

    .summary-section img {
        width: 100%;
        border-radius: 12px;
        margin-bottom: 15px;
    }

    .summary-section h4 {
        font-size: 18px;
        margin-bottom: 5px;
    }

    .summary-section p {
        font-size: 14px;
        color: #6b7280;
    }

    .summary-section #total {
        font-weight: 600;
        font-size: 20px;
        color: #111827;
    }

    @media (max-width: 900px) {
        .payment-container {
            grid-template-columns: 1fr;
        }

        .summary-section {
            order: -1;
        }
    }
</style>

    <main class="payment-page">
        <div class="payment-container">
            <div class="form-section">
                <form action="/actions/pay.php" method="POST" class="multi-step-form">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                    <div class="step step-1">
                        <h3>Billing Info</h3>
                        <input type="text" name="name" placeholder="Your Name" required>
                        <input type="text" name="phone" placeholder="Phone Number" required>
                        <input type="text" name="address" placeholder="Address" required>
                        <input type="text" name="city" placeholder="Town / City" required>
                        <button type="button" class="next-step">Next</button>
                    </div>

                    <div class="step step-2" style="display:none;">
                        <h3>Rental Info</h3>
                        <label>Pick-Up Location</label>
                        <input type="text" name="pickup_location" required>
                        <label>Pick-Up Date</label>
                        <input type="date" name="pickup_date" required>
                        <label>Pick-Up Time</label>
                        <input type="time" name="pickup_time" required>

                        <label>Drop-Off Location</label>
                        <input type="text" name="dropoff_location" required>
                        <label>Drop-Off Date</label>
                        <input type="date" name="dropoff_date" required>
                        <label>Drop-Off Time</label>
                        <input type="time" name="dropoff_time" required>

                        <button type="button" class="prev-step">Back</button>
                        <button type="button" class="next-step">Next</button>
                    </div>

                    <div class="step step-3" style="display:none;">
                        <h3>Payment Method</h3>
                        <label>
                            <input type="radio" name="payment_method" value="card" checked> Credit Card
                        </label>
                        <input type="text" name="card_number" placeholder="Card Number" required>
                        <input type="text" name="card_holder" placeholder="Card Holder" required>
                        <input type="text" name="expiry" placeholder="MM/YY" required>
                        <input type="text" name="cvc" placeholder="CVC" required>

                        <label>
                            <input type="radio" name="payment_method" value="paypal"> PayPal
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="bitcoin"> Bitcoin
                        </label>

                        <button type="button" class="prev-step">Back</button>
                        <button type="button" class="next-step">Next</button>
                    </div>

                    <div class="step step-4" style="display:none;">
                        <h3>Confirmation</h3>
                        <label>
                            <input type="checkbox" name="marketing"> I agree with sending Marketing emails
                        </label>
                        <label>
                            <input type="checkbox" name="terms" required> I agree with Terms and Conditions
                        </label>

                        <button type="button" class="prev-step">Back</button>
                        <button type="submit" class="button-primary">Rent Now</button>
                    </div>
                </form>
            </div>

            <div class="summary-section">
                <h3>Rental Summary</h3>
                <img src="/assets/images/products/<?= htmlspecialchars($car['image']) ?>" alt="">
                <h4><?= htmlspecialchars($car['naam']) ?></h4>
                <p>€<span id="price"><?= number_format($car['kosten'],2) ?></span> / dag</p>
                <p>Total: €<span id="total"><?= number_format($car['kosten'],2) ?></span></p>
            </div>
        </div>
    </main>

    <script>
        let currentStep = 1;
        const steps = document.querySelectorAll('.step');

        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', () => {
                steps[currentStep-1].style.display = 'none';
                steps[currentStep].style.display = 'block';
                currentStep++;
            });
        });

        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', () => {
                steps[currentStep-1].style.display = 'none';
                steps[currentStep-2].style.display = 'block';
                currentStep--;
            });
        });

        const daysInput = document.createElement('input');
        daysInput.type = 'hidden';
        daysInput.name = 'days';
        daysInput.value = 1;
        document.querySelector('.multi-step-form').appendChild(daysInput);
    </script>

<?php require "includes/footer.php"; ?>