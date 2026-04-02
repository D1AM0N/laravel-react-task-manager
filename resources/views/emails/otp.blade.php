<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Access Code</title>
    <style>
        /* Base Reset */
        body { margin: 0; padding: 0; width: 100% !important; background-color: #f4f7ff; }
        
        .wrapper { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            background-color: #f4f7ff; 
            padding: 40px 20px; 
            text-align: center; 
        }

        .card { 
            max-width: 400px; 
            margin: 0 auto; 
            background: #ffffff; 
            padding: 30px; 
            border-radius: 24px; 
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.08); 
            border: 1px solid #eef2ff; 
        }

        .logo { 
            font-weight: 900; 
            font-size: 22px; 
            text-transform: uppercase; 
            letter-spacing: -1px; 
            margin-bottom: 25px; 
            display: block;
        }

        .indigo { color: #4f46e5; }
        .dark { color: #111827; }

        .label { 
            font-size: 10px; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            color: #9ca3af; 
            margin-bottom: 15px; 
        }

        .otp-box { 
            background-color: #f5f3ff; 
            border: 2px dashed #c7d2fe; 
            border-radius: 16px; 
            padding: 24px 10px; 
            margin: 24px 0; 
        }

        .otp-code { 
            font-size: 36px; 
            font-weight: 900; 
            letter-spacing: 10px; 
            color: #4f46e5; 
            margin: 0; 
            /* Ensure code doesn't wrap on small screens */
            white-space: nowrap;
        }

        .footer { 
            font-size: 12px; 
            color: #6b7280; 
            margin-top: 25px; 
            line-height: 1.6; 
        }

        .expiry {
            color: #ef4444;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="logo">
                <span class="indigo">TASK</span><span class="dark">MASTER</span>
            </div>
            
            <div class="label">Identity Authentication</div>
            
            <p style="font-size: 15px; color: #4b5563; margin-top: 0;">
                Your high-security operative code is ready:
            </p>
            
            <div class="otp-box">
                <h1 class="otp-code">{{ $otp }}</h1>
            </div>
            
            <p class="footer">
                This tactical code expires in <span class="expiry">10 minutes</span>.<br>
                If you did not initiate this request, secure your account credentials immediately.
            </p>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f3f4f6;">
                <p style="font-size: 10px; color: #9ca3af; text-transform: uppercase;">
                    System: Stormbreaker v1.0
                </p>
            </div>
        </div>
    </div>
</body>
</html>