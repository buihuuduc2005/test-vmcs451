<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Professional Namecard</title>
    <style>
        body {
            display: flex; justify-content: center; align-items: center;
            height: 100vh; background-color: #f0f2f5; margin: 0;
            font-family: sans-serif;
        }
        .namecard {
            width: 350px; height: 200px; padding: 30px; box-sizing: border-box;
            background: white; border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; justify-content: center;
            position: relative; border-top: 6px solid #3498db;
        }
        .fullname { font-size: 1.8em; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .position { font-size: 1.1em; color: #3498db; margin-bottom: 15px; }
        .gender { 
            font-size: 0.9em; color: #7f8c8d; background-color: #ecf0f1; 
            padding: 4px 8px; border-radius: 4px; align-self: flex-start; 
        }
    </style>
</head>
<body>

    <div class="namecard">
        <div class="fullname"><?php echo $_GET['fullname']; ?></div>
        <div class="position"><?php echo $_GET['position']; ?></div>
        <div class="gender">Gender: <?php echo $_GET['gender']; ?></div>
    </div>

</body>
</html>
