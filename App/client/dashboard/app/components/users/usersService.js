angular.module("Dashboard").service('UsersService', function($http, RequestFactory, AuthFactory){
    
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
        $http({
            method: 'PUT',
            url: RequestFactory.getURL()+"/users/"+user.id,
            data: {
                email: user.email,
                password: user.pass,
                role: user.role
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }

    this.changeStatus = function(user_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: RequestFactory.getURL()+"/users/"+user_id+"/status/"+status
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
    
    this.deleteUser = function(user_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: RequestFactory.getURL()+"/users/"+user_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    

});