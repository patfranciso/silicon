
  <form class="login bootstrap-form-with-validation" 
    action="<?php echo base_url();?>auth/login_attempt">
    <h1 class="text-center">Staff Login</h1>
    <div class="form-group">
        <label for="name" class="control-label">Username</label>
        <input type="text" name="name" class="form-control" id="name" />
    </div>
    <div class="form-group">
        <label for="password" class="control-label">Password Input</label>
        <input type="password" name="password" class="form-control" id="password" />
    </div>
    <div id="er" style="color:red"></div>
    <div class="form-group">
        <button class="btn btn-primary" type="button" 
        onclick="login()">Sign in</button>
    </div>
  </form>
  <script src="<?php echo base_url();?>/assets/js/app/auth.js"></script>