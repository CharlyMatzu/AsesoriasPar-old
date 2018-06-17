angular.module("LoginApp").service('SigninService', function($http, RequestFactory){

    this.signin = function(user, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: RequestFactory.getURL()+"/auth/signin",
            data: {
                email: user.email,
                password: user.pass
            }
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

});