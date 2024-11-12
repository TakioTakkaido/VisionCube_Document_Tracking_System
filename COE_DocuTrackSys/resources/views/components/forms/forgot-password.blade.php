<div class="wrapper">
    {{-- Forgot Password form --}}
    <form id="forgotPasswordForm" method="POST" autocomplete="off">
        @csrf
        @method('POST')

        {{-- Title --}}
        <h1>Forgot Password</h1>

        {{-- CSRF Token --}}
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

        {{-- Email Input --}}
        <div class="input-box">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <span class="error" id="error-email" style="display: none"></span>
            <i class='bx bx-mail-send'></i>
        </div>


        {{-- Submit Button --}}
        <button type="submit" class="btn" id="sendResetPasswordBtn">Send Reset Password Link</button>

        <div class="register-link">
            {{-- Redirect the user to login page whenever the password is remembered --}}
            <p>Remembered your password? <a href="{{route('show.login')}}">Back to Login</a></p>
        </div>

    </form>
</div>