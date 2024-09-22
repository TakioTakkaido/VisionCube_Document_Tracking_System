{{-- 
VISION CUBE SOFTWARE CO. 

View: Forgot Password
The page that would change the user's password by entering their respective email
It contains:
-Form to fill up email used in the system
-Sending of verification link to their respective emails for changing their password

Contributor/s: 
Calulut, Joshua Miguel C. 
Sanchez, Shane David U.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" href="{{Vite::asset('resources/img/COE.png')}}" type="image/x-icon">
  @vite(['resources/css/register_login.css'])
</head>
<body>
  <div class="wrapper">
    {{-- Forgot Password form --}}
    <form action="{{route('account.forgotPassword')}}" id="forgotPasswordForm" method="POST">
        @csrf
        @method('POST')
        <h1>Forgot Password</h1>
        <div class="input-box">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <i class='bx bx-mail-send'></i>
        </div>
        {{-- 
          Sends verification link into the inputted email, whenever the email is proved to be present in the accounts table
          IMPORTANT: NEEDED IMPLEMENTATION IN SENDING LINK IN EMAIL
        --}}
        <button type="submit" class="btn">Send Verification</button>
        <div class="register-link">
          {{-- Redirect the user to login page whenever the password is remembered --}}
            <p>Remembered your password? <a href="{{route('account.showLogIn')}}">Login</a></p>
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
