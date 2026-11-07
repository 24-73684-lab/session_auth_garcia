<?php
session_start();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'db.php';

$stmt = $pdo->prepare('SELECT id, username, email, created_at FROM `user` WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: logout.php');
    exit;
}

$sid = session_id();
$loginTime = $_SESSION['login_time'] ?? time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar bg-white shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">Session AuthSystem Dashboard</span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Log out</a>
    </div>
</nav>
	<div class = "card"> 
		<h2> Welcome! <?= htmlspecialchars($user['username']) ?></h2>
		<p> This page is protected and only visible to logged-in users.</p>
	</div>
			<div class ="row">
				<div class ="col md-4">
					<div class = "card mb-4">  
						<h3 class="h6">Account status</h3>
                    	<p class="mini">Status: Active</p>
                    	<p class="mini">Login state: Logged in</p>
                    	<p> </p>
                    </div>
				</div>
				<div class = "col md-4">
					<div class = card>
						<h3 class="h6">User details</h3>
                    	<p class="mini">ID: <?= (int)$user['id'] ?></p>
                    	<p class="mini">Username: <?= htmlspecialchars($user['username']) ?></p>
                    	<p class="mini">Joined: <?= htmlspecialchars(date('M j, Y', strtotime($user['created_at']))) ?></p>
                    </div>
				</div> 
				<div class = "col md-4">
					<div class = card>
						<h3 class="h6">Active session ID</h3>
                    	<p class="mini">Current Session ID: </p><p class = "mini" style="color : red"> <?= htmlspecialchars(substr($sid, 0, 20)) ?></p>
                    	<p class="mini">Logged in: <?= htmlspecialchars(date('g:i A', $loginTime)) ?></p>
					</div>
				</div>
			</div>
</body>
</html>