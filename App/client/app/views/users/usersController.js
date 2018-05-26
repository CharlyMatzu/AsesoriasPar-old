app.controller('UsersController', function($scope, $http, UsersService){
    $scope.loading = true;
    $scope.users = [];

    //Cargando controllador, obtiene usuarios
    $scope.getAll = function(){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/users"
        }).then(function(success){
            
            console.log( success.data );
            $scope.users = success.data;

        }, function(error){
            console.log( error );
        });    
    }
    
    $scope.delete = function(user_id){
        $http({
            method: 'DELETE',
            url: "http://api.asesoriaspar.com/index.php/users/"+user_id
        }).then(function (response){
            console.log( response.data.message );
            $scope.getAll();
        },function (response){
            console.log( response.data.message );
        });
    }

    $scope.insert = function(user){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/users"
        }).then(function(success){
            
            console.log( success.data );
            $scope.users = success.data;

        }, function(error){
            console.log( error );
        });    
    }

    //Obtiene todos por default
    $scope.getAll();

});