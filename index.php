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
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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

<?php $root = ''; require_once __DIR__ . '/includes/header.php'; ?>


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


</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/cart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
