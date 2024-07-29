<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We're building your site</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .progress-bar-container {
            margin-top: 20px;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background-color: #007bff;
            width: 0;
            animation: progress-animation 5s linear infinite;
        }
        @keyframes progress-animation {
            0% { width: 0; }
            100% { width: 100%; }
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.reload();
        }, 5000);
    </script>
</head>
<body>
<div class="container">
    <h1>We're building your site</h1>
    <p>Please wait a moment while we set things up for you.</p>
    <div class="progress-bar-container">
        <div class="progress-bar"></div>
    </div>
    <a class="button" href="javascript:window.location.reload()">Retry</a>
</div>
</body>
</html>
