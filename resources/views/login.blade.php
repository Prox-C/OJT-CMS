<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">


</head>
<body style="background: #900303;">
    <div class="container-fluid p-md-0 p-lg-5 d-flex justify-content-center align-items-center flex-column w-100" style="height: 100vh;">
    <div class="card rounded-4 p-5 d-flex flex-column align-items-center justify-content-center col-12 col-sm-8 col-md-6 col-lg-5" style="background: #fff;">
        <img class="mb-4" src="{{ asset('assets/images/EVSU_Official_Logo.png') }}" alt="" height="100" width="100">
        <h3>OJT-CMS</h3>
        <p class="text-muted mb-5">Student Internship Portal</p>

        <form action="" class="w-100">
        <div class="form-floating mb-3">
            <input type="text" class="form-control rounded-4" id="floatingInput" placeholder="">
            <label for="floatingInput">Student ID</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control rounded-4" id="floatingPassword" placeholder="">
            <label for="floatingPassword">Password</label>
        </div>

        <a class="btn w-100 btn-gold rounded-4 mt-4 text-white mb-3 py-3" href="/coordinator">Login</a>
        <a href="#" class="small text-center d-block w-100 mt-3 text-decoration-none text-muted border-top pt-3">Forgot password</a>
        </form>
    </div>
    </div>
</body>

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


</html>