<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTE Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">

</head>
<body style="background: #900303;">
    <div class="container-fluid p-md-0 p-lg-5 d-flex justify-content-center align-items-center flex-column w-100" style="height: 100vh;">
        <div class="card rounded-4 p-5 d-flex flex-column align-items-center justify-content-center col-12 col-sm-8 col-md-6 col-lg-5" style="background: #fff;">
            <img class="mb-4" src="{{ asset('assets/images/EVSU_Official_Logo.png') }}" alt="" height="100" width="100">
            <h3>OJT-CMS</h3>
            <p class="text-muted mb-5">Host Training Establishment Portal</p>

            <form method="POST" action="{{ route('hte.authenticate') }}" class="w-100">
                @csrf
                <div class="form-floating mb-3">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control rounded-4 @error('email') is-invalid @enderror" 
                        id="floatingEmail" 
                        placeholder="Email Address"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                    <label for="floatingEmail">Email Address</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control rounded-4 @error('password') is-invalid @enderror" 
                        id="floatingPassword" 
                        placeholder="Password"
                        required
                        autocomplete="current-password"
                    >
                    <label for="floatingPassword">Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted" for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn w-100 btn-gold rounded-4 mt-4 text-white mb-3 py-3">
                    Login
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>