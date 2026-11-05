
<?php

require_once '../config.php';

require_admin();

// Update booking status
if (isset($_POST['status'], $_POST['booking_id'])) {
    $allowedStatuses = [
        'Pending',
        'Confirmed',
        'Cancelled',
        'Completed'
    ];

    $status = $_POST['status'];
    $bookingId = (int) $_POST['booking_id'];

    if (in_array($status, $allowedStatuses, true)) {
        $stmt = $pdo->prepare(
            "UPDATE bookings SET status = ? WHERE id = ?"
        );

        $stmt->execute([
            $status,
            $bookingId
        ]);
    }

    header('Location: dashboard.php');
    exit;
}

// Dashboard statistics
$stats = [
    'bookings' => $pdo
        ->query('SELECT COUNT(*) FROM bookings')
        ->fetchColumn(),

    'pending' => $pdo
        ->query(
            "SELECT COUNT(*) FROM bookings
             WHERE status = 'Pending'"
        )
        ->fetchColumn(),

    'guests' => $pdo
        ->query(
            "SELECT COUNT(*) FROM users
             WHERE role = 'user'"
        )
        ->fetchColumn(),

    'rooms' => $pdo
        ->query(
            'SELECT COUNT(*) FROM rooms
             WHERE is_available = 1'
        )
        ->fetchColumn()
];

// Retrieve all bookings with guest and room information
$sql = "
    SELECT
        b.*,
        u.name AS guest_name,
        u.email,
        r.name AS room_name,
        r.price
    FROM bookings AS b
    JOIN users AS u
        ON u.id = b.user_id
    JOIN rooms AS r
        ON r.id = b.room_id
    ORDER BY b.created_at DESC
";

$bookings = $pdo->query($sql)->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard | Azure Haven</title>

    <link
        rel="stylesheet"
        href="../assets/style.css"
    >
</head>

<body class="dashboard admin-page">

    <!-- Dashboard Header -->
    <header class="dash-header">

        <a class="brand" href="../index.php">

            <span class="brand-mark">AH</span>

            <span>
                AZURE HAVEN
                <small>ADMIN CONSOLE</small>
            </span>

        </a>

        <div>
            Administrator
            <a class="text-link" href="../logout.php">
                Log out
            </a>
        </div>

    </header>

    <!-- Main Dashboard Content -->
    <main class="dash-main">

        <p class="eyebrow dark">
            RESORT MANAGEMENT
        </p>

        <h1>
            Admin <em>overview.</em>
        </h1>

        <!-- Statistics Cards -->
        <div class="stats-grid">

            <?php
            $statLabels = [
                'Bookings'       => $stats['bookings'],
                'Pending'        => $stats['pending'],
                'Guest accounts' => $stats['guests'],
                'Available rooms'=> $stats['rooms']
            ];
            ?>

            <?php foreach ($statLabels as $label => $value): ?>

                <div class="stat-card">

                    <p><?= e($label) ?></p>

                    <strong><?= e($value) ?></strong>

                </div>

            <?php endforeach; ?>

        </div>

        <!-- Reservation Management -->
        <section class="panel table-panel">

            <div class="table-title">

                <div>
                    <h2>Reservation management</h2>

                    <p class="muted">
                        Review guest requests and update their status.
                    </p>
                </div>

            </div>

            <div class="table-scroll">

                <table>

                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Dates</th>
                            <th>Guests</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($bookings as $booking): ?>

                            <tr>

                                <!-- Guest Information -->
                                <td>
                                    <strong>
                                        <?= e($booking['guest_name']) ?>
                                    </strong>

                                    <small>
                                        <?= e($booking['email']) ?>
                                    </small>
                                </td>

                                <!-- Room Information -->
                                <td>
                                    <?= e($booking['room_name']) ?>
                                </td>

                                <!-- Booking Dates -->
                                <td>
                                    <?= e($booking['check_in']) ?>

                                    <small>
                                        to
                                        <?= e($booking['check_out']) ?>
                                    </small>
                                </td>

                                <!-- Number of Guests -->
                                <td>
                                    <?= (int) $booking['guests'] ?>
                                </td>

                                <!-- Booking Status -->
                                <td>
                                    <span class="status status-<?= e(strtolower($booking['status'])) ?>">
                                        <?= e($booking['status']) ?>
                                    </span>
                                </td>

                                <!-- Update Booking Status -->
                                <td>

                                    <form
                                        method="post"
                                        class="status-form"
                                    >

                                        <input
                                            type="hidden"
                                            name="booking_id"
                                            value="<?= (int) $booking['id'] ?>"
                                        >

                                        <select
                                            name="status"
                                            aria-label="Reservation status"
                                        >

                                            <?php
                                            $statuses = [
                                                'Pending',
                                                'Confirmed',
                                                'Cancelled',
                                                'Completed'
                                            ];
                                            ?>

                                            <?php foreach ($statuses as $status): ?>

                                                <option
                                                    value="<?= e($status) ?>"
                                                    <?= $booking['status'] === $status
                                                        ? 'selected'
                                                        : '' ?>
                                                >
                                                    <?= e($status) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                        <button
                                            type="submit"
                                            class="btn btn-small"
                                        >
                                            Save
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

                <!-- Empty State -->
                <?php if (empty($bookings)): ?>

                    <p class="empty">
                        No bookings received yet.
                    </p>

                <?php endif; ?>

            </div>

        </section>

        <!-- Payment Notice -->
        <p class="tiny">
            Prototype note: Payment processing is not connected.
            Integrate a trusted payment gateway before accepting
            real payments.
        </p>

    </main>

</body>

</html>
