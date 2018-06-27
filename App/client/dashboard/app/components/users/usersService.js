angular.module("Dashboard").service('UsersService', function( RequestFactory, AuthFactory){
    
    this.getUsers = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            '/users/staff',
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.searchUsers = function(data,successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            '/users/search/'+data+'/staff',
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.updateUser = function(user, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'POST',
            "/users/"+user.id,
            data = {
                email: user.email,
                password: user.pass,
                role: user.role
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.changeStatus = function(user_id, status, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/users/"+user_id+"/status/"+status,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    this.deleteUser = function(user_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'DELETE',
            "/users/"+user_id,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    

});