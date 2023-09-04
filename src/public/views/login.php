<link rel="stylesheet" href="<?= get_assets_uri("css/login.css") ?>">

<div class="wrapper page-wrapper">
    <div class="wrapper-inner container container-login">
        <h2>Login</h2>
        <form id="login-form" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password">

            <button type="submit">Login</button>
        </form>
    </div><!-- wrapper inner -->
</div><!-- page wrapper -->