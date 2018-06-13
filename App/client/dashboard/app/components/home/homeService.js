app.service('HomeService', function($http, RequestFactory){


    this.changeStatus = function(user_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: RequestFactory.getURL()+"/users/"+user_id+"/status/"+status
        }).then(function(success){
            var data = success.data;
            // console.log( success );
            successCallback(success);
        }, function(error){
            // console.log( error );
            errorCallback(error);
        });
    }

    
    this.getUsers = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/users"
        }).then(function(success){
            var data = success.data;
            // console.log( success );
            successCallback(success);
        }, function(error){
            // console.log( error );
            errorCallback(error);
        });
    }

    this.addUser = function(user, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: RequestFactory.getURL()+"/users",
            data: {
                email: user.email,
                password: user.pass,
                role: user.role
            }
        }).then(function(success){
            // console.log( success );
            successCallback(success) 
        }, function(error){
            // console.log( error );
            errorCallback(error)
        });
    }

    this.updateUser = function(user, successCallback, errorCallback){
        $http({
            method: 'PUT',
            url: RequestFactory.getURL()+"/users/".user_id,
            data: {
                email: user.email,
                password: user.pass,
                role: user.role
            }
        }).then(function(success){
            // console.log( success );
            successCallback(success) 
        }, function(error){
            // console.log( error );
            errorCallback(error)
        });
    }
    
    this.deleteUser = function(user_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: RequestFactory.getURL()+"/users/"+user_id
        }).then(function (success){
            // console.log( response.data.message );
            successCallback(success);
        },function (error){
            // console.log( response.data.message );
            errorCallback(error);
        });
    }
    
    

});