app.service('SigninService', function($http){

    this.signin = function(user, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://api.asesoriaspar.com/index.php/auth",
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