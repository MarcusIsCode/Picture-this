<?php
unset($_SESSION['showFeed']);
?>

<div class="search">

    <form method="post" class="form search__form">
        <div class="form__group">
            <input type="text" name="search" id="search" autocomplete="off" required>
        </div><!-- /form-group -->

        <button type="submit" class="button"></button>
    </form>

    <div class="found__users">
    </div>

</div>
