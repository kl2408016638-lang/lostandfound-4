<?php
// admin_sidebar_nav.php
// Check jika session sudah start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'db_connect.php';

// Check jika user logged in sebagai admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get fresh user data for profile picture
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT profile_pic, name FROM accounts WHERE id='$user_id'";
$user_result = mysqli_query($connect, $user_sql);
$user_data = mysqli_fetch_assoc($user_result);

$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
        display: flex;
        min-height: 100vh;
        background-color: #f5f5f5;
    }
    
    /* ADMIN SIDEBAR - DARK THEME */
    .admin-sidebar {
        width: 280px;
        background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding: 20px 0;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        overflow-y: auto;
    }
    
    /* TOP HEADER BAR - ADMIN */
    .admin-top-header {
        position: fixed;
        top: 0;
        left: 280px;
        right: 0;
        height: 70px;
        background: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 40px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        z-index: 999;
        border-bottom: 3px solid #e74c3c;
    }
    
    .admin-logo {
        text-align: center;
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }
    
    .admin-logo h2 {
        color: white;
        font-size: 24px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .admin-logo p {
        color: #6c8bc7;
        font-size: 13px;
        font-weight: 300;
    }
    
    .admin-badge {
        background: #e74c3c;
        color: white;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 1px;
    }
    
    .admin-nav-menu {
        padding: 0 15px;
    }
    
    .admin-nav-title {
        color: #6c8bc7;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 20px 15px 10px 15px;
        margin-top: 10px;
        font-weight: 600;
    }
    
    .admin-nav-item {
        display: flex;
        align-items: center;
        padding: 14px 15px;
        color: #b8c7e0;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }
    
    .admin-nav-item:hover {
        background: rgba(255, 255, 255, 0.08);
        color: white;
        transform: translateX(5px);
    }
    
    .admin-nav-item.active {
        background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }
    
    .admin-nav-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: white;
        border-radius: 0 2px 2px 0;
    }
    
    .admin-nav-icon {
        margin-right: 15px;
        font-size: 18px;
        width: 24px;
        text-align: center;
    }
    
    .admin-nav-text {
        font-size: 15px;
        font-weight: 500;
        flex: 1;
    }
    
    .nav-arrow {
        font-size: 12px;
        transition: transform 0.3s;
    }
    
    .nav-arrow.rotated {
        transform: rotate(90deg);
    }
    
    /* SUBMENU STYLES */
    .submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        margin: 5px 0;
    }
    
    .submenu.open {
        max-height: 300px;
    }
    
    .submenu-item {
        display: flex;
        align-items: center;
        padding: 12px 15px 12px 45px;
        color: #a0b1d0;
        text-decoration: none;
        transition: all 0.3s;
        border-left: 2px solid transparent;
    }
    
    .submenu-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
        border-left-color: #3498db;
    }
    
    .submenu-item.active {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
        border-left-color: #3498db;
    }
    
    .submenu-icon {
        margin-right: 10px;
        font-size: 14px;
        width: 20px;
        text-align: center;
    }
    
    /* TOP HEADER STYLES - ADMIN */
    .admin-page-title {
        font-size: 24px;
        color: #2c3e50;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .admin-title-icon {
        color: #e74c3c;
        font-size: 20px;
    }
    
    .admin-header-right {
        display: flex;
        align-items: center;
        gap: 25px;
    }
    
    .admin-notification-btn {
        position: relative;
        background: none;
        border: none;
        color: #7f8c8d;
        font-size: 22px;
        cursor: pointer;
        padding: 10px;
        border-radius: 50%;
        transition: all 0.3s;
    }
    
    .admin-notification-btn:hover {
        background: #f8f9fa;
        color: #e74c3c;
        transform: rotate(15deg);
    }
    
    .admin-notification-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #e74c3c;
        color: white;
        font-size: 10px;
        padding: 3px 7px;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
        font-weight: bold;
    }
    
    .admin-user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 8px 15px;
        border-radius: 25px;
        transition: all 0.3s;
        background: #f8f9fa;
        border: 2px solid transparent;
    }
    
    .admin-user-profile:hover {
        background: white;
        border-color: #e74c3c;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.1);
    }
    
    .admin-user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid white;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }
    
    .admin-user-info {
        display: flex;
        flex-direction: column;
    }
    
    .admin-user-name {
        font-weight: 700;
        color: #2c3e50;
        font-size: 15px;
    }
    
    .admin-user-role {
        color: #e74c3c;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .admin-logout-btn {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
    }
    
    .admin-logout-btn:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(231, 76, 60, 0.3);
    }
    
    /* MAIN CONTENT AREA - ADMIN */
    .admin-main-content {
        flex: 1;
        margin-left: 280px;
        margin-top: 70px;
        padding: 40px;
        min-height: calc(100vh - 70px);
        background: #f8f9fa;
    }
    
    /* ADMIN DROPDOWN MENU */
    .admin-dropdown {
        position: relative;
    }
    
    .admin-dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        min-width: 220px;
        display: none;
        z-index: 1001;
        border: 1px solid #eee;
        overflow: hidden;
    }
    
    .admin-dropdown:hover .admin-dropdown-menu {
        display: block;
    }
    
    .admin-dropdown-item {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: #2c3e50;
        text-decoration: none;
        transition: all 0.3s;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .admin-dropdown-item:hover {
        background: #f8f9fa;
        color: #e74c3c;
        padding-left: 25px;
    }
    
    .admin-dropdown-item:last-child {
        border-bottom: none;
        background: #fff5f5;
    }
    
    .admin-dropdown-icon {
        margin-right: 12px;
        font-size: 16px;
        width: 20px;
        text-align: center;
    }
    
    /* ADMIN NOTIFICATION PANEL */
    .admin-notification-panel {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        width: 380px;
        max-height: 450px;
        overflow-y: auto;
        display: none;
        z-index: 1001;
        border: 1px solid #eee;
    }
    
    .admin-notification-header {
        padding: 20px;
        border-bottom: 1px solid #f8f9fa;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
    }
    
    .admin-notification-item {
        padding: 18px 20px;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .admin-notification-item:hover {
        background: #f8f9fa;
    }
    
    .admin-notification-item.unread {
        background: #ffeaea;
        border-left: 4px solid #e74c3c;
    }
    
    .admin-notification-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .admin-notification-message {
        color: #7f8c8d;
        font-size: 14px;
        margin-bottom: 8px;
        line-height: 1.5;
    }
    
    .admin-notification-time {
        color: #95a5a6;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    /* SYSTEM STATS BADGE */
    .system-stats {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    
    .stat-badge {
        background: rgba(255, 255, 255, 0.1);
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 11px;
        color: #6c8bc7;
        border: 1px solid rgba(108, 139, 199, 0.3);
    }
    
    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .admin-sidebar {
            width: 240px;
        }
        
        .admin-top-header {
            left: 240px;
            padding: 0 30px;
        }
        
        .admin-main-content {
            margin-left: 240px;
            padding: 30px;
        }
    }
    
    @media (max-width: 768px) {
        .admin-sidebar {
            width: 70px;
        }
        
        .admin-sidebar .admin-nav-text,
        .admin-sidebar .admin-logo h2,
        .admin-sidebar .admin-logo p,
        .admin-sidebar .admin-nav-title,
        .admin-sidebar .system-stats,
        .admin-sidebar .nav-arrow {
            display: none;
        }
        
        .admin-sidebar .admin-nav-item {
            justify-content: center;
            padding: 18px;
        }
        
        .admin-sidebar .admin-nav-icon {
            margin-right: 0;
            font-size: 20px;
        }
        
        .admin-sidebar .submenu-item {
            padding: 15px;
            justify-content: center;
        }
        
        .admin-sidebar .submenu-icon {
            margin-right: 0;
            font-size: 16px;
        }
        
        .admin-top-header {
            left: 70px;
            padding: 0 20px;
        }
        
        .admin-main-content {
            margin-left: 70px;
            padding: 20px;
        }
        
        .admin-user-info {
            display: none;
        }
        
        .admin-page-title {
            font-size: 20px;
        }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle submenu function
        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const arrow = document.querySelector(`[data-submenu="${submenuId}"] .nav-arrow`);
            
            if (submenu && arrow) {
                submenu.classList.toggle('open');
                arrow.classList.toggle('rotated');
            }
        }
        
        // Set active menu berdasarkan current page
        function setActiveMenu() {
            const currentPage = '<?php echo $current_page; ?>';
            
            document.querySelectorAll('.admin-nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            document.querySelectorAll('.submenu-item').forEach(item => {
                item.classList.remove('active');
            });
            
            const submenuItems = {
                'list_found.php': { submenu: 'listsSubmenu', item: 'founditems' },
                'list_lost.php': { submenu: 'listsSubmenu', item: 'lostitems' },
                'admin_statistics.php': { submenu: 'dashboardSubmenu', item: 'statistics' },
                'admin_trail.php': { submenu: 'dashboardSubmenu', item: 'trail' },
                'admin_users.php': { submenu: 'dashboardSubmenu', item: 'users' },
                'archive_items.php': { submenu: 'dashboardSubmenu', item: 'archive' }
            };
            
            if (submenuItems[currentPage]) {
                const { submenu, item } = submenuItems[currentPage];
                
                const submenuEl = document.getElementById(submenu);
                const arrow = document.querySelector(`[data-submenu="${submenu}"] .nav-arrow`);
                
                if (submenuEl && arrow) {
                    submenuEl.classList.add('open');
                    arrow.classList.add('rotated');
                }
                
                const submenuItem = document.querySelector(`.submenu-item[data-page="${item}"]`);
                if (submenuItem) {
                    submenuItem.classList.add('active');
                }
                
                const parentMenu = document.querySelector(`[data-submenu="${submenu}"]`);
                if (parentMenu) {
                    parentMenu.classList.add('active');
                }
            }
        }
        
        document.querySelectorAll('[data-submenu]').forEach(menuItem => {
            menuItem.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const submenuId = this.getAttribute('data-submenu');
                toggleSubmenu(submenuId);
                
                document.querySelectorAll('.submenu').forEach(sub => {
                    if (sub.id !== submenuId) {
                        sub.classList.remove('open');
                    }
                });
                
                document.querySelectorAll('.nav-arrow').forEach(arrow => {
                    if (!arrow.closest(`[data-submenu="${submenuId}"]`)) {
                        arrow.classList.remove('rotated');
                    }
                });
            });
        });
        
        setActiveMenu();
        
        const notificationBtn = document.querySelector('.admin-notification-btn');
        const notificationPanel = document.querySelector('.admin-notification-panel');
        
        if (notificationBtn && notificationPanel) {
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationPanel.style.display = notificationPanel.style.display === 'block' ? 'none' : 'block';
            });
            
            document.addEventListener('click', function() {
                notificationPanel.style.display = 'none';
            });
            
            notificationPanel.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        const userProfile = document.querySelector('.admin-user-profile');
        const userDropdown = document.querySelector('.admin-dropdown-menu');
        
        if (userProfile && userDropdown) {
            userProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
            });
            
            document.addEventListener('click', function() {
                userDropdown.style.display = 'none';
            });
            
            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>

<!-- ADMIN SIDEBAR NAVIGATION -->
<div class="admin-sidebar">
    <div class="admin-logo">
        <h2>
            <i class="fas fa-shield-alt"></i>
            Admin Panel
            <span class="admin-badge">ADMIN</span>
        </h2>
        <p>Surau Ismail Kharofa Lost & Found</p>
        
        <div class="system-stats">
            <span class="stat-badge"><i class="fas fa-users"></i> Online</span>
            <span class="stat-badge"><i class="fas fa-server"></i> Active</span>
        </div>
    </div>
    
    <div class="admin-nav-menu">
        <div class="admin-nav-title">Administration</div>

        <a href="admin_profile.php" class="admin-nav-item <?php echo $current_page == 'admin_profile.php' ? 'active' : ''; ?>">
            <div class="admin-nav-icon"><i class="fas fa-user-cog"></i></div>
            <div class="admin-nav-text">Profile</div>
        </a>
        
        <!-- Lists Menu dengan Submenu -->
        <button class="admin-nav-item" data-submenu="listsSubmenu">
            <div class="admin-nav-icon"><i class="fas fa-list"></i></div>
            <div class="admin-nav-text">Lists</div>
            <div class="nav-arrow"><i class="fas fa-chevron-right"></i></div>
        </button>
        
        <!-- Submenu untuk Lists -->
        <div class="submenu" id="listsSubmenu">
            <a href="list_found.php" class="submenu-item" data-page="founditems">
                <div class="submenu-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <div>Found Items</div>
            </a>
            <a href="list_lost.php" class="submenu-item" data-page="lostitems">
                <div class="submenu-icon"><i class="fas fa-search"></i></div>
                <div>Lost Items</div>
            </a>
        </div>
        
        <!-- Dashboard dengan Submenu -->
        <button class="admin-nav-item" data-submenu="dashboardSubmenu">
            <div class="admin-nav-icon"><i class="fas fa-tachometer-alt"></i></div>
            <div class="admin-nav-text">Dashboard</div>
            <div class="nav-arrow"><i class="fas fa-chevron-right"></i></div>
        </button>
        
        <!-- Submenu untuk Dashboard -->
        <div class="submenu" id="dashboardSubmenu">
            <a href="admin_statistics.php" class="submenu-item" data-page="statistics">
                <div class="submenu-icon"><i class="fas fa-chart-bar"></i></div>
                <div>Statistics</div>
            </a>
            <a href="admin_trail.php" class="submenu-item" data-page="trail">
                <div class="submenu-icon"><i class="fas fa-history"></i></div>
                <div>Admin Trail</div>
            </a>
            <a href="admin_users.php" class="submenu-item" data-page="users">
                <div class="submenu-icon"><i class="fas fa-user-friends"></i></div>
                <div>List User Account</div>
            </a>
            <a href="archive_items.php" class="submenu-item" data-page="archive">
                <div class="submenu-icon"><i class="fas fa-archive"></i></div>
                <div>Archive</div>
            </a>
        </div>
    </div>
</div>

<!-- ADMIN TOP HEADER BAR -->
<div class="admin-top-header">
    <div class="admin-page-title">
        <i class="fas fa-<?php 
            $page_icons = [
                'admin_statistics.php' => 'chart-bar',
                'admin_profile.php' => 'user-cog',
                'list_found.php' => 'hand-holding-heart',
                'list_lost.php' => 'search',
                'admin_trail.php' => 'history',
                'admin_users.php' => 'user-friends',
                'form_item.php' => 'plus-circle',
                'archive_items.php' => 'archive'
            ];
            echo $page_icons[$current_page] ?? 'cog';
        ?> admin-title-icon"></i>
        <?php 
        $page_titles = [
            'admin_statistics.php' => 'Dashboard Statistics',
            'admin_profile.php' => 'Admin Profile',
            'list_found.php' => 'Found Items Management',
            'list_lost.php' => 'Lost Items Management',
            'admin_trail.php' => 'Admin Activity Trail',
            'admin_users.php' => 'User Accounts Management',
            'form_item.php' => 'Report Item',
            'archive_items.php' => 'Archive'
        ];
        echo $page_titles[$current_page] ?? 'Admin Panel';
        ?>
    </div>
    
    <div class="admin-header-right">
        <!-- Admin Notification Button -->
        <div class="admin-dropdown">
            <button class="admin-notification-btn">
                <i class="fas fa-bell"></i>
                <span class="admin-notification-badge">5</span>
            </button>
            
            <div class="admin-notification-panel">
                <div class="admin-notification-header">
                    <span>Admin Notifications</span>
                    <span style="font-size: 12px; color: #e74c3c; font-weight: 600;">5 Unread</span>
                </div>
                
                <div class="admin-notification-item unread">
                    <div class="admin-notification-title">
                        <i class="fas fa-exclamation-triangle" style="color: #e74c3c;"></i>
                        System Alert
                    </div>
                    <div class="admin-notification-message">New user registration requires approval</div>
                    <div class="admin-notification-time">
                        <i class="far fa-clock"></i> Just now
                    </div>
                </div>
                
                <div class="admin-notification-item unread">
                    <div class="admin-notification-title">
                        <i class="fas fa-hand-holding-heart" style="color: #27ae60;"></i>
                        Found Item Reported
                    </div>
                    <div class="admin-notification-message">New found item requires review</div>
                    <div class="admin-notification-time">
                        <i class="far fa-clock"></i> 2 hours ago
                    </div>
                </div>
                
                <div class="admin-notification-item unread">
                    <div class="admin-notification-title">
                        <i class="fas fa-search" style="color: #ff9800;"></i>
                        Lost Item Reported
                    </div>
                    <div class="admin-notification-message">New lost item report submitted</div>
                    <div class="admin-notification-time">
                        <i class="far fa-clock"></i> 3 hours ago
                    </div>
                </div>
                
                <div class="admin-notification-item unread">
                    <div class="admin-notification-title">
                        <i class="fas fa-user-check" style="color: #28a745;"></i>
                        User Activity
                    </div>
                    <div class="admin-notification-message">10 active users in the last hour</div>
                    <div class="admin-notification-time">
                        <i class="far fa-clock"></i> 4 hours ago
                    </div>
                </div>
                
                <div class="admin-notification-item">
                    <div class="admin-notification-title">
                        <i class="fas fa-database" style="color: #6c757d;"></i>
                        Backup Complete
                    </div>
                    <div class="admin-notification-message">System backup completed successfully</div>
                    <div class="admin-notification-time">
                        <i class="far fa-clock"></i> Yesterday
                    </div>
                </div>
                
                <a href="admin_notifications.php" class="admin-dropdown-item" style="text-align: center; color: #e74c3c; font-weight: 600;">
                    <i class="fas fa-list admin-dropdown-icon"></i>
                    View All Notifications
                </a>
            </div>
        </div>
        
        <!-- Admin User Profile Dropdown -->
        <div class="admin-dropdown">
            <div class="admin-user-profile">
                <div class="admin-user-avatar">
                    <?php if(!empty($user_data['profile_pic']) && file_exists('profile_pics/' . $user_data['profile_pic'])): ?>
                        <img src="profile_pics/<?php echo $user_data['profile_pic']; ?>" 
                             alt="Profile" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; 
                                    background: linear-gradient(135deg, #3498db, #2c3e50);
                                    display: flex; align-items: center; justify-content: center;
                                    color: white; font-weight: bold; font-size: 18px;">
                            <?php echo strtoupper(substr($user_data['name'] ?? 'A', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="admin-user-info">
                    <div class="admin-user-name"><?php echo htmlspecialchars($user_data['name'] ?? 'Administrator'); ?></div>
                    <div class="admin-user-role">ADMIN</div>
                </div>
                <i class="fas fa-chevron-down" style="color: #7f8c8d; font-size: 12px;"></i>
            </div>
            
            <div class="admin-dropdown-menu">
                
                
                <div class="admin-dropdown-item">
                    <a href="logout.php" class="admin-logout-btn" style="width: 100%; justify-content: center;">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADMIN MAIN CONTENT AREA - OPEN DIV SAHAJA, TANPA PENUTUP -->
<div class="admin-main-content">
    <!-- Content will be inserted here by other admin pages -->