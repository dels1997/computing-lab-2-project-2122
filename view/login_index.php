<?php require_once __DIR__ . '/login_header.php'; ?>

    <form method="POST" action="freediving.php" class="svelte-1yxl0k9" autocomplete="off">
        Login or register
        <!-- <input type="password" name="password"></br>
        <button type="submit" name="register">Login or register!</button> -->
        
        <div class="field">
            <input type="text" name="username" class="input1" placeholder="">
            <label for="username" class="label1">Username (or e-mail)</label>
        </div>

        <div class="field">
            <input type="password" name="password" class="input1" placeholder="">
            <label for="password" class="label1">Password</label>
        </div>
        <button type="submit" name="login" class="btn btn-white btn-big">Enter</button>
        <button type="submit" name="register" class="btn btn-white btn-big">Sign me up</button>
    </form>

<?php require_once __DIR__ . '/_footer.php'; ?>
