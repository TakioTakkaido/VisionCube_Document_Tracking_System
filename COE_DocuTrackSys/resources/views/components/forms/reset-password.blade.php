<div class="wrapper">
    {{-- Login Form --}}
    <form id="resetPassword" method="POST" autocomplete="off">
      @csrf
      @method('POST')
      <div class="loginLogo">
        <img src="{{Vite::asset('resources/img/COE.png')}}" alt="Logo">
      </div>

      {{-- Title --}}
      <h1>Reset Password</h1>

      {{-- Password Input --}}
      <div class="input-box mb-1">
        <input type="password" name="password" id="password" placeholder="New Password" required>
        <i class="bx bx-hide showPassword" style="cursor: pointer;"></i>
        <span class="error" id="error-password" style="display: none"></span>
      </div>

      {{-- Confirm Password Input --}}
      <div class="input-box mb-5">
        <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm Password" required>
        <i class="bx bx-hide showConfirmPassword" style="cursor: pointer;"></i>
        <span class="error" id="error-password_confirmation" style="display: none"></span>
      </div>

      <p class="mb-2">Resetting your password will automatically log you out from all other devices.</p>
      {{-- Submit Button --}}
      <button class="btn" id="resetPasswordBtn">Reset Password</button>
    </form>
  </div>