app.factory('AuthFactory', function($http, localStorageService){
    var hostURL = "http://api.asesoriaspar.com/";

    var saveUserToLocal = function(user){

    }

    var auth = {
        
        authenticate: function(user, callback){
            $http({
                method: 'POST',
                url: hostURL + "auth"
            }).then(function (success){
                var user = success.data
                saveUserToLocal(user);
                callback(true);
            },function (error){
                callback(false);
            });
        }


    }
    
    return auth;

});