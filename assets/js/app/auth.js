function makeLogin(handler) {
  return function (
    u=document.getElementById('name').value,
    p=document.getElementById('password').value
  ){
    document.getElementById('er').innerHTML = ''
    axios.post(origin()+'/auth/login_attempt', formData({name: u, password: p}), {headers})
      .then(res=>{handler(res)})
      .catch(e=>console.error(e))
  }
}

function loginResponseHandler(response){
  if(response.data.status === 'success'){
    window.location = origin()
  }
  else{
    document.getElementById('er').innerHTML = response.data.password
  }
}
const login = makeLogin(loginResponseHandler)
