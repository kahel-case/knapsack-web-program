<nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold fs-3">Snapsack</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white">Welcome, <strong><?= $_SESSION['username'] ?></strong></span>
            <button class="btn btn-danger btn-sm rounded-pill" onclick="window.location.href='logout.php'"><strong>Logout</strong></button>
        </div>
    </div>
</nav>