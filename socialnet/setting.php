<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

require_once('db.php');
require_once('flash.php');
$me = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $description_escaped = escape_sql($description);
    $me_escaped = escape_sql($me);
    
    // Handle picture upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_name = $_FILES['picture']['name'];
        $file_size = $_FILES['picture']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validate file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file_ext, $allowed_extensions)) {
            set_flash('error', 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
            header('Location: setting.php');
            exit();
        }
        
        if ($file_size > $max_size) {
            set_flash('error', 'File is too large. Maximum size is 5MB.');
            header('Location: setting.php');
            exit();
        }
        
        // Create uploads directory if it doesn't exist
        $upload_dir = __DIR__ . '/uploads';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                set_flash('error', 'Could not create uploads directory. Please contact administrator.');
                header('Location: setting.php');
                exit();
            }
        }
        
        // Verify directory is writable
        if (!is_writable($upload_dir)) {
            chmod($upload_dir, 0777);
            if (!is_writable($upload_dir)) {
                set_flash('error', 'Uploads directory is not writable. Please contact administrator.');
                header('Location: setting.php');
                exit();
            }
        }
        
        // Create unique filename
        $unique_filename = $me . '_' . time() . '.' . $file_ext;
        $upload_path_full = $upload_dir . '/' . $unique_filename;
        $upload_path_relative = 'uploads/' . $unique_filename;
        
        // Move file to uploads folder
        if (move_uploaded_file($file_tmp, $upload_path_full)) {
            // Delete old picture if exists
            $old_rows = db_query("SELECT picture FROM account WHERE username='" . $me_escaped . "'");
            if ($old_rows && $old_rows->num_rows > 0) {
                $old_picture = $old_rows->fetch_assoc()['picture'];
                if ($old_picture && file_exists($old_picture)) {
                    unlink($old_picture);
                }
            }
            
            // Update database with new picture path
            $upload_path_escaped = escape_sql($upload_path_relative);
            db_query("UPDATE account SET picture='" . $upload_path_escaped . "' WHERE username='" . $me_escaped . "'");
        } else {
            set_flash('error', 'Failed to save the file. Please check file permissions and try again.');
            header('Location: setting.php');
            exit();
        }
    } else if (isset($_FILES['picture']) && $_FILES['picture']['error'] != UPLOAD_ERR_NO_FILE) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => 'File is larger than allowed by server.',
            UPLOAD_ERR_FORM_SIZE => 'File is larger than allowed by form.',
            UPLOAD_ERR_PARTIAL => 'File upload was interrupted.',
            UPLOAD_ERR_NO_TMP_DIR => 'Temporary folder not found.',
            UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk.',
            UPLOAD_ERR_EXTENSION => 'File upload blocked by extension.'
        ];
        $error_code = $_FILES['picture']['error'];
        $error_msg = isset($error_messages[$error_code]) ? $error_messages[$error_code] : 'Unknown upload error.';
        set_flash('error', $error_msg);
        header('Location: setting.php');
        exit();
    }
    
    db_query("UPDATE account SET description='" . $description_escaped . "' WHERE username='" . $me_escaped . "'");
    set_flash('success', 'Profile updated successfully.');
    header('Location: setting.php');
    exit();
}

$me_escaped = escape_sql($me);
$rows = db_query("SELECT description, picture FROM account WHERE username='" . $me_escaped . "'");
$old_description = '';
$old_picture = '';
if ($rows && $rows->num_rows > 0) {
    $data = $rows->fetch_assoc();
    $old_description = $data['description'];
    $old_picture = $data['picture'];
}

$flash = consume_flash();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SocialNet: Settings</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .settings-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .settings-form {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .settings-form h2 {
            margin-top: 0;
            color: var(--text);
            margin-bottom: 25px;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 15px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text);
            font-size: 1.05em;
        }
        
        .picture-section {
            border: 2px dashed var(--border);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #111822;
            transition: all 0.3s ease;
        }
        
        .picture-section:hover {
            border-color: var(--accent);
            background-color: #0e1620;
        }
        
        .current-picture {
            margin-bottom: 20px;
        }
        
        .current-picture img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 3px solid #04aa6d;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        
        .current-picture p {
            color: var(--muted);
            font-size: 0.9em;
            margin-top: 10px;
        }
        
        #picture {
            padding: 10px;
            border: 2px solid var(--border);
            background: var(--card-bg);
            color: var(--text);
            border-radius: 6px;
            width: 100%;
            box-sizing: border-box;
            cursor: pointer;
            font-size: 0.95em;
        }
        
        #picture:focus {
            outline: none;
            border-color: #04aa6d;
            box-shadow: 0 0 0 3px rgba(4, 170, 109, 0.1);
        }
        
        .file-help {
            color: var(--muted);
            font-size: 0.9em;
            margin-top: 8px;
            display: block;
        }
        
        #description {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid var(--border);
            background: var(--card-bg);
            color: var(--text);
            border-radius: 6px;
            font-family: sans-serif;
            font-size: 0.95em;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s;
        }
        
        #description:focus {
            outline: none;
            border-color: #04aa6d;
            box-shadow: 0 0 0 3px rgba(4, 170, 109, 0.1);
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn-save {
            flex: 1;
            background-color: var(--accent);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-save:hover {
            background-color: var(--accent-dark);
        }
        
        .btn-save:active {
            transform: scale(0.98);
        }
        
        .info-box {
            background-color: rgba(32,201,151,0.08);
            border-left: 4px solid var(--accent);
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 0.9em;
            color: #a7f3d0;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="settings-container">
            <div class="settings-form">
                <h2>Account Settings</h2>
                
                <?php if ($flash): ?>
                    <p class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['message']); ?></p>
                <?php endif; ?>

                <form method="POST" action="setting.php" enctype="multipart/form-data">
                    <!-- Picture Section -->
                    <div class="form-section">
                        <label>Profile Picture</label>
                        <div class="picture-section">
                            <?php if ($old_picture && file_exists($old_picture)): ?>
                                <div class="current-picture">
                                    <img src="<?php echo htmlspecialchars($old_picture); ?>" alt="Current profile picture">
                                    <p>Click below to change your picture</p>
                                </div>
                            <?php else: ?>
                                <p style="color: #9aa3ad; margin: 20px 0;">No picture selected yet</p>
                            <?php endif; ?>
                            <input type="file" id="picture" name="picture" accept="image/jpeg,image/png,image/gif,image/webp" />
                            <span class="file-help">Max: 5MB</span>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="form-section">
                        <label for="description">About You</label>
                        <div class="info-box">
                            Tell other users about yourself. This appears on your profile.
                        </div>
                        <textarea id="description" name="description" placeholder="Share something about yourself... (e.g., hobbies, interests, location)"><?php echo htmlspecialchars($old_description); ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="button-group">
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
