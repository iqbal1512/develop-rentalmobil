<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — AutoPrime Showroom Mobil</title>
  <meta name="description" content="Login ke Sistem Informasi Showroom Mobil AutoPrime">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Quicksand", sans-serif;
    }
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: #111;
      width: 100%;
      overflow: hidden;
    }
    .ring {
      position: relative;
      width: 500px;
      height: 500px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .ring i {
      position: absolute;
      inset: 0;
      border: 2px solid #fff;
      transition: 0.5s;
    }
    .ring i:nth-child(1) {
      border-radius: 38% 62% 63% 37% / 41% 44% 56% 59%;
      animation: animate 6s linear infinite;
    }
    .ring i:nth-child(2) {
      border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
      animation: animate 4s linear infinite;
    }
    .ring i:nth-child(3) {
      border-radius: 41% 44% 56% 59%/38% 62% 63% 37%;
      animation: animate2 10s linear infinite;
    }
    .ring:hover i {
      border: 6px solid var(--clr);
      filter: drop-shadow(0 0 20px var(--clr));
    }
    @keyframes animate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    @keyframes animate2 {
      0% { transform: rotate(360deg); }
      100% { transform: rotate(0deg); }
    }
    .login {
      position: absolute;
      width: 320px;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      gap: 20px;
      z-index: 10;
    }
    .login h2 {
      font-size: 2em;
      color: #fff;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .login h2 span {
      font-size: 0.4em;
      display: block;
      font-weight: 400;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 5px;
      letter-spacing: 0;
    }
    .login form {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .login .inputBx {
      position: relative;
      width: 100%;
    }
    .login .inputBx input {
      position: relative;
      width: 100%;
      padding: 12px 20px;
      background: transparent;
      border: 2px solid #fff;
      border-radius: 40px;
      font-size: 1.1em;
      color: #fff;
      box-shadow: none;
      outline: none;
      transition: border 0.3s;
    }
    .login .inputBx input:focus {
      border: 2px solid #00ff0a;
    }
    .login .inputBx input[type="submit"], .login .inputBx button {
      width: 100%;
      background: #0078ff;
      background: linear-gradient(45deg, #ff357a, #fff172);
      border: none;
      cursor: pointer;
      padding: 12px 20px;
      border-radius: 40px;
      font-size: 1.2em;
      color: #111;
      font-weight: 700;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .login .inputBx button:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(255, 53, 122, 0.4);
    }
    .login .inputBx input::placeholder {
      color: rgba(255, 255, 255, 0.75);
    }
    .login .alert {
      width: 100%;
      padding: 12px;
      border-radius: 12px;
      font-size: 0.9em;
      text-align: center;
      font-weight: 600;
    }
    .login .alert-danger {
      background: rgba(255, 53, 122, 0.2);
      border: 1px solid #ff357a;
      color: #fff;
    }
    .login .alert-success {
      background: rgba(0, 255, 10, 0.2);
      border: 1px solid #00ff0a;
      color: #fff;
    }
  </style>
</head>
<body>

<div class="ring">
  <i style="--clr:#00ff0a;"></i>
  <i style="--clr:#ff0057;"></i>
  <i style="--clr:#fffd44;"></i>
  
  <div class="login">
    <h2 style="text-align:center;">
      Showroom Mobil
      <span>Login Sistem Manajemen</span>
    </h2>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger" id="alertBox">
        <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success" id="alertBox">
        <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('login/proses') ?>" method="POST">
      <?= csrf_field() ?>
      
      <div class="inputBx">
        <input type="text" name="username" placeholder="Username" value="<?= old('username') ?>" required autocomplete="off">
      </div>
      <div class="inputBx">
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <div class="inputBx">
        <button type="submit">Sign in</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Auto dismiss alert
  const alertBox = document.getElementById('alertBox');
  if (alertBox) {
    setTimeout(() => {
      alertBox.style.transition = 'opacity 0.5s ease';
      alertBox.style.opacity = '0';
      setTimeout(() => alertBox.remove(), 500);
    }, 3000);
  }
</script>

</body>
</html>