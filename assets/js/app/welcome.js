Vue.component('user-item',{
  template:`
  <li class="list-group-item"><span class="left"> {{ user.id }}</span><span>{{ user.name }}</span>
  
    <select v-model="user.group_id" @change="userGroupChanged()">    
      <option disabled value="">Please select one</option>
      <option v-for="item in grouplist" :value="item.id">
        {{ item.name }}
      </option>
    </select>
    <span class="right" v-if="is_admin()">
      <a class="btn btn-default btn-sm" role="button" href="#" @click="editUser()">
        <i class="glyphicon glyphicon-pencil"></i>Edit</a>
      <a class="btn btn-default btn-sm" role="button" href="#" @click="deleteUser()">
        <i class="glyphicon glyphicon-trash"></i>Delete</a>
    </span>
  </li>
  `,
  props:{
    user:Object,
    grouplist:Array
  },
  methods:{
    editUser: function(){
      const that = this
      EventBus.$emit('intentEditUser',{id: that.user.id, name: that.user.name})
    },    
    deleteUser(){
      this.$confirm({
        title: 'Confirm',
        content:'This user '+this.user.id+':'+this.user.name+' will be deleted. Continue?'
      })
      .then(() => {
        axios.post(origin()+'/v1/api/delete_user_post', formData({id: this.user.id}), {headers})
          .then( res=>{
            if(res.status === 200){
              v.userlist.splice(v.userlist.indexOf(this.user),1)
              this.$notify({
                type: 'success',
                title: 'Success',
                content: res.data.message,
                duration: 4000
              })
            }
            else{
              this.$notify({
                type: 'danger',
                title: 'Failed',
                content: res.data.message,
                duration: 4000
              })
            }
          })
          .catch(e=>console.log(e))
      })
      .catch(() => {
        this.$notify('Delete User cancelled.')
      })
    },
    userGroupChanged(){
      const that = this
      this.$notify({
        type: 'success',
        content: 'User Group Changed completed successfully to:'+that.user.group_id
      })
    }
  }
})

Vue.component('add-new-user',{
  template:`
  <modal v-model="showModal" :footer="false" title="Add a new User">
    <form @submit.prevent="addUser()">
      <div class="form-group">
        <input class="form-control" ref="newName" v-model="newName" 
          placeholder="Name for new user..." autofocus>
      </div>
      <button class="btn btn-primary" :disabled="newName===''">Add a new User</button>
    </form>
  </modal>
  `,
  mounted(){
    let that = this
    EventBus.$on('intentAddUser',function(){
      that.showModal = true
    })
    EventBus.$on('successAddUser',function(){
      that.showModal = false
      that.newName =''
    })
    EventBus.$on('failedAddUser',function(){
      that.showModal = false
      that.newName =''
    })
  },
  data(){
    return {
    showModal:false,
    newName:''
    }
  },
  methods:{
    addUser: function(){
      axios.post(origin()+'/v1/api/create_user_post', formData({name: this.newName}), {headers})
        .then(res=>{
          console.log(res)
          if(res.status === 201){
            EventBus.$emit('successAddUser', res.data.user_info)
            this.$notify({
              type: 'success',
              title: 'Success',
              content: res.data.message,
              duration: 4000
            })
          }
          else if(res.status === 202){
            EventBus.$emit('failedAddUser', res.data.user_info)
            this.$notify({
              type: 'danger',
              title: 'Failed',
              content: res.data.message,
              duration: 4000
            })
          }
        })
        .catch(e=>console.error(e))
    }
  }
})

Vue.component('edit-user',{
  template:`
  <modal v-model="showModal" :footer="false" title="Edit User">
    <form @submit.prevent="editUser()">
      <div class="form-group">
        <input class="form-control" ref="newName" v-model="newName" 
          placeholder="Name for user is required..." autofocus>
      </div>
      <button class="btn btn-primary" :disabled="nameChanged()">Edit User</button>
    </form>
  </modal>
  `,
  mounted(){
    let that = this
    EventBus.$on('intentEditUser',function(duser){
      that.showModal = true
      that.user = duser
      that.newName = duser.name
      that.originalName = duser.name
    })
  },
  data(){
    return {
      showModal:false,
      user:{},
      newName:'',
      originalName:'',
    }
  },
  methods:{
    editUser: function(){
      alert("Edited :"+ this.newName)
    },
    nameChanged: function(){
      return this.newName === this.originalName
    }
  }
})

const v = new Vue({
  el:'#app',
  data:{
    userlist:[],
    grouplist:[ ]
  },
  created(){
    EventBus.$on('successAddUser',(newuser)=>{
      const that = this
      that.userlist.push(newuser)
    })
    this.showAll(); 
  },
  methods:{
    addNewUser: function(){
      EventBus.$emit('intentAddUser')
    },
    showAll(){ 
      axios.get(origin()+"/v1/api/showAllUsers").then(function(response){
        v.userlist = response.data.users
      })
      axios.get(origin()+"/v1/api/showAllGroups").then(function(response){
        v.grouplist = response.data.groups
      })
  },
  },
})