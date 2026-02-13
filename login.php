<!DOCTYPE html>
<html>
<body>

<h2>Login</h2>

<form method="POST" action="login_process.php">
  <input type="text" name="phone" placeholder="Phone" required><br><br>
  <input type="password" name="password" placeholder="Password" required><br><br>
  <button type="submit">Login</button>
</form>

<p style="margin-top:15px;">
    Don’t have an account?
    <a href="signup.php" style="color:blue; text-decoration:none; font-weight:bold;">
        Sign up
    </a>
</p>


</body>
</html>
