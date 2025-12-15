<?php
    require_once 'koneksi.php'; 
    $error_message = '';
    $success_message = '';
    if (!$koneksi) {
        $error_message = "Koneksi database gagal!";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $koneksi) {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $repeat_password = $_POST['repeat_password'] ?? '';
        $role = $_POST['role'] ?? '';
        $terms = isset($_POST['terms']);

        if (empty($email) || empty($password) || empty($repeat_password) || empty($role)) {
            $error_message = "Semua field harus diisi!";
        } 
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Format email tidak valid!";
        } 
        elseif ($password !== $repeat_password) {
            $error_message = "Password tidak cocok!";
        } 
        elseif (strlen($password) < 8) {
            $error_message = "Password minimal 8 karakter!";
        }
        elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $error_message = "Password harus mengandung huruf dan angka!";
        }
        elseif (!in_array($role, ['karyawan', 'manager'])) {
            $error_message = "Role tidak valid!";
        }
        elseif (!$terms) {
            $error_message = "Anda harus menyetujui terms & conditions!";
        } 
        else {
            $email = mysqli_real_escape_string($koneksi, $email);
            $role = mysqli_real_escape_string($koneksi, $role);

            $check_query = "SELECT id_user FROM users WHERE email = '$email'";
            $check_result = mysqli_query($koneksi, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                $error_message = "Email sudah terdaftar! Silakan gunakan email lain atau <a href='login.php'>login</a>.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                $insert_query = "INSERT INTO users (email, password, role) 
                                VALUES ('$email', '$hashed_password', '$role')";
                
                if (mysqli_query($koneksi, $insert_query)) {
                    $success_message = "Registrasi berhasil! Silakan <a href='login.php'>login</a> untuk melanjutkan.";
                    
                    $_POST = array();
                } else {
                    $error_message = "Error: " . mysqli_error($koneksi);
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Your Social Campaigns</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            gap: 60px;
            align-items: center;
        }

        .left-section {
            flex: 1;
            color: #333;
        }

        .left-section h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .left-section p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        .form-card {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 550px;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #999;
            font-size: 0.9rem;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #000;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0066ff;
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%23666' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
            font-size: 1.2rem;
            padding: 4px;
        }

        .password-toggle:hover {
            color: #666;
        }

        .password-hint {
            font-size: 0.75rem;
            color: #999;
            margin-top: 6px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: 0.85rem;
            color: #666;
            cursor: pointer;
            margin: 0;
        }

        .checkbox-group a {
            color: #0066ff;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            color: #999;
            font-size: 0.85rem;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e5e5e5;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
        }

        .social-btn:hover {
            border-color: #ccc;
            background: #f9f9f9;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #0066ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background: #0052cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .signin-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
        }

        .signin-link a {
            color: #0066ff;
            text-decoration: none;
            font-weight: 500;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 40px;
        }

        .footer-links a {
            color: #0066ff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 968px) {
            .container {
                flex-direction: column;
                gap: 40px;
            }

            .left-section {
                text-align: center;
            }

            .form-card {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .form-card {
                padding: 32px 24px;
            }

            .social-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>Evalify</h1>
            <p>Penilaian kinerja karyawan berbasis proyek</p>
            
            <div class="footer-links">
                <a href="#">Terms</a>
                <a href="#">Plans</a>
                <a href="#">Contact Us</a>
            </div>
        </div>

        <div class="form-card">
            <div class="form-header">
                <h2>Sign Up</h2>
                <p>Your Social Campaigns</p>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="signupForm">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <span id="eyeIcon">👁</span>
                        </button>
                    </div>
                    <p class="password-hint">Use 8 or more characters with a mix of letters, numbers & symbols.</p>
                </div>

                <div class="form-group">
                    <label>Repeat Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="repeat_password" id="repeat_password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('repeat_password')">
                            <span id="eyeIcon2">👁</span>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Register as</label>
                    <select name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="karyawan" <?php echo (isset($_POST['role']) && $_POST['role'] === 'karyawan') ? 'selected' : ''; ?>>Karyawan</option>
                        <option value="manager" <?php echo (isset($_POST['role']) && $_POST['role'] === 'manager') ? 'selected' : ''; ?>>Manager</option>
                    </select>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">I accept the <a href="#">Term</a></label>
                </div>

                <div class="divider">Or with</div>

                <div class="social-buttons">
                    <a href="#" class="social-btn">
                        <svg width="18" height="18" viewBox="0 0 18 18">
                            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
                            <path fill="#34A853" d="M9.003 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.96v2.332C2.44 15.983 5.485 18 9.003 18z"/>
                            <path fill="#FBBC05" d="M3.964 10.71c-.18-.54-.282-1.117-.282-1.71s.102-1.17.282-1.71V4.958H.957C.347 6.173 0 7.548 0 9s.348 2.827.957 4.042l3.007-2.332z"/>
                            <path fill="#EA4335" d="M9.003 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.464.891 11.426 0 9.003 0 5.485 0 2.44 2.017.96 4.958L3.967 7.29c.708-2.127 2.692-3.71 5.036-3.71z"/>
                        </svg>
                        Sign Up with Google
                    </a>
                    <a href="#" class="social-btn">
                        <svg width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.94 5.19A7.2 7.2 0 009 1.8c-3.977 0-7.2 3.223-7.2 7.2 0 1.414.41 2.73 1.117 3.844L1.8 16.2l3.47-1.088A7.165 7.165 0 009 16.2c3.977 0 7.2-3.223 7.2-7.2 0-1.932-.754-3.745-2.123-5.073z" fill="#000"/>
                        </svg>
                        Sign Up with Apple
                    </a>
                </div>

                <button type="submit" class="submit-btn">Sign Up</button>

                <div class="signin-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById(id === 'password' ? 'eyeIcon' : 'eyeIcon2');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '👁️‍🗨️';
            } else {
                input.type = 'password';
                icon.textContent = '👁';
            }
        }

        // Validasi password match real-time
        document.getElementById('repeat_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const repeatPassword = this.value;
            
            if (repeatPassword && password !== repeatPassword) {
                this.setCustomValidity('Password tidak cocok!');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>