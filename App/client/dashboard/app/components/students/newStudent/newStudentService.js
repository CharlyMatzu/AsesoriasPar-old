angular.module("Dashboard").service('NewUserService', function($http, RequestFactory, AuthFactory){

    
    this.addUser = function(user, successCallback, errorCallback){
        // RequestFactory.makeTokenRequest(
        //     'POST',
        //     "/users", //Puede ser
        //     data = {
        //         email: user.email,
        //         password: user.pass,
        //         role: user.role
        //     },
        //     AuthFactory.getToken(),
        //     successCallback,
        //     errorCallback
        // );
    }
    

});