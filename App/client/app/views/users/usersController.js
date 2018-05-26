app.controller('UsersController', function($scope, $http, UsersService){
    $scope.newUser = false;
    $scope.users = [];
    $scope.loader = {
        loading: true,
        message: "Cargando"
    };


    $scope.add = function(user){
        UsersService.addUser(function(response){
            $scope.getUsers();
        }, user);
    }

    $scope.getUsers = function(){
        UsersService.getUsers(function(response){
            if( response.status == NO_CONTENT)
                $scope.loader = { message: "No hay usuarios registrados" };
        });
    }

    $scope.deleteUser = function(user_id){
        UsersService.deleteUser(function(response){
            $scope.getUsers();
        }, user_id);
    }

    //Se carguen datos al iniciar pagina
    $scope.getUsers();

});