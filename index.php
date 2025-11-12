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

<!-- ABOUT -->
<section id="about" class="py-5 bg-white">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6">
				<h2>About PizzaHub</h2>
				<p class="lead">PizzaHub is your number one stop shop online platform that allows you to sell and buy delicious pizza with verified sellers and a secure payment system</p>
				<p>Our goal is to make it quick to search for pizza products, manage your pizza products, and allow your customers to reach 
					you in the easiest,quickest way possible. With Pizzahub, the success of your business is assured, as we are linked to over 2 million customers all over the world giving you a higher customer reach regardless of your location. Create an account now!!, What are you waiting for?</p>
			</div>
			
		</div>
	</div>
</section>

<!-- FEATURES -->
<section id="features" class="py-5">
	<div class="container">
		<h3 class="mb-4 text-center">What we offer</h3>
		<div class="row gy-4">
			<div class="col-md-4">
				<div class="card p-3 h-100">
					<h5>Easy Catalog</h5>
					<p class="small">Browse organized categories and brands with product images and pricing.</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card p-3 h-100">
					<h5>Smooth Cart</h5>
					<p class="small">Buy items from our platform, and see it all in your cart with the ability to update quantities and checkout seamlessly.</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card p-3 h-100">
					<h5>Admin Controls</h5>
					<p class="small">Admin pages to manage categories, brands and products (role-restricted).</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-5 bg-white">
	<div class="container">
		<h3 class="mb-4 text-center">Contact Us</h3>
		<div class="row justify-content-center">
			<div class="col-md-8">
				<form id="contactForm">
					<div class="mb-3">
						<label for="cname" class="form-label">Your name</label>
						<input id="cname" name="name" type="text" class="form-control" required>
					</div>
					<div class="mb-3">
						<label for="cemail" class="form-label">Email</label>
						<input id="cemail" name="email" type="email" class="form-control" required>
					</div>
					<div class="mb-3">
						<label for="cmessage" class="form-label">Message</label>
						<textarea id="cmessage" name="message" rows="4" class="form-control" required></textarea>
					</div>
					<div class="d-flex justify-content-center">
						<button type="submit" class="btn btn-primary">Send message</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<script>
// contact form handled client-side: validate then show a friendly message
document.addEventListener('DOMContentLoaded', function(){
	const form = document.getElementById('contactForm');
	if (!form) return;
	form.addEventListener('submit', function(e){
		e.preventDefault();
		const name = document.getElementById('cname').value.trim();
		const email = document.getElementById('cemail').value.trim();
		const message = document.getElementById('cmessage').value.trim();
		if (!name || !email || !message) {
			if (window.Swal) Swal.fire('Missing fields','Please complete all fields','warning');
			else alert('Please complete all fields');
			return;
		}
		// just show success client-side (no backend in demo)
		if (window.Swal) {
			Swal.fire('Thanks!', 'Your message has been received. We will get back to you shortly.', 'success');
		} else {
			alert('Thanks! Your message has been received.');
		}
		form.reset();
	});
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/cart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
