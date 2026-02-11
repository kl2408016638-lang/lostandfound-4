<?php
session_start();
include 'db_connect.php';

// Check jika user logged in DAN role user
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$message = "";
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

if(isset($_POST['submit'])) {
    // Get form data
    $item_type = $_POST['item_type'] ?? ''; // lost or found
    $type_item = $_POST['type_item'] ?? '';
    $custom_item = $_POST['custom_item'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $location = $_POST['location'] ?? '';
    $location_custom = $_POST['location_custom'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Jika "other" dipilih, guna custom_item
    if($type_item == 'other' && !empty($custom_item)) {
        $type_item = $custom_item;
    }
    
    // Jika "other" location dipilih, guna location_custom
    if($location == 'other' && !empty($location_custom)) {
        $location = $location_custom;
    }
    
    // Handle file upload (picture)
    $picture = '';
    if(isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $file_type = $_FILES['picture']['type'];
        
        if(in_array($file_type, $allowed_types)) {
            // Create uploads directory if not exists
            if(!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            // Generate unique filename
            $file_ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $picture = 'item_' . time() . '_' . $user_id . '.' . $file_ext;
            $upload_path = 'uploads/' . $picture;
            
            if(!move_uploaded_file($_FILES['picture']['tmp_name'], $upload_path)) {
                $picture = '';
                $message = "Error: Failed to upload picture!";
            }
        } else {
            $message = "Error: Only JPG, PNG, and GIF images are allowed!";
        }
    }
    
    if(empty($message)) {
        // Insert into database
        $sql = "INSERT INTO items 
                (user_id, user_name, item_type, type_item, date, time, location, picture, description, status, created_at) 
                VALUES 
                ('$user_id', '$user_name', '$item_type', '$type_item', '$date', '$time', '$location', '$picture', '$description', 'pending', NOW())";
        
        if(mysqli_query($connect, $sql)) {
            $message = "Item reported successfully!";
            // Clear form
            $_POST = array();
        } else {
            $message = "Error: " . mysqli_error($connect);
        }
    }
}

// Include sidebar navigation
include 'sidebar_nav.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Item - Surau Ismail Kharofa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        
        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .item-type-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .item-type-btn {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
        }
        
        .item-type-btn:hover {
            border-color: #4CAF50;
            background: #f0f9f0;
        }
        
        .item-type-btn.selected {
            border-color: #4CAF50;
            background: #4CAF50;
            color: white;
        }
        
        .lost-btn.selected {
            background: #ff9800;
            border-color: #ff9800;
        }
        
        .lost-btn:hover {
            border-color: #ff9800;
            background: #fff3e0;
        }
        
        .submit-btn {
            background: #4CAF50;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            margin-top: 20px;
            transition: background 0.3s;
        }
        
        .submit-btn:hover {
            background: #45a049;
        }
        
        .file-info {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .hidden {
            display: none;
        }
        
        .nav-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .nav-links a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        
        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Report Item</h1>
    <p class="subtitle">Surau Ismail Kharofa - Lost and Found System</p>
    
    <?php if($message != ""): ?>
        <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data" id="itemForm">
        <!-- Item Type: Lost or Found -->
        <div class="form-group">
            <label>Report Type:</label>
            <div class="item-type-selector">
                <div class="item-type-btn found-btn selected" onclick="selectItemType('found')">
                    📍 Report Found Item
                </div>
                <div class="item-type-btn lost-btn" onclick="selectItemType('lost')">
                    🔍 Report Lost Item
                </div>
            </div>
            <input type="hidden" name="item_type" id="item_type" value="found" required>
        </div>
        
        <!-- Type Item -->
        <div class="form-group">
            <label for="type_item">Type of Item:</label>
            <select name="type_item" id="type_item" required onchange="toggleCustomItem()">
                <option value="">-- Select Item Type --</option>
                <option value="wallet">Wallet/Purse</option>
                <option value="phone">Mobile Phone</option>
                <option value="keys">Keys</option>
                <option value="documents">Documents</option>
                <option value="jewelry">Jewelry</option>
                <option value="clothing">Clothing</option>
                <option value="books">Books</option>
                <option value="electronics">Electronics</option>
                <option value="other">Other (Please specify)</option>
            </select>
        </div>
        
        <!-- Custom Item Input (show only when "other" selected) -->
        <div class="form-group hidden" id="customItemGroup">
            <label for="custom_item">Please specify the item:</label>
            <input type="text" name="custom_item" id="custom_item" placeholder="e.g., Water bottle, Umbrella, Glasses, etc.">
        </div>
        
        <div class="form-row">
            <!-- Date -->
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required>
            </div>
            
            <!-- Time -->
            <div class="form-group">
                <label for="time">Time:</label>
                <input type="time" name="time" id="time" required>
            </div>
        </div>
        
        <!-- Location -->
        <div class="form-group">
            <label for="location">Location:</label>
            <select name="location" id="location" required onchange="toggleCustomLocation()">
                <option value="">-- Select Location --</option>
                <option value="main_hall">Main Prayer Hall</option>
                <option value="ablution_area">Ablution Area</option>
                <option value="parking">Parking Area</option>
                <option value="office">Office</option>
                <option value="classroom">Classroom</option>
                <option value="library">Library</option>
                <option value="cafeteria">Cafeteria</option>
                <option value="entrance">Main Entrance</option>
                <option value="other">Other Area (Please specify)</option>
            </select>
        </div>
        
        <!-- Custom Location Input -->
        <div class="form-group hidden" id="customLocationGroup">
            <label for="location_custom">Please specify location:</label>
            <input type="text" name="location_custom" id="location_custom" placeholder="e.g., Near shoe rack, Outside toilet, etc.">
        </div>
        
        <!-- Picture -->
        <div class="form-group">
            <label for="picture">Picture of Item:</label>
            <input type="file" name="picture" id="picture" accept="image/*">
            <div class="file-info">Optional: Upload photo of the item (JPG, PNG, GIF)</div>
        </div>
        
        <!-- Description -->
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" placeholder="Describe the item in detail (color, brand, size, distinguishing features, etc.)" required></textarea>
        </div>
        
        <button type="submit" name="submit" class="submit-btn" id="submitBtn">
            Submit Found Item Report
        </button>
    </form>
    
    
</div>

<script>
    // Set default date to today
    document.getElementById('date').valueAsDate = new Date();
    
    // Set default time to current time
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    document.getElementById('time').value = `${hours}:${minutes}`;
    
    // Item type selection
    function selectItemType(type) {
        document.getElementById('item_type').value = type;
        
        // Update button styles
        document.querySelector('.found-btn').classList.remove('selected');
        document.querySelector('.lost-btn').classList.remove('selected');
        
        if(type === 'found') {
            document.querySelector('.found-btn').classList.add('selected');
            document.getElementById('submitBtn').textContent = 'Submit Found Item Report';
        } else {
            document.querySelector('.lost-btn').classList.add('selected');
            document.getElementById('submitBtn').textContent = 'Submit Lost Item Report';
        }
    }
    
    // Show/hide custom item input
    function toggleCustomItem() {
        const itemType = document.getElementById('type_item').value;
        const customItemGroup = document.getElementById('customItemGroup');
        
        if(itemType === 'other') {
            customItemGroup.classList.remove('hidden');
            document.getElementById('custom_item').required = true;
        } else {
            customItemGroup.classList.add('hidden');
            document.getElementById('custom_item').required = false;
        }
    }
    
    // Show/hide custom location input
    function toggleCustomLocation() {
        const location = document.getElementById('location').value;
        const customLocationGroup = document.getElementById('customLocationGroup');
        
        if(location === 'other') {
            customLocationGroup.classList.remove('hidden');
            document.getElementById('location_custom').required = true;
        } else {
            customLocationGroup.classList.add('hidden');
            document.getElementById('location_custom').required = false;
        }
    }
    
    // Form validation
    document.getElementById('itemForm').addEventListener('submit', function(e) {
        const itemType = document.getElementById('type_item').value;
        const customItem = document.getElementById('custom_item').value;
        
        // If "other" selected but no custom item specified
        if(itemType === 'other' && !customItem.trim()) {
            e.preventDefault();
            alert('Please specify the item type in the "Please specify the item" field.');
            document.getElementById('custom_item').focus();
            return false;
        }
        
        const location = document.getElementById('location').value;
        const customLocation = document.getElementById('location_custom').value;
        
        // If "other" location selected but no custom location specified
        if(location === 'other' && !customLocation.trim()) {
            e.preventDefault();
            alert('Please specify the location in the "Please specify location" field.');
            document.getElementById('location_custom').focus();
            return false;
        }
    });
</script>

</body>
</html>