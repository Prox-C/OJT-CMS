<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">
    <style>
        .login-card {
            transition: all 0.3s ease;
            height: 100%;
            cursor: pointer;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .logo-container {
            max-width: 100%;
            height: auto;
        }
        .logo-img {
            max-width: 100%;
            height: auto;
            max-height: 300px;
            position: relative;
            right: 15px;
        }
    </style>
</head>
<body style="background: #720201;">
    <div class="container-fluid p-md-0 p-lg-5 d-flex justify-content-center align-items-center flex-column w-100" style="height: 100vh;">
        <div class="row w-100 h-100 align-items-center">
            <!-- Logo Column -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center mb-5 mb-lg-0">
                <div class="logo-container text-center p-4">
                    <!-- Replace with your actual logo -->
                    <img src="{{ asset('assets/images/ojtcms_logo2.png') }}" alt="System Logo" class="logo-img">
                    <h1 class="text-white mt-4">Student Internship Portal</h1>
                </div>
            </div>
            
            <!-- Login Cards Column -->
            <div class="col-lg-6">
                <div class="row g-4">
                    <!-- Intern Card -->
                    <div class="col-md-6 col-12">
                        <a href="intern/login" class="text-decoration-none">
                            <div class="card login-card bg-white text-center p-4">
                                <div class="card-body">
                                    <div class="card-icon text-primary">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h3 class="card-title">Intern</h3>
                                    <p class="card-text">Login to access your internship dashboard</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Coordinator Card -->
                    <div class="col-md-6 col-12">
                        <a href="coordinator/login" class="text-decoration-none">
                            <div class="card login-card bg-white text-center p-4">
                                <div class="card-body">
                                    <div class="card-icon text-success">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h3 class="card-title">Coordinator</h3>
                                    <p class="card-text">Manage interns and internship programs</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- HTE Card -->
                    <div class="col-md-6 col-12">
                        <a href="hte/login" class="text-decoration-none">
                            <div class="card login-card bg-white text-center p-4">
                                <div class="card-body">
                                    <div class="card-icon text-warning">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h3 class="card-title">HTE</h3>
                                    <p class="card-text">Host Training Establishment portal</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Admin Card -->
                    <div class="col-md-6 col-12">
                        <a href="/admin/login" class="text-decoration-none">
                            <div class="card login-card bg-white text-center p-4">
                                <div class="card-body">
                                    <div class="card-icon text-danger">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <h3 class="card-title">Admin</h3>
                                    <p class="card-text">System administration portal</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome for icons (optional) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>