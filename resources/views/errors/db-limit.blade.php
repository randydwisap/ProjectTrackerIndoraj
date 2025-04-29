<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Too Many Connections - Database Limit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
            text-align: center;
            color: #222;
        }

        .container {
            padding: 4rem 2rem;
        }

        .image {
            max-width: 300px;
        }

        .title {
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 2rem 0 1rem;
        }

        .message {
            font-size: 1.1rem;
            color: #555;
        }

        @media (min-width: 768px) {
            .container {
                padding: 6rem;
            }

            .title {
                font-size: 3rem;
            }

            .image {
                max-width: 400px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/sadness.png') }}" alt="Sadness" class="image">
        <div class="title">Awww... Don’t Cry.</div>
        <p class="message">It’s just a database connection limit.<br>Too many users are connected right now.<br>Please try again in a few moments.</p>
    </div>
</body>
</html>
