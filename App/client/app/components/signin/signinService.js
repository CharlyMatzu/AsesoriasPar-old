angular.module("LoginApp").service('SigninService', function($http, HostProvider){

    this.signin = function(user, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: HostProvider.getURL()+"/auth/signin",
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