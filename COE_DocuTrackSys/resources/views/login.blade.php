{{-- 
VISION CUBE SOFTWARE CO. 

View: Login
The page that would require account credentials to login
It contains:
-Form for logging in
-Link to redirect the user whenever the password is forgotten

Contributor/s: 
Calulut, Joshua Miguel C. 
Sanchez, Shane David U.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Document Tracking System</title>
  <link rel="stylesheet" href="{{ asset('css/register_login.css') }}">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <div class="wrapper">
    {{-- Account Login form --}}
    <form action="{{route('account.login')}}" method="POST">
      @csrf
      @method('POST')
      <div class="loginLogo">
          <img src="{{asset('img/COE.png')}}" alt="Logo">
      </div>
      <h1>Login</h1>
      <div class="input-box">
        <input type="text" name= "email" placeholder="Email" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" name = "password" placeholder="Password" required>
        <i class='bx bxs-lock-alt' ></i>
      </div>
      <div class="remember-forgot">
        {{-- Redirect the user to forget password when password is forgotten --}}
        <label><input type="checkbox" name="remember">Remember Me</label>
        <a href="{{route('account.forgotPassword')}}">Forgot Password</a>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register-link">
        {{-- Redirect the user to account creation whenever no account is made --}}
        <p>Dont have an account? <a href="{{route('account.create')}}">Register</a></p>
      </div>
    </form>
  </div>

  {{--
      IMPORTANT: NEEDED IMPLEMENTATION TO SHOW ERRORS BELOW THE FORM INSTEAD, MUCH LIKE
      THE ONES PRESENT IN DOCUMENT MODAL FORM
  --}}
  @if ($errors->any())
    <div class="alert">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
</body>
</html>