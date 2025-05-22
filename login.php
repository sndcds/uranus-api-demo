<?php
include_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f4f4f4;
        }
        form {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        input, button {
            display: block;
            width: 100%;
            margin-top: 1rem;
            padding: 0.5rem;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<form id="loginForm">
    <h2>Login</h2>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required />

    <button type="submit">Login</button>
</form>

<script>
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            // Step 1: Login to Go backend, receive token
            const loginRes = await fetch("<?= $apiBaseUrl ?>/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                credentials: "include", // for receiving cookies (if any)
                body: JSON.stringify({ email, password })
            });

            const loginData = await loginRes.json();

            if (!loginRes.ok) {
                alert("Login failed: " + loginData.error);
                return;
            }

            const token = loginData.token;
            console.log("Received token:", token);

            // Step 2: Send token to PHP to set cookie
            const phpRes = await fetch("http://localhost:8000/login-handler.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                credentials: "include", // this is required for cookies to be accepted
                body: JSON.stringify({ token })
            });

            const phpData = await phpRes.json();

            if (!phpRes.ok) {
                alert("PHP handler failed: " + phpData.error);
                return;
            }

            console.log("Cookie set successfully");
            // Step 3: Redirect
            window.location.href = "/dashboard.html"; // or wherever you want

        } catch (err) {
            console.error("Error:", err);
            alert("Something went wrong.");
        }
    });
</script>

</body>
</html>