<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Logo Web -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <!-- Logo web -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin & Humas</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
      background-image: url('../image/bg_polije.jpeg');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center relative">

  <!-- Overlay -->
  <div class="absolute inset-0 bg-black/50"></div>

  <!-- Card -->
  <div class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl p-10 z-10">

    <!-- Logo -->
    <div class="flex justify-center mb-3">
      <img src="../image/logo_polije&PKM.png" alt="Logo 1" class="w-28 h-auto object-contain">
    </div>

    <h2 class="text-center font-bold text-gray-800 text-lg">
      Login Pengelola SIM PKM
    </h2>

    <p class="text-center text-xs text-gray-500 mb-6">
      Masuk ke Dashboard Pengelola SIM PKM <br>
      Area Terbatas Sistem
    </p>

    <form action="proses_login_user.php" method="POST" class="space-y-5">
  <div>
    <label class="text-xs text-gray-600 font-semibold">EMAIL</label>
    <input type="email" name="email" required
      placeholder="pengelola@polije.ac.id"
      class="w-full mt-1 px-4 py-3 bg-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
  </div>

  <div>
    <label class="text-xs text-gray-600 font-semibold">PASSWORD</label>
    <input type="password" name="password" required
      placeholder="••••••••"
      class="w-full mt-1 px-4 py-3 bg-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600">
  </div>

  <button type="submit"
    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-xl transition duration-300">
    MASUK
  </button>
</form>

  </div>

</body>
</html>
