<?php
include 'connect.php';
$admin_id = $_SESSION['admin_id'];

// Check if admin is logged in
if (!isset($admin_id)) {
    header('location:../admin/admin_login.php');
}

$select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Custom Style -->
    <style>
        /* Created by Tivotal */
        /* Super Advanced 3D Animated Admin Dashboard Style */
        /* Dev Community Style */
        
        * {
         font-family: "Almendra", serif;
         font-weight: 600;
         font-style: normal;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: #222;


            
        }

        .nav {
            position: absolute;
            width: 300px;
            height: 100vh;
            background: #fff;
            padding-left: 10px;
            
        }

        .nav ul {
            position: relative;
            height: 100vh;
        }

        .nav ul li {
            position: relative;
            list-style: none;
        }

        .nav ul li.active {
            background: #222;
            border-top-left-radius: 50px;
            border-bottom-left-radius: 50px;
        }

        .nav ul li.active::before {
            position: absolute;
            content: "";
            width: 20px;
            height: 20px;
            background: transparent;
            top: -20px;
            right: 0;
            border-bottom-right-radius: 20px;
            box-shadow: 5px 5px 0 5px #222;




       
        }

        .nav ul li.active::after {
            position: absolute;
            content: "";
            width: 20px;
            height: 20px;
            background: transparent;
            bottom: -20px;
            right: 0;
            border-top-right-radius: 20px;
            box-shadow: 5px -5px 0 5px #222;
        }

        .nav ul li.logo {
            margin-bottom: 50px;
        }

        .nav ul li.logo .icon {
            font-size: 2em;
            color: #222;
        }

        .nav ul li.logo .text {
            font-size: 1.2em;
            font-weight: 600;
            color: #222;
        }

        .nav ul li a {
            position: relative;
            display: flex;
            text-decoration: none;
            z-index: 1;
        }

        .nav ul li a .icon {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 60px;
            height: 70px;
            font-size: 1.5em;
            color: #333;
            transition: 0.5s;
            padding-left: 10px;

            
        }

        .nav ul li a .text {
            position: relative;
            height: 70px;
            display: flex;
            align-items: center;
            font-size: 1.5em;
            font-weight: 500;
            color: gold;
            padding-left: 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: 0.5s;

            
        }

        .nav ul li.active a .icon {
            color: gold;
            font-size: 30px;



            
        }

        .nav ul li.active a .text {
            color: gold;
            font-family: "Kalnia Glaze", serif;
            font-weight: 800;
            font-style: normal;
            
        }

        .nav ul li:hover a .icon,
        .nav ul li:hover a .text {
            color: #0b8c4c;
        }

        .nav ul li.active a .icon::before {
            position: absolute;
            content: "";
            inset: 5px;
            width: 60px;
            background: #fff;
            border-radius: 50%;
            z-index: -1;
        }

        .bottom {
            position: absolute;
            width: 100%;
            bottom: 0;
        }

        .image {
            position: relative;
            height: 40px;
            width: 40px;
            border-radius: 50%;
            overflow: hidden;
        }

        .image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .main-content {
            margin-left: 320px;
            padding: 20px;
        }

        .dashboard .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .box {
            background: #333;
            padding: 20px;
            border-radius: 10px;
            color: black;
            flex: 1;
            min-width: 250px;

            
        }

        .box h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .box p {
            font-size: 1.2em;
        }

        .box .btn {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #0b8c4c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .box .btn:hover {
            background-color: #085f32;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 1024px) {
            .nav {
                width: 250px;
            }

            .main-content {
                margin-left: 270px;
            }
        }

        @media (max-width: 768px) {
            .nav {
                width: 200px;
            }

            .main-content {
                margin-left: 220px;
            }

            .nav ul {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .nav ul li {
                width: 100%;
                text-align: center;
            }

            .nav ul li a {
                justify-content: center;
            }

            .nav ul li.logo .icon {
                font-size: 1.8em;
            }

            .nav ul li.logo .text {
                font-size: 1em;
            }

            .nav ul li .icon,
            .nav ul li .text {
                font-size: 1.2em;
            }
        }

        @media (max-width: 480px) {
            .nav {
                width: 180px;
            }

            .main-content {
                margin-left: 200px;
            }

            .nav ul li {
                font-size: 0.9em;
            }

            .box {
                width: 100%;
                margin-bottom: 20px;
            }
        }


    body {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    perspective: 1500px;
    overflow-x: hidden;
}

.nav {
    position: fixed;
    width: 280px;
    height: 100vh;
    background: #1e272e;
    padding-left: 10px;
    box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
    border-right: 1px solid rgba(255, 255, 255, 0.05);
    transform: translateZ(0);
}

.nav ul li.active {
    background: linear-gradient(135deg, #222, #2d3436);
    border-top-left-radius: 50px;
    border-bottom-left-radius: 50px;
    box-shadow: inset 5px 5px 15px rgba(0, 0, 0, 0.5),
                inset -5px -5px 15px rgba(255, 255, 255, 0.05);
}

.nav ul li.active a .icon,
.nav ul li.active a .text {
    color: gold;
    text-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
}

.nav ul li a .icon,
.nav ul li a .text {
    transition: 0.4s ease;
    transform-style: preserve-3d;
}

.nav ul li a:hover .icon,
.nav ul li a:hover .text {
    transform: translateX(8px) scale(1.05);
    color: #0b8c4c;
    text-shadow: 0 0 5px rgba(11, 140, 76, 0.4);
}

.box {
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.4);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    backdrop-filter: blur(8px);
}

.box:hover {
    transform: rotateY(6deg) scale(1.03);
    box-shadow: 0 2rem 3rem rgba(0, 0, 0, 0.5);
}

.box .btn {
    background-color: #0b8c4c;
    border-radius: 8px;
    font-weight: bold;
    box-shadow: 0 8px 15px rgba(11, 140, 76, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.box .btn:hover {
    background-color: #0eaa5d;
    transform: scale(1.05);
}
body {
    min-height: 100vh;
    background: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #000000);
    background-size: 400% 400%;
    animation: gradientBG 200s ease infinite;
    color: white;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.nav {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100vh;
    background: #111;
    padding: 20px 0;
    box-shadow: 10px 0 30px rgba(0, 0, 0, 0.6);
    border-right: 1px solid rgba(255, 255, 255, 0.05);
    overflow-y: auto;
    z-index: 999;
    transform-style: preserve-3d;
    animation: floatSidebar 10s ease-in-out infinite alternate;
}

/* Sidebar float 3D animation */
@keyframes floatSidebar {
    0% {
        transform: rotateY(0deg) translateZ(0px);
    }
    100% {
        transform: rotateY(-8deg) translateZ(10px);
    }
}

.nav ul {
    list-style: none;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.nav ul li {
    transition: all 0.3s ease;
}

.nav ul li.logo {
    margin-bottom: 40px;
    text-align: center;
    pointer-events: none;
}

.nav ul li.logo .icon {
    font-size: 2.5em;
    color: #00ffe7;
    text-shadow: 0 0 20px #00ffe7;
}

.nav ul li.logo .text {
    font-family: "Kalnia Glaze", serif;
    font-size: 1.6em;
    font-weight: 700;
    color: gold;
    text-shadow: 0 0 10px gold;
}

.nav ul li a {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    text-decoration: none;
    color: #ccc;
    transition: all 0.4s ease;
    position: relative;
    transform-style: preserve-3d;
    perspective: 1000px;
    border-radius: 8px;
}

/* Hover & active state */
.nav ul li a:hover,
.nav ul li.active a {
    background: rgba(255, 255, 255, 0.05);
    transform: translateX(10px) rotateY(8deg) scale(1.05);
    box-shadow: 0 10px 20px rgba(0, 255, 200, 0.2);
    color: #0f0;
}

.nav ul li a:hover .icon,
.nav ul li.active a .icon {
    color: #0f0;
    text-shadow: 0 0 15px #0f0;
}

.nav ul li a:hover .text,
.nav ul li.active a .text {
    color: #0f0;
    text-shadow: 0 0 10px #0f0;
}

.nav ul li .icon {
    font-size: 1.4em;
    width: 35px;
    text-align: center;
    margin-right: 10px;
    transition: all 0.3s ease;
}

.nav ul li .text {
    font-size: 1em;
    letter-spacing: 1px;
    font-weight: 500;
    transition: all 0.3s ease;
}

/* Scrollbar custom */
.nav::-webkit-scrollbar {
    width: 5px;
}
.nav::-webkit-scrollbar-track {
    background: #111;
}
.nav::-webkit-scrollbar-thumb {
    background: #0f0;
    border-radius: 10px;
}

        
    </style>
</head>
<body>
    <!-- Sidebar -->
  <nav class="nav">
    <ul>
        <!-- Logo Section -->
        <li class="logo">
            <a href="#">
                <div class="icon">
                    <i class="fab fa-codepen"></i>
                </div>
                <div class="text">AdminPanel</div>
            </a>
        </li>

        <!-- Navigation Menu -->
        <li class="active">
            <a href="dashboard.php">
                <div class="icon"><i class="fas fa-home"></i></div>
                <div class="text">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="products.php">
                <div class="icon"><i class="fas fa-box"></i></div>
                <div class="text">Products</div>
            </a>
        </li>
        <li>
            <a href="placed_orders.php">
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="text">Orders</div>
            </a>
        </li>
        <li>
            <a href="users_accounts.php">
                <div class="icon"><i class="fas fa-user"></i></div>
                <div class="text">Users</div>
            </a>
        </li>
        <li>
            <a href="admin_accounts.php">
                <div class="icon"><i class="fas fa-user-shield"></i></div>
                <div class="text">Admins</div>
            </a>
        </li>
        <li>
            <a href="messages.php">
                <div class="icon"><i class="fas fa-envelope"></i></div>
                <div class="text">Messages</div>
            </a>
        </li>

        <!-- Bottom Logout -->
        <li class="logout">
            <a href="admin_logout.php">
                <div class="icon"><i class="fas fa-right-from-bracket"></i></div>
                <div class="text">Logout</div>
            </a>
        </li>
    </ul>
</nav>


    <!-- Main Content Area -->
    <div class="main-content">
        <section class="dashboard">
            <div class="box-container">
                <div class="box">
                    <h3>Welcome!</h3>
                    <p><?= $fetch_profile['name']; ?></p>
                    <a href="update_profile.php" class="btn">Update Profile</a>
                </div>
            </div>
        </section>
    </div>

    <!-- Inline JS -->
    <script>
        let items = document.querySelectorAll(".menu li");

        items.forEach((item) => {
            item.addEventListener("click", () => {
                items.forEach((link) => {
                    link.classList.remove("active");
                });

                item.classList.add("active");
            });
        });


        // Ensure the sidebar stays fixed on scroll
window.addEventListener("scroll", () => {
    const sidebar = document.querySelector(".nav");
    if (window.scrollY > 0) {
        sidebar.style.position = "fixed";
        sidebar.style.top = "0";
    } else {
        sidebar.style.position = "fixed"; // Always fixed to the top
    }
});

    </script>
</body>
</html>
