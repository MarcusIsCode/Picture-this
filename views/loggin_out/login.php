<?php
//require __DIR__ . "/../app/autoload.php"; 
//page reguire this when there isn't a session 

?>

<form action="app/users/login.php" method="post">
    <div class="form-gruop">
        <label for="email"></label>
        <input class="input" type="email" name="email" id="email" placeholder="Email" required>
    </div><!-- /form-group  -->

    <!-- password-->
    <div class="form-group">
        <label for="password"></label>
        <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
    </div><!-- /form-group -->

    <button type="submit" class="btn btn-primary">Login</button>
</form>