{{-- Account Login form --}}
{{-- 
Input:
Email
->Error: red text, form error
Password
->Error: red text, form error
->View Password
 --}}

<div class="wrapper">
    {{-- Login Form --}}
    <form id="loginForm" method="POST" autocomplete="off">
      @csrf
      @method('POST')
      <div class="loginLogo">
        <img src="{{Vite::asset('resources/img/COE.png')}}" alt="Logo">
      </div>

      {{-- Title --}}
      <h1>Login</h1>
      
      {{-- CSRF Token --}}
      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

      {{-- Email Input --}}
      <div class="input-box mb-1">
        <input type="text" name="email" id="email" placeholder="Email" required>
        <i class='bx bxs-user'></i>  
        <span class="error" id="error-email" style="display: none"></span>
      </div>

      {{-- Password Input --}}
      <div class="input-box mb-5">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i class='bx bx-hide' id="showPassword" style="cursor: pointer;"></i>
        <span class="error" id="error-password" style="display: none"></span>
      </div>

      <div class="remember-forgot mb-0">
        {{-- Remember Password --}}        
        <label>
          <input type="checkbox" name="remember">Remember Me
        </label>

        {{-- Redirect the user to forget password when password is forgotten --}}
        <a href="{{route('show.forgotPassword')}}">Forgot Password?</a>
      </div>

      {{-- Submit Button --}}
      <button type="submit" class="btn" id="loginBtn">Login</button>
    </form>
  </div>