<?php
session_start();
include 'db_connect.php';

// Check jika user logged in DAN role admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";
$user_id = $_SESSION['user_id'];

// Get admin data
$sql = "SELECT * FROM accounts WHERE id='$user_id' AND role='admin'";
$result = mysqli_query($connect, $sql);
$admin = mysqli_fetch_assoc($result);

// List of available avatars
$avatars = [
    'avatar1.png', 'avatar2.png', 'avatar3.png', 'avatar4.png',
    'avatar5.png', 'avatar6.png', 'avatar7.png', 'avatar8.png',
    'avatar9.png', 'avatar10.png', 'avatar11.png', 'avatar12.png'
];

// Handle profile update
if(isset($_POST['update'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name'] ?? $admin['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email'] ?? $admin['email']);
    $password = $_POST['password'] ?? '';
    $avatar_choice = $_POST['avatar'] ?? '';
    
    // Handle profile picture
    $profile_pic = $admin['profile_pic']; // Default to current
    
    // Option 1: Upload custom image
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $file_type = $_FILES['profile_image']['type'];
        
        if(in_array($file_type, $allowed_types)) {
            // Create profile_pics directory if not exists
            if(!is_dir('profile_pics')) {
                mkdir('profile_pics', 0777, true);
            }
            
            // Generate unique filename
            $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $profile_pic = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
            $upload_path = 'profile_pics/' . $profile_pic;
            
            if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                // Delete old profile picture if not default avatar
                if($admin['profile_pic'] && !str_starts_with($admin['profile_pic'], 'avatar_') && file_exists('profile_pics/' . $admin['profile_pic'])) {
                    unlink('profile_pics/' . $admin['profile_pic']);
                }
            } else {
                $message = "Error: Failed to upload profile picture!";
                $profile_pic = $admin['profile_pic']; // Keep old one
            }
        } else {
            $message = "Error: Only JPG, PNG, and GIF images are allowed!";
        }
    }
    // Option 2: Select avatar
    elseif(!empty($avatar_choice)) {
        $profile_pic = $avatar_choice;
        // Delete old uploaded picture if exists
        if($admin['profile_pic'] && !str_starts_with($admin['profile_pic'], 'avatar_') && file_exists('profile_pics/' . $admin['profile_pic'])) {
            unlink('profile_pics/' . $admin['profile_pic']);
        }
    }
    
    // Determine new password
    if(!empty($password)) {
        $new_password = $password;
    } else {
        $new_password = $admin['password']; // Keep current
    }
    
    // If no errors, update database
    if(empty($message)) {
        $update_sql = "UPDATE accounts SET 
                       name='$name', 
                       email='$email', 
                       password='$new_password',
                       profile_pic='$profile_pic' 
                       WHERE id='$user_id' AND role='admin'";
        
        if(mysqli_query($connect, $update_sql)) {
            $message = "Profile updated successfully!";
            // Refresh admin data
            $result = mysqli_query($connect, $sql);
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['name'] = $admin['name'];
            $_SESSION['profile_pic'] = $admin['profile_pic'];
        } else {
            $message = "Error: " . mysqli_error($connect);
        }
    }
}

// Include admin sidebar navigation
include 'admin_sidebar_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Surau Ismail Kharofa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .main-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .profile-container {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .profile-left {
            flex: 1;
        }
        
        .profile-right {
            flex: 1;
        }
        
        .profile-card {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        
        .card-label {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .card-value {
            font-size: 20px;
            color: #333;
            font-weight: bold;
        }
        
        .profile-pic-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #ddd;
        }
        
        .current-profile-pic {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        
        .avatar-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 8px;
        }
        
        .avatar-option {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .avatar-option:hover {
            transform: scale(1.1);
            border-color: #3498db;
        }
        
        .avatar-option.selected {
            border-color: #e74c3c;
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
        }
        
        .upload-section {
            margin-top: 20px;
            padding: 20px;
            background: #e8f4ff;
            border-radius: 8px;
            border: 2px dashed #3498db;
        }
        
        .upload-btn {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .upload-btn:hover {
            background: #2980b9;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #2c3e50 0%, #e74c3c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }
        
        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .tab-container {
            margin-top: 20px;
        }
        
        .tab-buttons {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        
        .tab-btn {
            padding: 12px 25px;
            background: #f8f9fa;
            border: none;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-weight: 600;
            color: #7f8c8d;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            background: #3498db;
            color: white;
        }
        
        .tab-content {
            display: none;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 0 8px 8px 8px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            margin-top: 10px;
            border: 3px solid #3498db;
            display: none;
        }
        
        @media (max-width: 1024px) {
            .profile-container {
                flex-direction: column;
            }
            
            .avatar-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .avatar-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .current-profile-pic {
                width: 150px;
                height: 150px;
            }
        }
        
        @media (max-width: 480px) {
            .avatar-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .tab-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 style="color: #2c3e50; margin-bottom: 10px; font-size: 28px;">
                        <i class="fas fa-user-cog" style="color: #e74c3c; margin-right: 10px;"></i>
                        Admin Profile
                    </h1>
                    <p style="color: #7f8c8d;">Manage your administrator account and profile picture</p>
                </div>
                
                <div style="background: #fff5f5; color: #e74c3c; padding: 10px 20px; 
                            border-radius: 10px; font-weight: 600; border: 2px solid #e74c3c;">
                    <i class="fas fa-shield-alt" style="margin-right: 8px;"></i>
                    ADMINISTRATOR
                </div>
            </div>
            
            <?php if($message != ""): ?>
                <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-container">
                <!-- LEFT: Profile Information & Picture -->
                <div class="profile-left">
                    <div class="profile-pic-section">
                        <h3 style="color: #2c3e50; margin-bottom: 15px;">
                            <i class="fas fa-user-circle" style="color: #3498db; margin-right: 10px;"></i>
                            Profile Picture
                        </h3>
                        
                        <?php
                        // Determine profile picture path
                        $profile_pic_path = 'images/avatars/avatar_default.png'; // Default
                        
                        if(!empty($admin['profile_pic'])) {
                            if(str_starts_with($admin['profile_pic'], 'avatar_')) {
                                $profile_pic_path = 'images/avatars/' . $admin['profile_pic'];
                            } else {
                                $profile_pic_path = 'profile_pics/' . $admin['profile_pic'];
                            }
                        }
                        ?>
                        
                        <img src="<?php echo $profile_pic_path; ?>" 
                             alt="Profile Picture" 
                             class="current-profile-pic"
                             id="currentProfilePic">
                        
                        <p style="color: #7f8c8d; margin-top: 10px; font-size: 14px;">
                            Current profile picture
                        </p>
                    </div>
                    
                    <!-- Profile Information Cards -->
                    <div class="profile-card" style="border-left-color: #28a745;">
                        <div class="card-label">NAME</div>
                        <div class="card-value"><?php echo htmlspecialchars($admin['name']); ?></div>
                    </div>
                    
                    <div class="profile-card" style="border-left-color: #17a2b8;">
                        <div class="card-label">EMAIL</div>
                        <div class="card-value"><?php echo htmlspecialchars($admin['email'] ?? 'Not set'); ?></div>
                    </div>
                    
                    <div class="profile-card" style="border-left-color: #e74c3c;">
                        <div class="card-label">
                            <i class="fas fa-key" style="margin-right: 8px;"></i>PASSWORD
                        </div>
                        <div class="card-value" style="font-family: 'Courier New', monospace; letter-spacing: 2px;">
                            <?php echo htmlspecialchars($admin['password']); ?>
                        </div>
                    </div>
                    
                    <div class="profile-card" style="border-left-color: #6c757d;">
                        <div class="card-label">ADMIN ID</div>
                        <div class="card-value"><?php echo $admin['id']; ?></div>
                    </div>
                </div>
                
                <!-- RIGHT: Edit Form -->
                <div class="profile-right">
                    <h2 style="color: #2c3e50; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #eee;">
                        <i class="fas fa-edit" style="color: #e74c3c; margin-right: 10px;"></i>
                        Edit Profile
                    </h2>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user" style="color: #3498db;"></i>Name:
                            </label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" 
                                   required class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope" style="color: #17a2b8;"></i>Email:
                            </label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" 
                                   required class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock" style="color: #e74c3c;"></i>New Password:
                                <span style="font-size: 12px; color: #6c757d; font-weight: normal;">
                                    (leave blank to keep current)
                                </span>
                            </label>
                            <input type="text" name="password" placeholder="Enter new password"
                                   class="form-input" style="background: #fff5f5; border-color: #f5c6cb;">
                        </div>
                        
                        <!-- Profile Picture Tabs -->
                        <div class="tab-container">
                            <div class="tab-buttons">
                                <button type="button" class="tab-btn active" onclick="openTab('uploadTab')">
                                    <i class="fas fa-upload"></i> Upload Image
                                </button>
                                <button type="button" class="tab-btn" onclick="openTab('avatarTab')">
                                    <i class="fas fa-user-circle"></i> Choose Avatar
                                </button>
                            </div>
                            
                            <!-- Upload Tab -->
                            <div id="uploadTab" class="tab-content active">
                                <div class="upload-section">
                                    <label class="form-label">
                                        <i class="fas fa-camera" style="color: #3498db;"></i>
                                        Upload Custom Image:
                                    </label>
                                    <input type="file" name="profile_image" id="profileImage" 
                                           accept="image/*" style="display: none;" onchange="previewImage(this)">
                                    
                                    <button type="button" class="upload-btn" onclick="document.getElementById('profileImage').click()">
                                        <i class="fas fa-cloud-upload-alt"></i> Choose File
                                    </button>
                                    
                                    <div id="fileName" style="margin-top: 10px; color: #3498db; font-size: 14px;"></div>
                                    <img id="imagePreview" class="preview-image" alt="Preview">
                                    
                                    <p style="color: #666; font-size: 12px; margin-top: 10px;">
                                        Maximum file size: 2MB<br>
                                        Allowed formats: JPG, PNG, GIF
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Avatar Tab -->
                            <div id="avatarTab" class="tab-content">
                                <label class="form-label">
                                    <i class="fas fa-users" style="color: #9b59b6;"></i>
                                    Select an Avatar:
                                </label>
                                
                                <div class="avatar-grid" id="avatarGrid">
                                    <?php foreach($avatars as $avatar): ?>
                                    <div onclick="selectAvatar('<?php echo $avatar; ?>')">
                                        <img src="images/avatars/<?php echo $avatar; ?>" 
                                             alt="Avatar" 
                                             class="avatar-option <?php echo ($admin['profile_pic'] == $avatar) ? 'selected' : ''; ?>"
                                             data-avatar="<?php echo $avatar; ?>">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="avatar" id="selectedAvatar" value="<?php echo $admin['profile_pic']; ?>">
                            </div>
                        </div>
                        
                        <button type="submit" name="update" class="submit-btn">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
    
    <script>
        // Tab switching function
        function openTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.currentTarget.classList.add('active');
            
            // Reset avatar selection when switching to upload tab
            if(tabName === 'uploadTab') {
                document.getElementById('selectedAvatar').value = '';
                document.querySelectorAll('.avatar-option.selected').forEach(avatar => {
                    avatar.classList.remove('selected');
                });
            }
        }
        
        // Avatar selection function
        function selectAvatar(avatarName) {
            // Remove selection from all avatars
            document.querySelectorAll('.avatar-option').forEach(avatar => {
                avatar.classList.remove('selected');
            });
            
            // Add selection to clicked avatar
            event.currentTarget.querySelector('.avatar-option').classList.add('selected');
            
            // Set hidden input value
            document.getElementById('selectedAvatar').value = avatarName;
            
            // Update preview
            document.getElementById('currentProfilePic').src = 'images/avatars/' + avatarName;
            
            // Clear file input if any
            document.getElementById('profileImage').value = '';
            document.getElementById('fileName').textContent = '';
            document.getElementById('imagePreview').style.display = 'none';
        }
        
        // Image preview function
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                document.getElementById('fileName').textContent = 'Selected: ' + fileName;
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    
                    // Update current profile picture preview
                    document.getElementById('currentProfilePic').src = e.target.result;
                    
                    // Clear avatar selection
                    document.getElementById('selectedAvatar').value = '';
                    document.querySelectorAll('.avatar-option.selected').forEach(avatar => {
                        avatar.classList.remove('selected');
                    });
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set current avatar as selected
            const currentAvatar = '<?php echo $admin['profile_pic']; ?>';
            if(currentAvatar && currentAvatar.startsWith('avatar_')) {
                document.querySelector(`.avatar-option[data-avatar="${currentAvatar}"]`)?.classList.add('selected');
                document.getElementById('selectedAvatar').value = currentAvatar;
            }
        });
        
        // File size validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('profileImage');
            if(fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
                if(fileSize > 2) {
                    e.preventDefault();
                    alert('File size must be less than 2MB');
                    return false;
                }
            }
        });
    </script>
</body>
</html>