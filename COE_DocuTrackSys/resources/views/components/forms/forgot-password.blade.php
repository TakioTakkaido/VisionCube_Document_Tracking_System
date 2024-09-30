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
            <i class='bx bx-mail-send'></i>
        </div>

        {{-- 
            Sends verification link into the inputted email, whenever the email is proved to be present in the accounts table
            IMPORTANT: NEEDED IMPLEMENTATION IN SENDING LINK IN EMAIL
        --}}

        {{-- Submit Button --}}
        <button type="submit" class="btn">Send Verification</button>

        <div class="register-link">
            {{-- Redirect the user to login page whenever the password is remembered --}}
            <p>Remembered your password? <a href="{{route('show.login')}}">Login</a></p>
        </div>

    </form>
</div>