<?php
require_once __DIR__.'/settings/core.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HomePage - PizzaHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
		body {
			background: #fff8f0;
		}
		.hero {
			padding: 120px 20px;
		}
		.hero h1 {
			font-size: 70px;
			font-weight: 800;
			color: #d35400;
		}
		.hero p {
			font-size: 18px;
			font-family: 'Times New Roman', serif;
		}
	</style>
</head>

<body>

<!-- ✅ NEW TOP NAVIGATION BAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 fixed-top">
	<a href="index.php" class="navbar-brand fw-bold" style="color:#d35400;">PizzaHub 🍕</a>

	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="mainNav">
		<ul class="navbar-nav ms-auto align-items-center">

			<!-- Always visible -->
			<li class="nav-item"><a href="View/all_product.php" class="nav-link">All Products</a></li>

			<!-- Not logged in -->
			<?php if (!isLoggedIn()): ?>
				<li class="nav-item"><a href="login/register.php" class="nav-link">Join Us</a></li>
				<li class="nav-item"><a href="login/login.php" class="nav-link">Sign In</a></li>

			<!-- Logged in -->
			<?php else: ?>

				<!-- Admin Menu -->
				<?php if (isAdmin()): ?>
					<li class="nav-item"><a href="admin/category.php" class="nav-link">Category</a></li>
					<li class="nav-item"><a href="admin/brand.php" class="nav-link">Brand</a></li>
					<li class="nav-item"><a href="admin/product.php" class="nav-link">Manage Products</a></li>
				<?php endif; ?>

				<li class="nav-item">
					<a href="login/logout.php" class="btn btn-sm btn-outline-danger ms-3">Logout</a>
				</li>

			<?php endif; ?>
		</ul>
		
	</div>
</nav>


<!-- ✅ HERO SECTION -->
<div class="container hero text-center">
	<h1>Welcome to PizzaHub 🍕</h1>

	<p class="text-muted mt-3">
		<?php if (isLoggedIn()): ?>
			Good to have you back, <?= htmlspecialchars($_SESSION['customer_name']) ?>!
		<?php else: ?>
			Create an account to place orders and enjoy great deals!
		<?php endif; ?>
	</p>

	<a href="View/all_product.php" class="btn btn-primary btn-lg mt-4 px-4">
		Explore Products 🍕
	</a>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
