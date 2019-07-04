var EventBus = new Vue()

Vue.component('group-item',{
  template:`
  <li class="list-group-item"><span class="left">{{ group.id }}</span><span>{{ group.name }}</span>
    <span class="right">
      <a class="btn btn-default btn-sm" role="button" href="#" @click="editGroup()">
        <i class="glyphicon glyphicon-pencil"></i>Edit</a>
      <a class="btn btn-default btn-sm" role="button" href="#" @click="deleteGroup()">
        <i class="glyphicon glyphicon-trash"></i>Delete</a>
        <a class="btn btn-default" role="button" :href="'group/'+group.id">
        <i class="glyphicon glyphicon-list"></i>User List</a>
    </span>
  </li>
  `,
  props:{
    group:Object,
  },
  methods:{
    editGroup: function(){
      const that = this
      EventBus.$emit('intentEditGroup',{id: that.group.id, name: that.group.name})
    },    
    deleteGroup(){
      this.$confirm({
        title: 'Confirm',
        content: 'This group will be permanently deleted. Continue?'
      })
      .then(() => {        
        this.$notify({
          type:'success',
          title: 'Success',
          content:'Delete Group successful.'})
      })
      .catch(() => {
        this.$notify('Delete Group cancelled.')
      })
    },
  }
})

Vue.component('add-new-group',{
  template:`
  <modal v-model="showModal" :footer="false" title="Add a new Group">
    <form @submit.prevent="addGroup()">
      <div class="form-group">
        <input class="form-control" ref="newGroup" v-model="newGroup" 
          placeholder="Name for new group..." autofocus>
      </div>
      <button class="btn btn-primary" :disabled="newGroup===''">Add a new Group</button>
    </form>
  </modal>
  `,
  mounted(){
    let that = this
    EventBus.$on('intentAddGroup',function(){
      that.showModal = true
    })
  },
  data(){
    return {
    showModal:false,
    newGroup:''
    }
  },
  methods:{
    addGroup: function(){
      let that = this
      axios.post(origin()+'/v1/api/create_group_post', formData({name: this.newGroup}), {headers})
        .then(res=>{
          if(res.status === 201){
            that.showModal = false
            v.grouplist.push(res.data.group_info)
            this.$notify({
              type: 'success',
              title: 'Success',
              content: res.data.message,
              duration: 4000
            })
          }
          else if(res.status === 202){
            that.showModal = false
            EventBus.$emit('failedAddGroup', res.data.group_info)
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

Vue.component('edit-group',{
  template:`
  <modal v-model="showModal" :footer="false" title="Edit Group">
    <form @submit.prevent="editGroup()">
      <div class="form-group">
        <input class="form-control" ref="newName" v-model="newName" 
          placeholder="Name for group is required..." autofocus>
      </div>
      <button class="btn btn-primary" :disabled="nameChanged()">Edit Group</button>
    </form>
  </modal>
  `,
  mounted(){
    let that = this
    EventBus.$on('intentEditGroup',function(dgroup){
      that.showModal = true
      that.group = dgroup
      that.newName = dgroup.name
      that.originalName = dgroup.name
    })
  },
  data(){
    return {
      showModal:false,
      group:{},
      newName:'',
      originalName:'',
    }
  },
  methods:{
    editGroup: function(){
      alert("Edited :"+ this.newName)
    },
    nameChanged: function(){
      return this.newName === this.originalName
    }
  }
})

var v = new Vue({
  el:'#app',
  data:{
    grouplist:[],
  },
  created(){
    this.showAll(); 
  },
  methods:{
    addNewGroup: function(){
      EventBus.$emit('intentAddGroup')
    },
    showAll(){
      axios.get(origin()+"/v1/api/showAllGroups").then(function(response){
        v.grouplist = response.data.groups
      })
    },
  },
})