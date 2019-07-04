<div class="container" id="app">
    <h1 class="text-center">Manage Users</h1>
				<div class="user-name"><?php echo $active_user->name; ?></div>
    <div class="row">
      <div class="col-md-12"><a class="btn btn-primary" role="button" @click="addNewUser()">
        <i class="glyphicon glyphicon-plus"></i>Add New User</a>
      </div>
    </div>
    <ul class="list-group">
        <li class="list-group-item title"><span class="title left"> No</span>
        <span class="title"> User Name</span><span class="title group_name"> Group Name</span>
        <span class="title right">Menu </span><span class="right"></span></li>
        <user-item v-for="user in userlist" :user="user" :grouplist="grouplist"></user-item>
        <add-new-user></add-new-user>
        <edit-user></edit-user>
    </ul>
</div>
<script src="<?php echo base_url()?>assets/js/app/welcome.js"></script>