function origin() { return window.location.origin }

const headers = {
  'Accept':'application/json',
  'Content-Type':'application/json'
} 

function formData(obj){
  var formData = new FormData();
  for ( var key in obj ) {
    formData.append(key, obj[key]);
  } 
  return formData;
}

function get_meta(name='token'){
  return document.querySelector("meta[name='"+name+"']").getAttribute('content')
}

function get_active_user(){
  return JSON.parse(base64.decode(get_meta()))
}

function is_admin(){
  return get_active_user().kind === 'admin'
}

var EventBus = new Vue()