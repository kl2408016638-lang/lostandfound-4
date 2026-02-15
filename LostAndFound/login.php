<?php
session_start();
include 'db_connect.php';

$message = "";

if(isset($_POST['login'])) {
    $role = $_POST['role']; // user/admin
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate
    if(empty($name) || empty($password)) {
        $message = "Error: Name and Password are required!";
    } else {
        // Query based on role
        $sql = "SELECT * FROM accounts WHERE role='$role' AND name='$name'";
        
        $result = mysqli_query($connect, $sql);
        
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password (macam dalam database awak - plain text)
            if($password === $user['password']) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['contactnum'] = $user['contactnum'];
                
                // Log admin action
                if(file_exists('admin_logger.php') && $_SESSION['role'] == 'admin') {
                    include 'admin_logger.php';
                    logAdminAction($connect, $_SESSION['user_id'], $_SESSION['name'], 'login', null, null, null, 'Admin logged into system');
                }
                
                // Redirect based on role
                if($role == 'user') {
                    header("Location: user_dashboard.php");
                } else {
                    header("Location: admin_profile.php");
                }
                exit();
                
            } else {
                $message = "Error: Invalid password!";
            }
        } else {
            $message = "Error: User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Surau Ismail Kharofa</title>
    <style>
        /* SAMA CSS SEPERTI AWAK PUNYA - TAK PERLU UBAH */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
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
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            min-height: 600px;
        }
        
        .design-side {
            flex: 1;
            background: linear-gradient(135deg, #2c5530 0%, #3a7c3e 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }
        
        .surau-title {
            font-size: 28px;
            color: white;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .lost-found {
            font-size: 32px;
            color: #FFE5B4;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .surau-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .design-quote {
            color: rgba(255, 248, 240, 0.9);
            font-size: 14px;
            margin-top: 10px;
            font-style: italic;
            max-width: 400px;
            line-height: 1.5;
        }
        
        .form-side {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }
        
        .form-container {
            max-width: 380px;
            width: 100%;
            margin: 0 auto;
        }
        
        .form-title {
            font-size: 28px;
            color: #2c5530;
            margin-bottom: 8px;
            text-align: center;
            font-weight: 600;
        }
        
        .form-subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .role-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .role-option {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s ease;
            font-weight: 500;
            color: #555;
            background: #f9f9f9;
            font-size: 15px;
        }
        
        .role-option:hover {
            border-color: #2c5530;
        }
        
        .role-option.selected {
            border-color: #2c5530;
            background: #2c5530;
            color: white;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            background: white;
            transition: all 0.2s;
            color: #333;
        }
        
        input:focus {
            outline: none;
            border-color: #2c5530;
            box-shadow: 0 0 0 2px rgba(44, 85, 48, 0.1);
        }
        
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #2c5530;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.2s;
        }
        
        .submit-btn:hover {
            background: #3a7c3e;
        }
        
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .register-link a {
            color: #2c5530;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .design-side, .form-side {
                padding: 30px;
            }
            
            .surau-title {
                font-size: 24px;
            }
            
            .lost-found {
                font-size: 28px;
            }
        }
        
        @media (max-width: 480px) {
            .role-selector {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
    <script>
        let currentRole = 'user';
        
        function selectRole(role) {
            currentRole = role;
            
            // Remove selected class
            document.querySelectorAll('.role-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked
            event.target.classList.add('selected');
            
            // Update hidden role input
            document.getElementById('roleInput').value = role;
            
            // Update form labels and placeholders
            if(role === 'user') {
                document.getElementById('nameLabel').textContent = 'Name:';
                document.getElementById('nameInput').placeholder = 'Enter your name';
                document.getElementById('formTitle').textContent = 'Login as User';
                document.getElementById('formSubtitle').textContent = 'Access your user account to report or claim items';
            } else {
                document.getElementById('nameLabel').textContent = 'Name/ID:';
                document.getElementById('nameInput').placeholder = 'Enter your name or ID';
                document.getElementById('formTitle').textContent = 'Login as Admin';
                document.getElementById('formSubtitle').textContent = 'Access admin dashboard to manage lost items';
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Select user by default
            document.querySelector('.role-option:first-child').classList.add('selected');
            document.getElementById('roleInput').value = 'user';
            document.getElementById('nameLabel').textContent = 'Name:';
            document.getElementById('nameInput').placeholder = 'Enter your name';
            document.getElementById('formTitle').textContent = 'Login as User';
            document.getElementById('formSubtitle').textContent = 'Access your user account to report or claim items';
        });
    </script>
</head>
<body>

<div class="container">
    <!-- LEFT SIDE - Design/Visual -->
    <div class="design-side">
        <div>
            <img src="Logo.png" alt="logo" class="logo">
        </div>
        
        <h1 class="surau-title">Surau Ismail Kharofa</h1>
        <div class="lost-found">Lost And Found</div>
        
        <img src="surau_pic.png" alt="surau" class="surau-image">
        
        <p class="design-quote">
            "Welcome back to our community lost and found system"
        </p>
    </div>
    
    <!-- RIGHT SIDE - Form -->
    <div class="form-side">
        <div class="form-container">
            <h2 class="form-title" id="formTitle">Welcome Back</h2>
            <p class="form-subtitle" id="formSubtitle">Sign in to your account to continue</p>
            
            <?php if($message != ""): ?>
                <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Role Selection -->
            <div class="role-selector">
                <div class="role-option" onclick="selectRole('user')">
                    <i style="margin-right: 8px;">👤</i> User Login
                </div>
                <div class="role-option" onclick="selectRole('admin')">
                    <i style="margin-right: 8px;">👑</i> Admin Login
                </div>
            </div>
            
            <form method="POST" action="">
                <!-- Hidden role input -->
                <input type="hidden" name="role" id="roleInput" value="user" required>
                
                <!-- LOGIN FIELDS -->
                <div class="form-group">
                    <label id="nameLabel">Name:</label>
                    <input type="text" name="name" id="nameInput" placeholder="Enter your name" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" name="login" class="submit-btn">
                    Sign In
                </button>
                
                <div class="register-link">
                    Don't have an account? <a href="register.php">Create Account (User Only)</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>