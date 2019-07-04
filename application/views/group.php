<div id="app" class="container">
  <h1 class="text-center">Manage Groups</h1>
  <div class="row">
    <div class="col-md-12">
      <a class="btn btn-primary" role="button" @click="addNewGroup()">
        <i class="glyphicon glyphicon-plus"></i>Add New Group</a></div>
  </div>
  <ul class="list-group">
    <li class="list-group-item"><span class="title left"> No</span>
      <span class="title"> Group Name</span><span class="title right">Menu </span>
      </li>

    <group-item v-for="group in grouplist" :group="group"></group-item>
        <add-new-group></add-new-group>
        <edit-group></edit-group>
  </ul>

</div>
<script src="<?php echo base_url() ?>assets/js/app/group.js"></script>