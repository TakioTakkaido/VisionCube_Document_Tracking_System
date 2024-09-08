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
        <label><input type="checkbox" name="remember">Remember Me</label>
        <a href="{{route('account.forgotPassword')}}">Forgot Password</a>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register-link">
        <p>Dont have an account? <a href="{{route('account.create')}}">Register</a></p>
      </div>
    </form>
  </div>

  {{-- Add html box for error, temporary html --}}
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