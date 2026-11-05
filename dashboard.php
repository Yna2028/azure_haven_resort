
<?php

require_once 'config.php';

require_user();

// Initialize messages
$success = '';
$error = '';

// Handle reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $roomId = (int) ($_POST['room_id'] ?? 0);
    $checkIn = $_POST['check_in'] ?? '';
    $checkOut = $_POST['check_out'] ?? '';
    $guests = max(1, (int) ($_POST['guests'] ?? 1));

    // Validate reservation dates
    if (
        $checkIn < $checkOut &&
        $checkIn >= date('Y-m-d')
    ) {

        // Verify that the selected room is available
        $roomStmt = $pdo->prepare(
            'SELECT id FROM rooms
             WHERE id = ? AND is_available = 1'
        );

        $roomStmt->execute([$roomId]);

        if ($roomStmt->fetch()) {

            // Save the reservation
            $stmt = $pdo->prepare(
                "INSERT INTO bookings (
                    user_id,
                    room_id,
                    check_in,
                    check_out,
                    guests,
                    status
                ) VALUES (?, ?, ?, ?, ?, 'Pending')"
            );

            $stmt->execute([
                $_SESSION['user_id'],
                $roomId,
                $checkIn,
                $checkOut,
                $guests
            ]);

            $success = 'Reservation request submitted. '
                . 'The resort team will confirm it shortly.';

        } else {
            $error = 'The selected room is unavailable. '
                . 'Please choose another room.';
        }

    } else {
        $error = 'Choose valid dates. '
            . 'Check-out must be after check-in, '
            . 'and check-in cannot be in the past.';
    }
}

// Retrieve available rooms
$rooms = $pdo->query(
    'SELECT * FROM rooms WHERE is_available = 1'
)->fetchAll();

// Retrieve the logged-in user's reservations
$stmt = $pdo->prepare(
    'SELECT
        b.*,
        r.name AS room_name,
        r.price
     FROM bookings AS b
     JOIN rooms AS r ON r.id = b.room_id
     WHERE b.user_id = ?
     ORDER BY b.created_at DESC'
);

$stmt->execute([$_SESSION['user_id']]);

$bookings = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Guest Dashboard | Azure Haven</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body class="dashboard">

    <!-- Dashboard Header -->
    <header class="dash-header">

        <a class="brand" href="index.php">

            <span class="brand-mark">AH</span>

            <span>
                AZURE HAVEN
                <small>GUEST PORTAL</small>
            </span>

        </a>

        <div>
            Welcome,
            <?= e($_SESSION['user_name'] ?? 'Guest') ?>

            <a class="text-link" href="logout.php">
                Log out
            </a>
        </div>

    </header>

    <!-- Main Dashboard -->
    <main class="dash-main">

        <p class="eyebrow dark">
            YOUR GETAWAY, ALL IN ONE PLACE
        </p>

        <h1>
            Guest <em>dashboard.</em>
        </h1>

        <div class="dash-grid">

            <!-- Reservation Form -->
            <section class="panel">

                <h2>Plan a stay</h2>

                <p class="muted">
                    Send a reservation request for your preferred dates.
                </p>

                <!-- Success Message -->
                <?php if (!empty($success)): ?>

                    <div class="success">
                        <?= e($success) ?>
                    </div>

                <?php endif; ?>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>

                    <div class="alert">
                        <?= e($error) ?>
                    </div>

                <?php endif; ?>

                <!-- Booking Form -->
                <form method="post" class="booking-form">

                    <label>
                        Room

                        <select name="room_id" required>

                            <?php foreach ($rooms as $room): ?>

                                <option value="<?= (int) $room['id'] ?>">
                                    <?= e($room['name']) ?>
                                    — ₱<?= number_format((float) $room['price']) ?>
                                    / night
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </label>

                    <div class="two-fields">

                        <label>
                            Check-in

                            <input
                                type="date"
                                name="check_in"
                                min="<?= date('Y-m-d') ?>"
                                required
                            >
                        </label>

                        <label>
                            Check-out

                            <input
                                type="date"
                                name="check_out"
                                min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                required
                            >
                        </label>

                    </div>

                    <label>
                        Guests

                        <input
                            type="number"
                            name="guests"
                            min="1"
                            max="12"
                            value="2"
                            required
                        >
                    </label>

                    <button type="submit" class="btn full">
                        Request reservation ↗
                    </button>

                </form>

            </section>

            <!-- Reservation History -->
            <section class="panel">

                <h2>Your reservations</h2>

                <?php if (empty($bookings)): ?>

                    <p class="empty">
                        No reservations yet.
                        Your next escape is just a few clicks away.
                    </p>

                <?php else: ?>

                    <?php foreach ($bookings as $booking): ?>

                        <div class="booking-item">

                            <div>

                                <strong>
                                    <?= e($booking['room_name']) ?>
                                </strong>

                                <p>
                                    <?= e($booking['check_in']) ?>

                                    &rarr;

                                    <?= e($booking['check_out']) ?>

                                    ·

                                    <?= (int) $booking['guests'] ?>
                                    guests
                                </p>

                            </div>

                            <span class="status status-<?= e(strtolower($booking['status'])) ?>">
                                <?= e($booking['status']) ?>
                            </span>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </section>

        </div>

    </main>

</body>

</html>
