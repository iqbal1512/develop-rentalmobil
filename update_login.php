<?php
$loginContent = <<<'HTML'
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — AutoPrime Showroom Mobil</title>
  <meta name="description" content="Login ke Sistem Informasi Showroom Mobil AutoPrime">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;700&display=swap" rel="stylesheet">
  <style>
    /* Reset & Background */
    html, body {
      width: 100%;
      height: 100%;
      background: #000 !important;
      overflow: hidden;
      margin: 0;
      padding: 0;
    }
    
    /* Speedometer App */
    .speedo-app {
      width: 100%;
      height: 100%;
      position: fixed;
      top: 0; left: 0;
      z-index: 1;
      opacity: 0;
      font-family: 'Montserrat', sans-serif;
      font-size: 85px;
    }
    .speedo-app canvas, .speedo-app svg {
      position: absolute;
      user-select: none;
    }
    #s {
      overflow: visible;
      width: 66.7%; height: 66.7%;
      left: 50%; top: 50%;
      transform: translate(-50%, -50%);
      z-index: 20;
    }
    
    /* Login Overlay */
    .login-overlay {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 1.5s ease;
      background: rgba(0,0,0,0.2);
    }
    .login-overlay.show {
      opacity: 1;
      pointer-events: all;
    }
    
    .login-overlay .login-card {
      background: rgba(20, 20, 20, 0.4);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 1.5rem;
      padding: 3rem;
      width: 100%;
      max-width: 450px;
      color: #fff;
      box-shadow: 0 25px 60px rgba(0,0,0,0.8);
    }
    
    .login-overlay .form-control, .login-overlay .input-group-text {
      background: rgba(0,0,0,0.5) !important;
      border: 1px solid rgba(255,255,255,0.2) !important;
      color: #fff !important;
    }
    .login-overlay .form-control:focus {
      border-color: #0dcaf0 !important;
      box-shadow: 0 0 0 2px rgba(13, 202, 240, 0.3) !important;
    }
    .login-overlay .form-control::placeholder {
      color: rgba(255,255,255,0.4) !important;
    }
    
    .login-overlay .login-title {
      color: #fff;
      font-size: 1.75rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 0.5rem;
      letter-spacing: 1px;
    }
    .login-overlay .login-subtitle {
      color: rgba(255,255,255,0.7);
      text-align: center;
      font-size: 0.875rem;
      margin-bottom: 2rem;
    }
    .login-overlay .form-label {
      color: rgba(255,255,255,0.9);
      font-weight: 500;
    }
    
    .btn-login {
      background: #0dcaf0;
      color: #000;
      border: none;
      font-weight: 700;
      padding: 0.875rem;
      border-radius: 0.5rem;
      width: 100%;
      font-size: 1rem;
      transition: all 0.3s;
      margin-top: 1rem;
      cursor: pointer;
    }
    .btn-login:hover {
      background: #fff;
      box-shadow: 0 0 15px rgba(255,255,255,0.5);
    }

    /* Hide Old Elements */
    .login-intro, .login-page { display: none !important; }
  </style>
</head>
<body>

<!-- Speedometer Animation Background -->
<div class="speedo-app" id="speedoApp">
  <canvas id="c"></canvas>
  <svg id="s" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" stroke="#fff" fill="none">
    <defs>
      <mask id="m1">
        <rect fill="#fff" width="100%" height="100%"/>
        <circle fill="#000" cx="300" cy="300" r="285"/>
      </mask>
      <mask id="m2">
        <rect fill="#fff" width="100%" height="100%"/>
        <circle fill="#000" cx="300" cy="300" r="272"/>
      </mask>    
    </defs>
    <circle class="ring1" cx="300" cy="300" r="210" stroke-width="220" stroke="#000" />  
    <g id="marks">
      <path mask="url(#m1)" opacity="0.5" d="M0.2,289.5l599.6,20.9 M6.6,237.6l586.9,124.7 M21.8,187.6l556.3,224.8 M45.6,141l508.8,318 M77.1,99.3
        L300,300 M115.3,63.6L300,300 M300,300L159.2,35.1 M207.3,14.7L300,300 M258.2,2.9L300,300 M300,300L310.5,0.2 M362.4,6.6L300,300
         M412.4,21.8L300,300 M459,45.6L300,300 M500.7,77.1L99.3,522.9 M63.6,484.7l472.8-369.4 M35.1,440.8l529.8-281.7 M14.7,392.7
        l570.6-185.4 M2.9,341.8l594.2-83.5 M0.7,279.1l598.5,41.9 M8.9,227.4l582.2,145.2 M25.9,178l548.1,244 M51.3,132.2l497.4,335.5
         M84.2,91.6L300,300 M123.7,57.3L300,300 M300,300L168.5,30.4 M217.3,11.6L300,300 M268.6,1.6L300,300 M300,300L320.9,0.7
         M372.6,8.9L300,300 M422,25.9L300,300 M467.8,51.3L300,300 M508.4,84.2L91.6,515.8 M57.3,476.3l485.4-352.7 M30.4,431.5l539.3-263
         M11.6,382.7l576.8-165.4 M1.6,331.4l596.7-62.7 M1.6,268.6l596.7,62.7 M11.6,217.3l576.8,165.4 M30.4,168.5l539.3,263 M57.3,123.7
        l485.4,352.7 M91.6,84.2L300,300 M132.2,51.3L300,300 M300,300L178,25.9 M227.4,8.9L300,300 M279.1,0.7L300,300 M300,300L331.4,1.6
         M382.7,11.6L300,300 M431.5,30.4L300,300 M476.3,57.3L300,300 M515.8,91.6L84.2,508.4 M51.3,467.8l497.4-335.5 M25.9,422
        l548.1-244 M8.9,372.6l582.2-145.2 M0.7,320.9l598.5-41.9 M2.9,258.2l594.2,83.5 M14.7,207.3l570.6,185.4 M35.1,159.2l529.8,281.7
         M63.6,115.3l472.8,369.4 M99.3,77.1L300,300 M141,45.6L300,300 M300,300L187.6,21.8 M237.6,6.6L300,300 M289.5,0.2L300,300
         M300,300L341.8,2.9 M392.7,14.7L300,300 M440.8,35.1L300,300 M484.7,63.6L300,300 M522.9,99.3L77.1,500.7 M45.6,459l508.8-318
         M21.8,412.4l556.3-224.8 M6.6,362.4l586.9-124.7 M0.2,310.5l599.6-20.9"/>
      <path mask="url(#m2)" d="M4.6,247.9l590.9,104.2 M18.1,197.4l563.8,205.2 M40.2,150l519.6,300 M70.2,107.2l459.6,385.7 M107.2,70.2
        L300,300 M150,40.2L300,300 M300,300L197.4,18.1 M247.9,4.6L300,300 M300,0v300 M300,300L352.1,4.6 M402.6,18.1L300,300 M450,40.2
        L300,300 M492.8,70.2L300,300 M529.8,107.2L70.2,492.8 M40.2,450l519.6-300 M18.1,402.6l563.8-205.2 M4.6,352.1l590.9-104.2 M0,300
        l600,0"/>
    </g>
    <circle class="st2" cx="300" cy="300" r="118" stroke-width="1.5" opacity="0.7"/>  
    <g class="needle" stroke-width="2">
      <circle class="ring2" cx="300" cy="300" r="100" stroke-width="4.5" />  
      <line x1="300" y1="400" x2="300" y2="600" stroke-width="3"/>
    </g>
    <circle class="ring3" cx="300" cy="300" r="300" stroke-width="1.5" />
    <circle class="redzone" cx="300" cy="300" r="315" stroke-width="6" stroke="hsl(10,85%,50%)" />
    <text class="txt" x="300" y="328" text-anchor="middle" stroke="none" fill="#fff">0</text>
  </svg>
  
  <div id="instructionText" style="position:absolute; bottom:12%; width:100%; text-align:center; color:#0dcaf0; font-family:'Montserrat',sans-serif; font-size:18px; letter-spacing:4px; font-weight:700; z-index:10; pointer-events:none; text-shadow: 0 0 10px rgba(13,202,240,0.8);">
     [ KLIK & TAHAN UNTUK START ENGINE ]
  </div>
</div>

<!-- Login Form Overlay -->
<div class="login-overlay" id="loginOverlay">
  <div class="login-card">
    <h1 class="login-title">Showroom Mobil Bekas</h1>
    <p class="login-subtitle">Sistem Informasi Penjualan Kendaraan</p>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" style="background:rgba(239,68,68,0.2); border:1px solid #ef4444; color:#fff;">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success" style="background:rgba(16,185,129,0.2); border:1px solid #10b981; color:#fff;">
      <i class="bi bi-check-circle-fill"></i>
      <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <form action="<?= base_url('login/proses') ?>" method="POST" id="loginForm">
      <?= csrf_field() ?>

      <div class="form-group mb-4">
        <label class="form-label" for="username">
          <i class="bi bi-person"></i> Username
        </label>
        <div class="input-group">
          <input
            type="text"
            id="username"
            name="username"
            class="form-control"
            placeholder="Masukkan username..."
            value="<?= old('username') ?>"
            autocomplete="username"
            required>
          <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
        </div>
      </div>

      <div class="form-group mb-4">
        <label class="form-label" for="password">
          <i class="bi bi-lock"></i> Password
        </label>
        <div class="input-group">
          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            placeholder="Masukkan password..."
            autocomplete="current-password"
            required>
          <span class="input-group-text" style="cursor:pointer" onclick="togglePwd()">
            <i class="bi bi-eye-slash-fill" id="pwdIcon"></i>
          </span>
        </div>
      </div>

      <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right"></i> Akses Dasbor
      </button>

    </form>
  </div>
</div>

<script src='https://unpkg.co/gsap@3/dist/gsap.min.js'></script>
<script src='https://assets.codepen.io/16327/DrawSVGPlugin3.min.js'></script>
<script>
// ==========================================
// SPEEDOMETER LOGIC
// ==========================================
var n = 4,
    dur = 3,
    props = {x:0, y:0, hue:185},
    mph = 0,
    mouseDown = false,
    c = document.getElementById('c'),
    ctx = c.getContext('2d'),
    size, cw, ch,
    img = new Image(),
    ring = new Image(),
    particles = [],
    loginShown = false;

var Particle = function(index){
  this.index = index;
  this.x = this.y = this.progress = this.opacity = this.scale = 1;

  this.draw = function(){
    ctx.translate( cw/2, ch/2 );
    ctx.rotate(this.rot);
    ctx.globalAlpha = this.opacity;
    ctx.globalCompositeOperation = 'overlay';
    ctx.drawImage(img, -size*this.scale/2, -size*this.scale/2, size*this.scale, size*this.scale);
    ctx.rotate(-this.rot);
    ctx.translate( -cw/2, -ch/2 );
  }

  this.tl = gsap.timeline({repeat:-1, repeatRefresh:true})
      .fromTo(this, {
        rot:()=>Math.random()*-0.8,
        scale:()=>3+Math.random(),
      },{
        duration:dur,
        scale:()=>0.5+Math.random(),
        rot:()=>(this.index%2==0)?1:-0.8,
        ease:'none'
      }, 0)
      .fromTo(this, {opacity:0}, {duration:dur/2, opacity:1, yoyo:true, repeat:1, ease:'power1.inOut'}, 0)
      .progress(this.index/n);
}

ring.src = 'https://assets.codepen.io/721952/speedometerAlpha3.png';
img.src = 'https://assets.codepen.io/721952/grayscaleFlame.jpg';

img.onload = function(){
  updateSize();
  for (var i=0; i<n; i++) particles.push(new Particle(i));
  gsap.ticker.add(redraw);
  gsap.set('.speedo-app', {opacity:1});
}

window.onresize = updateSize;

// Trigger Revs
window.onmousedown = window.ontouchstart = (e)=>{ 
  if(!loginShown) mouseDown = true; 
};
window.onmouseup = window.ontouchend = (e)=>{ 
  mouseDown = false; 
};
window.onmousemove = window.ontouchmove = (e)=>{
  if (e.touches) {
    e.clientX = e.touches[0].clientX;
    e.clientY = e.touches[0].clientY;
  }  
  gsap.to('#c, #s', {
    rotationY:-20+e.clientX/innerWidth*40,
    rotationX:10-e.clientY/innerHeight*20
  });
}

gsap.set('.needle',  { transformOrigin:'100px 100px', rotation:40 });
gsap.set('.ring1',   { transformOrigin:'50% 50%', rotation:130 });
gsap.set('.ring3',   { transformOrigin:'50% 50%', rotation:130, drawSVG:0 });
gsap.set('.redzone', { transformOrigin:'50% 50%', drawSVG:'2.8% 11.2%' });
gsap.set('.speedo-app', { perspective:400 });

function updateSize(){
  cw = (c.width = window.innerWidth);
  ch = (c.height = window.innerHeight);
  size = Math.min(cw/1.5, ch/1.5);
}

function redraw(){ 
  ctx.clearRect(0,0,cw,ch);
  for (var i=0; i<n; i++) particles[i].draw();
  ctx.globalAlpha = 1;
  ctx.globalCompositeOperation = 'multiply';
  ctx.fillStyle = "hsl("+props.hue+", 100%, 50%)";
  ctx.fillRect(cw/2-size/2, ch/2-size/2, size, size); 
  ctx.globalCompositeOperation = 'destination-in';
  ctx.drawImage(ring, cw/2-size/2, ch/2-size/2, size, size);
  
  if (mouseDown && mph<1 && !loginShown) {
    mph+=0.0015;
    (mph>0.88 && Math.random()>0.5) ? mph -= 0.002 : mph += 0.0015; 
  }
  else if (mph>0 && !loginShown) {
    (mph<0.05) ? mph=0:mph-=0.005;
  }
  
  // Hit max speed
  if(mph > 0.985 && !loginShown) {
     loginShown = true;
     mouseDown = false;
     
     // Fade out instruction
     gsap.to('#instructionText', {opacity: 0, duration: 0.5});
     
     // Show Login Form
     document.getElementById('loginOverlay').classList.add('show');
     
     // Maintain speedometer glow
     mph = 0.985;
  }

  // Calculate actual display speed
  var speedVal = Math.floor(mph*221);
  if(loginShown) speedVal = 218; // Lock at 218 when form shows
  
  gsap.to('.txt',     { duration:()=>(mph<0.01)?0.001:0.5, innerHTML:speedVal, snap:{innerHTML:1} })
  gsap.to('.ring3',   { drawSVG:'0 '+mph*75+'%' })
  gsap.to('.ring1',   { drawSVG:mph*75+'% 100%' })  
  gsap.to('.needle',  { rotation:40+mph*270 })
  gsap.set(props,     { hue:()=>(mph<0.9)?185:10 })
}

// ==========================================
// LOGIN UTILITIES
// ==========================================
function togglePwd() {
  const inp = document.getElementById('password');
  const ico = document.getElementById('pwdIcon');
  if (inp.type === 'password') {
    inp.type = 'text';
    ico.className = 'bi bi-eye-fill';
  } else {
    inp.type = 'password';
    ico.className = 'bi bi-eye-slash-fill';
  }
}

// Auto dismiss alerts
setTimeout(() => {
  document.querySelectorAll('.alert').forEach(el => {
    el.style.opacity = '0';
    el.style.transition = 'opacity 0.5s';
    setTimeout(() => el.remove(), 500);
  });
}, 3000);
</script>

</body>
</html>
HTML;

file_put_contents('c:\xampp8.2\htdocs\codeigniter4-develop\app\Views\auth\login.php', $loginContent);
echo "Done!";
?>
