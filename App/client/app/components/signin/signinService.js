angular.module("LoginApp").service('SigninService', function($http, RequestFactory, AuthFactory){

    this.signin = function(user){
        return RequestFactory.makeRequest(
            'POST',
            "/auth/signin",
            data = {
                email: user.email,
                password: user.pass
            },
        );
    }

});