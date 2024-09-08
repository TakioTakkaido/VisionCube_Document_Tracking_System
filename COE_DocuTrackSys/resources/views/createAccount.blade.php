{{-- 
VISION CUBE SOFTWARE CO. 
View: Create Account
Displays the account creation of the for the document tracking system.
COE Document Tracking System. 
It contains:
-Information relevant to the document
-Status of the document
-Versions of the document
Contributor/s: 
Calulut, Joshua Miguel C. 
Sanchez, Shane David U.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Document Tracking System</title>
  <link rel="stylesheet" href="{{ asset('css/register_login.css') }}">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
  <div class="wrapper">
    <form action="{{route('account.store')}}" method="POST">
      @csrf
      <h1>Create Account</h1>
      <div class="input-box">
        <input type="text" name="name" placeholder="Username" autocomplete="name" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="email" name="email" placeholder="Email" autocomplete="email" required>
        <i class='bx bx-mail-send'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      <div class="input-box">
      <button type="submit" class="btn">Register</button>
    </form>
    <div class="register-link">
      <p>Already have an account? <a href="{{ route('account.showLogIn') }}">Login</a></p>
    </div>
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
