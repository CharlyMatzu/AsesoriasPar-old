app.controller('UsersController', function($scope, $http, Notification, UsersService){
    $scope.newUser = {
        show: false,
        status: false
    };

    $scope.users = [];
    $scope.status = "Cargando usuarios..."


    $scope.errorSnack = function(errorMessage){
        Notification.error( errorMessage );
    }

    $scope.getStatus = function(status){
        if( status == 0 )
            return "CERO";
        else if( status == 1 )
            return "UNO";
        else if( status == 2 )
            return "DOS";
    }


    $scope.getUsers = function(){
        UsersService.getUsers(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification.primary('No hay usuarios registrados');
                }
                else{
                    Notification.success('Datos obtenidos');
                    $scope.users = success.data;
                }
                    
            },
            function( error ){
                $scope.errorSnack("Error al obtener usuarios");
            });
    }


    $scope.add = function(user){
        //Se pone en cargando
        $scope.newUser.status = true

        //Se hace peticion
        UsersService.addUser(user, 
            function(success){
                Notification.success('Datos obtenidos');
                $scope.getUsers();
            }, 
            function(error){
                $scope.errorSnack("Error al registrar usuario: "+error.data.message);
            });
    }

    $scope.deleteUser = function(user_id){
        UsersService.deleteUser(user_id,
            function(success){
                $scope.getUsers();
            },
            function(error){
                $scope.errorSnack("Error al eliminar usuarios");
            });
    }

    //Se carguen datos al iniciar pagina
    $scope.getUsers();

});