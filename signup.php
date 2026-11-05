
<?php

require_once 'config.php';

// Initialize error message
$error = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve form inputs
    $name = trim($_POST['name'] ?? '');

    $email = filter_var(
        trim($_POST['email'] ?? ''),
        FILTER_VALIDATE_EMAIL
    );

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate registration details
    if ($name === '' || !$email) {

        $error = 'Please enter your name and a valid email address.';

    } elseif (strlen($password) < 8) {

        $error = 'Password must be at least 8 characters long.';

    } elseif ($password !== $confirmPassword) {

        $error = 'Passwords do not match. Please try again.';

    } else {

        try {

            // Insert the new guest account
            $stmt = $pdo->prepare(
                "INSERT INTO users (
                    name,
                    email,
                    password,
                    role
                ) VALUES (?, ?, ?, 'user')"
            );

            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            // Regenerate session ID after registration
            session_regenerate_id(true);

            // Automatically log in the new guest
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = 'user';

            // Redirect to guest dashboard
            header('Location: dashboard.php');
            exit;

        } catch (PDOException $exception) {

            if ($exception->getCode() === '23000') {

                $error = 'That email may already be registered.';

            } else {

                error_log($exception->getMessage());

                $error = 'Registration failed. Please try again.';

            }
        }
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

    <title>Create Account | Azure Haven</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>
        /* Password field layout */
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

        /* Show/Hide button inside the input */
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

        <!-- Registration Banner -->
        <div class="auth-art">

            <p class="eyebrow">
                YOUR NEXT CHAPTER STARTS HERE
            </p>

            <h1>
                Make room
                <br>
                for <em>memories.</em>
            </h1>

        </div>

        <!-- Registration Form -->
        <form class="auth-form" method="post" id="signup-form">

            <p class="eyebrow dark">
                GUEST ACCOUNT
            </p>

            <h2>Create your account</h2>

            <p class="muted">
                Book your stay and manage your reservations.
            </p>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>

                <div class="alert" role="alert">
                    <?= e($error) ?>
                </div>

            <?php endif; ?>

            <!-- Full Name -->
            <label for="signup-name">
                Full name
            </label>

            <input
                type="text"
                id="signup-name"
                name="name"
                required
                maxlength="150"
                autocomplete="name"
                value="<?= e($_POST['name'] ?? '') ?>"
            >

            <!-- Email Address -->
            <label for="signup-email">
                Email address
            </label>

            <input
                type="email"
                id="signup-email"
                name="email"
                required
                autocomplete="email"
                value="<?= e($_POST['email'] ?? '') ?>"
            >

            <!-- Password -->
            <div class="password-field">

                <label for="signup-password">
                    Password
                </label>

                <div class="password-input-wrapper">

                    <input
                        type="password"
                        name="password"
                        id="signup-password"
                        minlength="8"
                        required
                        autocomplete="new-password"
                    >

                    <button
                        type="button"
                        class="password-toggle-btn"
                        aria-label="Show password"
                        aria-pressed="false"
                        onclick="togglePassword('signup-password', this)"
                    >
                        Show
                    </button>

                </div>

            </div>

            <!-- Confirm Password -->
            <div class="password-field">

                <label for="confirm-password">
                    Confirm password
                </label>

                <div class="password-input-wrapper">

                    <input
                        type="password"
                        name="confirm_password"
                        id="confirm-password"
                        minlength="8"
                        required
                        autocomplete="new-password"
                    >

                    <button
                        type="button"
                        class="password-toggle-btn"
                        aria-label="Show confirm password"
                        aria-pressed="false"
                        onclick="togglePassword('confirm-password', this)"
                    >
                        Show
                    </button>

                </div>

            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn full">
                Create account ↗
            </button>

            <!-- Login Link -->
            <p class="form-foot">
                Already registered?

                <a href="login.php">
                    Log in
                </a>
            </p>

        </form>

    </div>

    <!-- Password Toggle and Confirmation Validation -->
    <script>
        function togglePassword(inputId, button) {

            const input = document.getElementById(inputId);
            const showPassword = input.type === 'password';

            input.type = showPassword ? 'text' : 'password';

            button.textContent = showPassword ? 'Hide' : 'Show';

            button.setAttribute(
                'aria-label',
                showPassword ? 'Hide password' : 'Show password'
            );

            button.setAttribute(
                'aria-pressed',
                String(showPassword)
            );
        }

        // Check password confirmation before submission
        document.getElementById('signup-form').addEventListener(
            'submit',
            function (event) {

                const password = document.getElementById(
                    'signup-password'
                ).value;

                const confirmPassword = document.getElementById(
                    'confirm-password'
                ).value;

                if (password !== confirmPassword) {

                    event.preventDefault();

                    alert(
                        'Passwords do not match. Please try again.'
                    );

                    document.getElementById(
                        'confirm-password'
                    ).focus();
                }
            }
        );
    </script>

</body>

</html>
