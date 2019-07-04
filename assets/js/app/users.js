Vue.component('user',{
  template:`
  <li class="list-group-item"><span class="left"> 1</span><span>List Group Item 1</span>
    <select>
        <option value="12" selected>This is item 1</option>
        <option value="13">This is item 2</option>
        <option value="14">This is item 3</option>
    </select>
    <span class="right">
      <a class="btn btn-default btn-sm" role="button" href="#">
        <i class="glyphicon glyphicon-pencil"></i>Edit</a>
      <a class="btn btn-default btn-sm" role="button" href="#">
        <i class="glyphicon glyphicon-trash"></i>Delete</a>
    </span>
  </li>
  `,
  data:{},
  methods:{}
})


const v = new Vue({
  el:'#app',
  data:{},
  methods:{}
})