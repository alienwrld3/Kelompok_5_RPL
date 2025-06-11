<?php
session_start();
require 'function.php';

//validasi jika sudah login tidak bisa back
if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;}
$uname_error = '';
$pw_error = '';
if (isset($_POST['SignIn'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Siapkan query aman
	$stmt = mysqli_prepare($conn, "SELECT * FROM pengguna WHERE username = ?");
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)) {
		// Verifikasi password
		if ($password === $row['password']) {
			// Berhasil login
			$_SESSION['id_user'] = $row['id_pengguna'];
			header("Location: index.php");
			exit;
		} else {
			$pw_error = "Password salah!";
		}
	} else {
		$uname_error = "Username tidak ditemukan!";
	}
}
?>


<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<title>
		KlinikKu Login
	</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');

		body {
			font-family: 'Inter', sans-serif;
		}
	</style>
</head>

<body class="bg-[#e3eff1] min-h-screen flex items-center justify-center p-6">
	<div class="w-full max-w-6xl flex flex-col md:flex-row items-center justify-between">
		<!-- Left side form -->
		<div class="w-full md:w-1/2 max-w-md">
			<h1 class="text-3xl font-extrabold mb-2">
				Selamat datang!
				<span>👋</span>
			</h1>
			<p class="text-sm text-gray-700 mb-6">
				Tersenyumlah karena sebagian senyummu itu adalah obat untuk kesembuhan pasienmu
			</p>
			<!-- Form Input Login -->
			<form method="post">
				<label class="block text-gray-800 text-sm mb-1" for="InputUsername">
					Username
				</label>
				<input
					class="w-full mb-4 px-4 py-2 rounded-md border border-gray-200 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
					name="username" id="InputUserName" placeholder="Masukkan username" type="text" />
					<?php if ($uname_error): ?>
					<p class="text-red-600 text-sm mb-3"><?= $uname_error ?></p>
				<?php endif; ?>
				<label class="block text-gray-800 text-sm mb-1" for="InputPassword">
					Password
				</label>
				<input
					class="w-full mb-1 px-4 py-2 rounded-md border border-gray-200 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
					name="password" id="InputPassword" placeholder="Masukkan password" type="password" />
				<?php if ($pw_error): ?>
					<p class="text-red-600 text-sm mb-3"><?= $pw_error ?></p>
				<?php endif; ?>
				<button name="SignIn" type="submit"
					class="block w-full bg-[#12292f] text-white py-2 rounded-md text-lg font-normal mb-6 text-center hover:bg-[#0f2327] transition-colors">
					Sign In
				</button>
			</form>
		</div>
		<!-- Right side logo -->
		<div class="w-full md:w-1/2 flex flex-col items-center justify-center mt-12 md:mt-0">
			<img alt="Logo of KlinikKu showing a yellow shopping cart with a blue medical box and a red cross on top"
				class="w-60 h-auto mb-6" height="180" src="assets/img/Screenshot 2025-04-24 174922.png" width="240" />
			<h2 class="text-5xl font-extrabold leading-none">
				KlinikKu
			</h2>
		</div>
	</div>
</body>

</html>