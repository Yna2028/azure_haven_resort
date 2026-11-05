
<?php

require_once 'config.php';

// Initialize error message
$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get submitted credentials
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Find user by email
    $stmt = $pdo->prepare(
        'SELECT * FROM users WHERE email = ?'
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    // Verify credentials
    if (
        $user &&
        password_verify($password, $user['password'])
    ) {

        // Regenerate session ID after successful login
        session_regenerate_id(true);

        // Store authenticated user information
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Redirect according to user role
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: dashboard.php');
        }

        exit;

    } else {
        $error = 'Email or password is incorrect.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Log in | Azure Haven</title>

    <link rel="stylesheet" href="assets/style.css">

    <!-- Password Toggle Styling -->
    <style>
        .password-field {
            margin-bottom: 20px;
        }

        .password-field > label {
            display: block;
            margin-bottom: 8px;
        }

        .password-input-wrapper {
            position: relative;
            width: 100%;
        }

        .password-input-wrapper input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding-right: 75px;
        }

        .password-toggle-btn {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            padding: 6px 0;
            border: none;
            background: transparent;
            color: #245b78;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .password-toggle-btn:hover {
            color: #163b4b;
        }

        .password-toggle-btn:focus-visible {
            outline: 2px solid #245b78;
            outline-offset: 3px;
        }
    </style>

</head>

<body class="auth-page">

    <!-- Brand Logo -->
    <a class="brand auth-brand" href="index.php">

        <span class="brand-mark">AH</span>

        <span>
            AZURE HAVEN
            <small>RESORT &amp; ESCAPE</small>
        </span>

    </a>

    <div class="auth-wrap">

        <!-- Login Banner -->
        <div class="auth-art">

            <p class="eyebrow">
                WELCOME BACK
            </p>

            <h1>
                Your blue
                <br>
                is waiting.
            </h1>

        </div>

        <!-- Login Form -->
        <form class="auth-form" method="post">

            <p class="eyebrow dark">
                GUEST &amp; ADMIN ACCESS
            </p>

            <h2>Welcome back</h2>

            <p class="muted">
                Sign in to continue to your dashboard.
            </p>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>

                <div class="alert">
                    <?= e($error) ?>
                </div>

            <?php endif; ?>

            <!-- Email Address -->
            <label for="login-email">
                Email address
            </label>

            <input
                type="email"
                id="login-email"
                name="email"
                required
                autocomplete="username"
                value="<?= e($_POST['email'] ?? '') ?>"
            >

            <!-- Password Field -->
            <div class="password-field">

                <label for="login-password">
                    Password
                </label>

                <div class="password-input-wrapper">

                    <input
                        type="password"
                        name="password"
                        id="login-password"
                        required
                        autocomplete="current-password"
                    >

                    <button
                        type="button"
                        class="password-toggle-btn"
                        id="toggle-login-password"
                        aria-label="Show password"
                        aria-pressed="false"
                    >
                        Show
                    </button>

                </div>

            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn full">
                Log in ↗
            </button>

            <!-- Registration Link -->
            <p class="form-foot">
                New to Azure Haven?

                <a href="signup.php">
                    Create account
                </a>
            </p>

            <!-- Demo Credentials -->
            <p class="tiny">
                Admin demo: admin@azurehaven.test / Admin123!
            </p>

        </form>

    </div>

    <!-- Show/Hide Password Script -->
    <script>
        const passwordInput =
            document.getElementById('login-password');

        const toggleButton =
            document.getElementById('toggle-login-password');

        toggleButton.addEventListener('click', function () {

            const showPassword =
                passwordInput.type === 'password';

            passwordInput.type =
                showPassword ? 'text' : 'password';

            toggleButton.textContent =
                showPassword ? 'Hide' : 'Show';

            toggleButton.setAttribute(
                'aria-label',
                showPassword ? 'Hide password' : 'Show password'
            );

            toggleButton.setAttribute(
                'aria-pressed',
                String(showPassword)
            );

        });
    </script>

</body>

</html>
