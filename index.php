<?php
require_once __DIR__.'/settings/core.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HomePage- PizzaHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		.menu-tray {
			position: fixed;
			top: 16px;
			right: 16px;
			background: rgba(255,255,255,0.95);
			border: 1px solid #e6e6e6;
			border-radius: 8px;
			padding: 6px 20px;
			box-shadow: 0 4px 10px rgba(0,0,0,0.06);
			z-index: 1000;
		}
		.menu-tray a { margin-left: 8px; }
		h1{
			font-size: 70px;
		}
		p{
			font-family: 'Times New Roman', Times, serif;
		}
	</style>
</head>
<body>

	<div class="menu-tray">
		<?php if (!isLoggedIn()): ?>
			<a href="login/register.php" class="btn btn-sm btn-outline-primary">Join Us</a>
			<a href="login/login.php" class="btn btn-sm btn-outline-secondary">Sign In</a>
		<?php else: ?>
			<a href="login/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
			<?php if (isAdmin()): ?>
				<a  href="admin/category.php" class="btn btn-sm btn-outline-success">Category</a>
				<a href="admin/brand.php" class="btn btn-sm btn-outline-success">Brand</a>
				<a  href="admin/product.php" class="btn btn-sm btn-outline-success">Product</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<div class="container" style="padding-top:120px;">
		<div class="text-center">
			<h1>Hey There! Welcome</h1>
			<p class="text-muted">
				<?php if (isLoggedIn()): ?>
					Good to have you back, <?= htmlspecialchars($_SESSION['customer_name']) ?>!
				<?php else: ?>
					Look up there on your top right corner to Sign Up or Login.
				<?php endif; ?>
			</p>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

