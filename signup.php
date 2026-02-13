<!DOCTYPE html>
<html>
<body>

<h2>Signup</h2>

<form method="POST" action="signup_process.php">
  <input type="text" name="name" placeholder="Name" required><br><br>
  <input type="text" name="phone" placeholder="Indian Phone" required><br><br>
  <input type="password" name="password" placeholder="Password" required><br><br>
  <button type="submit">Sign Up</button>
</form>

<p style="margin-top:15px;">
    Already have an account?
    <a href="login.php" style="color:blue; text-decoration:none; font-weight:bold;">
        Log in
    </a>
</p>


</body>
</html>
