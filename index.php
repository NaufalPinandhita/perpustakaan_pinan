<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Pinan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Perpustakaan Pinan</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Books</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search books..." aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <a href="pages/login.php" class="btn btn-primary ms-3">Login</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <h1 class="text-center">Welcome to Perpustakaan Pinan</h1>
    </main>

    <footer class="bg-dark text-light py-3">
        <div class="container text-center">
            <p>&copy; 2025 Perpustakaan Pinan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>