app.factory('AuthService', function($http, local, localStorageService){


    var authenticate = function(user){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/users/confirm/"+token
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

    /**
     * Verifica si esta autenticado, es decir, si hay informacion del usuario
     * almacenada en el localstorage y que este mismo este en el sistema (API)
     */
    var isAuthenticated = function(){
        return true;
    }

});