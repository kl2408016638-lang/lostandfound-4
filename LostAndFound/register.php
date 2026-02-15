<?php
session_start();
include 'db_connect.php';

$message = "";

if(isset($_POST['register'])) {
    // AUTO SET ROLE = USER (admin tak boleh register sendiri)
    $role = 'user'; 
    $name = trim($_POST['name'] ?? '');
    $contactnum = trim($_POST['contactnum'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate semua field wajib
    if(empty($name) || empty($contactnum) || empty($email) || empty($password)) {
        $message = "Error: All fields are required!";
    } else {
        // Check if email already exists
        $check_email_sql = "SELECT * FROM accounts WHERE email='$email'";
        $email_result = mysqli_query($connect, $check_email_sql);
        
        if(mysqli_num_rows($email_result) > 0) {
            $message = "Error: Email already registered!";
        } else {
            // SIMPLE: Store password as plain text (macam dalam database awak)
            // Atau guna password_hash() jika nak secure
            $hashed_password = $password; // Plain text (macam current system)
            // $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Jika nak secure
            
            $sql = "INSERT INTO accounts (role, name, contactnum, email, password)
                    VALUES ('$role', '$name', '$contactnum', '$email', '$hashed_password')";

            if(mysqli_query($connect, $sql)){
                $message = "User registration successful! Please login.";
            } else {
                $message = "Error: ". mysqli_error($connect);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Surau Ismail Kharofa</title>
    <style>
        /* SAMA CSS DARI SEBELUM - TAK PERLU UBAH */
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
            margin-bottom: 20px;
            font-size: 14px;
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
        
        .required {
            color: #dc3545;
        }
        
        input[type="text"],
        input[type="email"],
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
            margin-top: 10px;
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .login-link a {
            color: #2c5530;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
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
    </style>
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
            "Helping our community reunite with lost belongings"
        </p>
    </div>
    
    <!-- RIGHT SIDE - Form -->
    <div class="form-side">
        <div class="form-container">
            <h2 class="form-title">Register as User</h2>
            <p class="form-subtitle">Create a user account to report or claim lost items</p>
            
            
            <?php if($message != ""): ?>
                <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <!-- Hidden role input - AUTO SET AS USER -->
                <input type="hidden" name="role" value="user">
                
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label>Contact Number <span class="required">*</span></label>
                    <input type="text" name="contactnum" placeholder="e.g., 0123456789" required>
                </div>
                
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" name="email" placeholder="e.g., yourname@example.com" required>
                </div>
                
                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <input type="password" name="password" placeholder="Create a password" required>
                </div>
                
                <button type="submit" name="register" class="submit-btn">
                    Create User Account
                </button>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Login Here</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>