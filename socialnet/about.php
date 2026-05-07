<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About - SocialNet</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .about-wrap {
            max-width: 760px;
            margin: 24px auto;
        }

        .about-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .about-title {
            margin: 0 0 6px 0;
            color: var(--text);
        }

        .about-subtitle {
            margin: 0 0 18px 0;
            color: var(--muted);
        }

        .about-row {
            margin: 10px 0;
            color: var(--text);
        }

        .about-label {
            color: var(--muted);
            display: inline-block;
            width: 150px;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="content">
        <div class="about-wrap">
            <div class="about-card">
                <h2 class="about-title">About</h2>
               

                <div class="about-row"><span class="about-label">Student Name:</span> Bui Huu Duc</div>
                <div class="about-row"><span class="about-label">Student Number:</span> 1694858</div>
            </div>
        </div>
    </div>
</body>
</html>
