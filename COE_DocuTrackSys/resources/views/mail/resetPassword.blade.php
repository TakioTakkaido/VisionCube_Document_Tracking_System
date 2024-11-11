<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>Verify Email | COE Document Tracking System</title>

    <!-- Assets -->
    <link rel="icon" href="{{ Vite::asset('resources/img/COE.png') }}" type="image/x-icon">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        #box1 {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            border: 2px solid #a50b0b;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        #englogo {
            height: 120px;
            width: 120px;
            margin-bottom: 20px;
        }

        #verify {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 24px;
            color: #a50b0b;
            margin-bottom: 20px;
            text-align: center;
        }

        #descr {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .buttonclick {
            display: inline-block;
            width: 100%;
            max-width: 300px;
            padding: 15px 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #a50b0b;
            border-radius: 10px;
            color: #ffffff;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.4s ease;
        }

        .buttonclick:hover {
            background: linear-gradient(45deg, #a50b0b, #ff4d4d);
        }

        #para {
            font-size: 16px;
            color: #aaa4a4;
            margin-top: 20px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            #box1 {
                padding: 20px;
            }

            #verify {
                font-size: 20px;
            }

            #descr {
                font-size: 16px;
            }

            .buttonclick {
                font-size: 16px;
                padding: 12px 0;
            }

            #para {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div id="box1">  
        <div id="click">      
            <img id="englogo" src="{{ $message->embed(asset('img/COE.png')) }}" alt="COE Logo">
            <h1 id="verify">Reset Password</h1>
            <h5 id="descr">Please click the button below to proceed in resetting your password.</h5>
            <!-- Updated Button -->
            <a class="buttonclick" href="{{ url(route('show.resetPassword', ['token' => $verificationLinkToken])) }}">
                <strong style="color: white">Reset Password</strong>
            </a>
            <p id="para">If you received this in error, simply ignore this email and do not click the button.</p>
        </div>
    </div>
</body>
</html>