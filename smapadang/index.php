<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persebaran SMA Negeri Padang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        /* --- Navbar Styling --- */
        .navbar-custom {
            background-color: #0c1825; 
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .navbar-custom .navbar-brand {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.8rem;
        }
        .navbar-custom .navbar-brand i {
            color: #0d6efd;
        }
        .navbar-custom .nav-link {
            color: #ffffff;
            font-weight: 500;
            margin-right: 15px;
            transition: color 0.3s ease;
        }
        .navbar-custom .nav-link:hover {
            color: #0d6efd;
        }
        .navbar-custom .btn-admin {
            background-color: #dc3545; 
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .navbar-custom .btn-admin:hover {
            background-color: #c82333;
        }

        /* --- Hero Section Styling --- */
        .hero-section {
            background-color: #0c1825;
            background-image: url('image/gambar.jpg'); /* Pastikan path ini benar */
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden; 
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        .hero-content p {
            font-size: 1.25rem;
            color: #cccccc;
            margin-bottom: 40px;
        }
        .btn-main-hero {
            background-color: #dc3545; 
            color: white;
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-main-hero:hover {
            background-color: #c82333;
        }
        .hero-illustration {
            display: none; 
        }

        /* --- Scroll to Top Button --- */
        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 99;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
        }
        #scrollToTopBtn:hover {
            background-color: #0a58ca;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            .hero-content p {
                font-size: 1rem;
            }
            .btn-main-hero {
                padding: 10px 20px;
                font-size: 1rem;
            }
            .hero-section {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-search"></i> FindU SMA
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="peta.php">PETA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cari.php">CARI SMA</a>
                </li>
                <li class="nav-item">
                    <!-- ✅ Perbaikan link admin -->
                    <a class="nav-link btn btn-admin ms-lg-3" href="admin/index.php">
                        ADMIN <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="hero-content">
                    <small class="text-uppercase fw-bold" style="letter-spacing: 2px;">Persebaran SMA Padang</small>
                    <h1 class="display-4 fw-bold mt-2 mb-3">Temukan Lokasi SMA Negeri di Kota Padang</h1>
                    <p class="lead mb-4">Jelajahi data sekolah dan lokasi SMA Negeri di seluruh Kota Padang.</p>
                    <a href="peta.php" class="btn btn-main-hero">
                        Lihat Peta Persebaran <i class="fas fa-map-marked-alt ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<button onclick="scrollToTop()" id="scrollToTopBtn" title="Go to top">
    <i class="fas fa-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tombol "Scroll to Top"
    var scrollToTopBtn = document.getElementById("scrollToTopBtn");

    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    };

    function scrollToTop() {
        document.body.scrollTop = 0; 
        document.documentElement.scrollTop = 0; 
    }
</script>
</body>
</html>
