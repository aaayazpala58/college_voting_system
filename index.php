<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to Harsha House Elections</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      overflow: hidden;
      background: url('uploads/logo.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      position: relative;
    }

    #particles-js {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.3); /* More visible background */
      z-index: 0;
    }

    body *:not(#particles-js):not(.overlay) {
      position: relative;
      z-index: 1;
    }

    .logo {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      margin-bottom: 30px;
      object-fit: cover;
      box-shadow: 0 0 25px rgba(255, 255, 255, 0.8);
      border: 3px solid #fff;
      animation: float 4s ease-in-out infinite;
    }

    h1 {
  font-size: 2.8rem;
  color: #ffffff;
  text-shadow: 0 0 20px #ff00ff, 0 0 30px #00ffff;
  margin-bottom: 40px;
  animation: zoomInOut 3s ease-in-out infinite;
}
@keyframes zoomInOut {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
}


    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .buttons-container {
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
      justify-content: center;
      animation: float 5s ease-in-out infinite;
    }

    .btn {
      padding: 15px 30px;
      font-size: 1.2rem;
      background: linear-gradient(45deg, #ff0066, #ffcc00);
      color: white;
      border: none;
      border-radius: 50px;
      text-decoration: none;
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
      display: flex;
      align-items: center;
      gap: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.4s;
    }

    .btn i {
      transition: transform 0.3s ease;
    }

    .btn:hover {
      transform: scale(1.1);
      box-shadow: 0 0 25px rgba(255, 255, 255, 0.7);
      background: linear-gradient(45deg, #00ccff, #ff00cc);
    }

    .btn:hover i {
      transform: rotate(360deg);
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 2rem;
      }
      .btn {
        font-size: 1rem;
        padding: 12px 20px;
      }
      .logo {
        width: 100px;
        height: 100px;
      }
    }
  </style>
</head>
<body>
  <div id="particles-js"></div>
  <div class="overlay"></div>

  <!-- Logo -->
  <img src="uploads/harlogo.jpg" alt="Harsha Logo" class="logo" />

  <!-- Heading -->
  <h1>Welcome to Harshahotsava House Elections</h1>

  <!-- Buttons -->
<div class="buttons-container">
  <a href="login.php" class="btn"><i class="fas fa-user-graduate"></i> Student Login</a>
  <a href="admin/admin_login.php" class="btn"><i class="fas fa-user-shield"></i> Admin Login</a>
  <a href="admin/coordinator_login.php" class="btn"><i class="fas fa-user-tie"></i> Coordinator Login</a>
</div>


  <!-- Particle JS -->
  <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
  <script>
    particlesJS("particles-js", {
      "particles": {
        "number": {
          "value": 70,
          "density": { "enable": true, "value_area": 800 }
        },
        "color": { "value": "#ffffff" },
        "shape": {
          "type": "circle",
          "stroke": { "width": 0, "color": "#000000" }
        },
        "opacity": {
          "value": 0.3,
          "random": true
        },
        "size": {
          "value": 4,
          "random": true
        },
        "line_linked": {
          "enable": true,
          "distance": 120,
          "color": "#ffffff",
          "opacity": 0.3,
          "width": 1
        },
        "move": {
          "enable": true,
          "speed": 2,
          "direction": "none",
          "random": false,
          "straight": false,
          "out_mode": "out"
        }
      },
      "interactivity": {
        "events": {
          "onhover": { "enable": true, "mode": "grab" },
          "onclick": { "enable": true, "mode": "push" }
        },
        "modes": {
          "grab": { "distance": 200, "line_linked": { "opacity": 0.4 } },
          "push": { "particles_nb": 3 }
        }
      },
      "retina_detect": true
    });
  </script>
</body>
</html>
