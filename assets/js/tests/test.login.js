var expect = chai.expect;
// Mocha.setup({timeout:5000})
Mocha.describe('Login', function () {
  Mocha.it('Should be just fine', function (callback) {
    this.timeout(3000)
    function resHandle(res){
      if(res.data.status !== 'success')
        callback(new Error('Error asserting login promise')) 
      else callback()
    }
    const username = 'admin'
    const password = 'password'

    const loginTest = makeLogin(resHandle)
    loginTest(username, password)

    //   makeLogin(function(res){
    //     if(res.data.status !== 'success')
    //       callback(new Error('Error asserting promise')) 
    //     else callback()
    //   })(username, password)

  })
  // .timeout(5000)
})
