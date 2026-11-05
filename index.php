
<?php

require_once 'config.php';

// Retrieve available rooms, ordered by price
$stmt = $pdo->query(
    'SELECT *
     FROM rooms
     WHERE is_available = 1
     ORDER BY price ASC'
);

$rooms = $stmt->fetchAll();

// Gallery images
$galleryImages = [
    [
        'file' => '01-infinity-pool.jpg',
        'title' => 'Infinity Pool',
        'alt' => 'Infinity pool overlooking the ocean'
    ],
    [
        'file' => '02-coastal-bedroom.jpg',
        'title' => 'Coastal Bedroom',
        'alt' => 'Elegant coastal resort bedroom'
    ],
    [
        'file' => '03-garden-villa.jpg',
        'title' => 'Garden Villa',
        'alt' => 'Modern villa surrounded by tropical greenery'
    ],
    [
        'file' => '04-oceanview-lounge.jpg',
        'title' => 'Oceanview Lounge',
        'alt' => 'Resort lounge with panoramic ocean views'
    ],
    [
        'file' => '05-sunset-beach.jpg',
        'title' => 'Sunset Beach',
        'alt' => 'Tropical beach at sunset'
    ],
    [
        'file' => '06-seaside-dining.jpg',
        'title' => 'Seaside Dining',
        'alt' => 'Dining setup overlooking the sea'
    ],
    [
        'file' => '07-spa-retreat.jpg',
        'title' => 'Spa Retreat',
        'alt' => 'Peaceful tropical spa treatment room'
    ],
    [
        'file' => '08-tropical-pathway.jpg',
        'title' => 'Tropical Pathway',
        'alt' => 'Garden pathway surrounded by tropical plants'
    ],
    [
        'file' => '09-tropical-drink.jpg',
        'title' => 'Poolside Refreshments',
        'alt' => 'Tropical drink beside the pool'
    ],
    [
        'file' => '10-poolside-cabana.jpg',
        'title' => 'Poolside Cabana',
        'alt' => 'Comfortable cabanas beside a resort pool'
    ],
    [
        'file' => '11-resort-aerial.jpg',
        'title' => 'Resort from Above',
        'alt' => 'Aerial view of a tropical coastal resort'
    ],
    [
        'file' => '12-seaside-breakfast.jpg',
        'title' => 'Seaside Breakfast',
        'alt' => 'Breakfast served overlooking the sea'
    ]
];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Discover Azure Haven Resort. Enjoy coastal rooms, tropical villas, peaceful experiences, and your next unforgettable getaway."
    >

    <title>Azure Haven Resort | Find Your Blue</title>

    <link rel="stylesheet" href="assets/style.css">

    <!-- Gallery Styles -->
    <style>
        /* Gallery Section */
        .gallery-section {
            padding: 100px 8%;
            background: #f5f0e7;
            scroll-margin-top: 30px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-top: 38px;
        }

        .gallery-item {
            position: relative;
            display: block;
            height: 250px;
            overflow: hidden;
            background: #e4ded2;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .gallery-item img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item::after {
            content: "";
            position: absolute;
            inset: 35% 0 0;
            background: linear-gradient(
                transparent,
                rgba(10, 30, 38, 0.75)
            );
            pointer-events: none;
        }

        .gallery-item span {
            position: absolute;
            z-index: 1;
            left: 18px;
            right: 10px;
            bottom: 17px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.4px;
        }

        .gallery-item:hover img {
            transform: scale(1.06);
        }

        .gallery-item:focus-visible {
            outline: 3px solid #6b9d9b;
            outline-offset: 3px;
        }

        /* Gallery Lightbox */
        .gallery-lightbox {
            width: min(900px, 90vw);
            max-width: none;
            max-height: 90vh;
            padding: 24px;
            border: none;
            background: #fff;
            color: #193c49;
            box-sizing: border-box;
        }

        .gallery-lightbox::backdrop {
            background: rgba(8, 23, 29, 0.85);
        }

        .gallery-lightbox[open] {
            margin: auto;
        }

        .gallery-lightbox img {
            display: block;
            width: 100%;
            max-height: 75vh;
            object-fit: contain;
        }

        .gallery-lightbox p {
            margin: 14px 0 0;
            text-align: center;
            font-size: 15px;
        }

        .gallery-close {
            display: block;
            margin-left: auto;
            margin-bottom: 12px;
            padding: 0 8px;
            border: none;
            background: transparent;
            color: #193c49;
            font-size: 30px;
            cursor: pointer;
        }

        /* Responsive Gallery */
        @media (max-width: 1000px) {
            .gallery-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .gallery-section {
                padding: 60px 20px;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .gallery-item {
                height: 180px;
            }

            .gallery-item span {
                left: 10px;
                bottom: 12px;
                font-size: 12px;
            }

            .gallery-lightbox {
                padding: 14px;
            }
        }

        @media (max-width: 380px) {
            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .gallery-item {
                height: 230px;
            }
        }
    </style>

</head>

<body>

    <!-- Navigation Header -->
    <header class="site-header">

        <a class="brand" href="index.php">

            <span class="brand-mark">AH</span>

            <span>
                AZURE HAVEN
                <small>RESORT &amp; ESCAPE</small>
            </span>

        </a>

        <nav aria-label="Main navigation">

            <a href="#stay">Stay</a>

            <a href="#experience">Experience</a>

            <a href="#gallery">Gallery</a>

            <a href="#contact">Contact</a>

            <a class="nav-login" href="login.php">
                Log in
            </a>

            <a class="btn btn-small" href="signup.php">
                Book your stay
            </a>

        </nav>

    </header>

    <main>

        <!-- Hero Section -->
        <section class="hero">

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <p class="eyebrow">
                    YOUR PRIVATE PIECE OF PARADISE
                </p>

                <h1>
                    Escape the ordinary.
                    <br>
                    <em>Find your blue.</em>
                </h1>

                <p class="hero-copy">
                    Slow mornings, ocean air, and moments worth keeping.
                    Your next getaway starts here.
                </p>

                <a class="btn" href="signup.php">
                    Plan your escape
                    <span>↗</span>
                </a>

                <div class="hero-note">

                    <span class="sun-dot"></span>

                    Thoughtful stays. Unforgettable days.

                </div>

            </div>

            <div class="hero-side-label">
                TROPICAL LIVING · EST. 2024
            </div>

        </section>

        <!-- Resort Introduction -->
        <section class="intro" id="experience">

            <div>

                <p class="eyebrow dark">
                    A DIFFERENT KIND OF GETAWAY
                </p>

                <h2>
                    More than a destination.
                    <br>
                    <em>A feeling.</em>
                </h2>

            </div>

            <p class="intro-copy">
                Find your rhythm in a place made for unwinding.
                From sunlit rooms to quiet poolside afternoons,
                every detail invites you to pause, reconnect,
                and enjoy the moment.
            </p>

        </section>

        <!-- Resort Features -->
        <section class="feature-grid">

            <article class="feature feature-pool">

                <div>

                    <span>01 / UNWIND</span>

                    <h3>
                        Water, warmth
                        <br>
                        &amp; no rush.
                    </h3>

                </div>

            </article>

            <article class="feature feature-dining">

                <div>

                    <span>02 / SAVOR</span>

                    <h3>
                        Fresh flavors.
                        <br>
                        Long evenings.
                    </h3>

                </div>

            </article>

            <article class="feature feature-nature">

                <div>

                    <span>03 / EXPLORE</span>

                    <h3>
                        Nature is
                        <br>
                        your neighbor.
                    </h3>

                </div>

            </article>

        </section>

        <!-- Available Rooms -->
        <section class="stay-section" id="stay">

            <div class="section-heading">

                <div>

                    <p class="eyebrow dark">
                        REST YOUR WAY
                    </p>

                    <h2>
                        Rooms made for
                        <em>recharging.</em>
                    </h2>

                </div>

                <p>Choose your corner of calm.</p>

            </div>

            <div class="room-grid">

                <?php if (empty($rooms)): ?>

                    <p class="empty">
                        No rooms are currently available.
                        Please check back later.
                    </p>

                <?php else: ?>

                    <?php foreach ($rooms as $room): ?>

                        <article class="room-card">

                            <!-- Room Image -->
                            <div
                                class="room-image room-<?= (int) $room['id'] ?>"
                                role="img"
                                aria-label="<?= e($room['name']) ?>"
                            ></div>

                            <!-- Room Details -->
                            <div class="room-info">

                                <div>

                                    <h3>
                                        <?= e($room['name']) ?>
                                    </h3>

                                    <p>
                                        <?= e($room['description']) ?>
                                    </p>

                                </div>

                                <div class="room-price">

                                    ₱<?= number_format((float) $room['price'], 0) ?>

                                    <small>/ night</small>

                                </div>

                            </div>

                            <!-- Reservation Link -->
                            <a class="text-link" href="signup.php">
                                Reserve this room ↗
                            </a>

                        </article>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </section>

        <!-- Resort Gallery -->
        <section class="gallery-section" id="gallery">

            <div class="section-heading">

                <div>

                    <p class="eyebrow dark">
                        A GLIMPSE OF PARADISE
                    </p>

                    <h2>
                        Moments worth
                        <em>remembering.</em>
                    </h2>

                </div>

                <p>Explore the Azure Haven experience.</p>

            </div>

            <!-- Twelve Gallery Pictures -->
            <div class="gallery-grid">

                <?php foreach ($galleryImages as $image): ?>

                    <?php
                    $imagePath = 'assets/gallery/' . $image['file'];
                    ?>

                    <a
                        href="<?= e($imagePath) ?>"
                        class="gallery-item"
                        data-caption="<?= e($image['title']) ?>"
                        aria-label="View <?= e($image['title']) ?>"
                    >

                        <img
                            src="<?= e($imagePath) ?>"
                            alt="<?= e($image['alt']) ?>"
                            loading="lazy"
                        >

                        <span>
                            <?= e($image['title']) ?>
                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        </section>

        <!-- Gallery Lightbox -->
        <dialog
            class="gallery-lightbox"
            id="gallery-lightbox"
            aria-label="Enlarged gallery image"
        >

            <button
                type="button"
                class="gallery-close"
                aria-label="Close image"
            >
                &times;
            </button>

            <img
                id="lightbox-image"
                src=""
                alt=""
            >

            <p id="lightbox-caption"></p>

        </dialog>

        <!-- Call to Action -->
        <section class="quote-band">

            <p class="eyebrow">
                MAKE SPACE FOR YOURSELF
            </p>

            <h2>
                “The best memories
                <br>
                happen when we
                <em>slow down.</em>”
            </h2>

            <a class="btn btn-light" href="signup.php">
                Your escape awaits ↗
            </a>

        </section>

    </main>

    <!-- Footer -->
    <footer id="contact">

        <a class="brand brand-footer" href="index.php">

            <span class="brand-mark">AH</span>

            <span>
                AZURE HAVEN
                <small>RESORT &amp; ESCAPE</small>
            </span>

        </a>

        <p>
            Find your blue. Stay a little longer.
        </p>

        <div>

            <a href="login.php">Guest login</a>

            <a href="admin/login.php">Admin</a>

        </div>

        <small>
            © <?= date('Y') ?> Azure Haven Resort.
            Demo website prototype.
        </small>

    </footer>

    <!-- Gallery Lightbox JavaScript -->
    <script>
        const lightbox = document.getElementById('gallery-lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxCaption = document.getElementById('lightbox-caption');
        const closeButton = document.querySelector('.gallery-close');

        // Open enlarged image when a gallery item is clicked
        document.querySelectorAll('.gallery-item').forEach(function (item) {

            item.addEventListener('click', function (event) {

                event.preventDefault();

                const image = item.querySelector('img');

                lightboxImage.src = item.href;
                lightboxImage.alt = image.alt;

                lightboxCaption.textContent =
                    item.dataset.caption || image.alt;

                lightbox.showModal();

            });

        });

        // Close using the close button
        closeButton.addEventListener('click', function () {
            lightbox.close();
        });

        // Close when clicking the backdrop
        lightbox.addEventListener('click', function (event) {

            if (event.target === lightbox) {
                lightbox.close();
            }

        });

        // Clear image when the lightbox closes
        lightbox.addEventListener('close', function () {

            lightboxImage.src = '';

        });
    </script>

</body>

</html>
