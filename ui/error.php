<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h1>Error</h1>
        <?php
        if (isset($_GET['message'])) {
            echo "<p>{$_GET['message']}</p>";
        } else {
            echo "<p>An unknown error occurred.</p>";
        }
        ?>
        <p><a href="index.php">Go back to the homepage</a></p>
    </div>
</body>
</html>
