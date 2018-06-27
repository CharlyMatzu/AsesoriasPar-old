angular.module("Dashboard").service('NewUserService', function( RequestFactory, AuthFactory){


    // this.getRoles = function(successCallback, errorCallback){
    //     $http({
    //         method: 'POST',
    //         url: RequestFactory.getURL()+"",
    //         data: {
    //             email: user.email,
    //             password: user.pass,
    //             role: user.role
    //         }
    //     }).then(function(success){
    //         successCallback(success) 
    //     }, function(error){
    //         errorCallback(error)
    //     });
    // }


    this.addUser = function(user){
        return RequestFactory.makeTokenRequest(
            'POST',
            '/users',
            data = {
                email: user.email,
                password: user.pass,
                role: user.role
            },
            AuthFactory.getToken()
        );
    }
    

});