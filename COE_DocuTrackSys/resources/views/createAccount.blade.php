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
        <input type="text" name="name" placeholder="Username" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="email" name="email" placeholder="Email" required>
        <i class='bx bx-mail-send'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      {{-- <div class="input-box">
        <input type="password" name="confirmpassword" placeholder="Confirm Password" required>
        <i class='bx bxs-lock-alt'></i>
      </div> --}}
      <div class="input-box">
      <button type="submit" class="btn">Register</button>
    </form>
    <div class="register-link">
      <p>Already have an account? <a href="login.html">Login</a></p>
    </div>
  </div>
  {{-- Check Password --}}
  {{-- <script>
    document.getElementById("registrationForm").onsubmit = function() {
        var password = document.getElementById("password").value;
        var confirm_password = document.getElementById("confirm_password").value;
        if (password.length < 8) {
            alert("Password must be at least 8 characters long");
            return false;
        }
  
    };
  </script> --}}
</body>
</html>