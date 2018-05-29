app.controller('UsersController', function($scope, $http, Notification, UsersService){
    $scope.newUser = {
        show: false,
        status: false
    };

    $scope.users = [];
    $scope.status = "Cargando usuarios..."


    /**
     * Funcion para habilitar/deshabilitar los botones de un elemento para que no se presionen de nuevo
     * @param {bool} disabled TRUE para deshabilitar, FALSE para habilitar
     * @param {int} id id del usuario al cual hacen referencia los botones de la tabla
     */
    $scope.disableButtons = function(disabled, id){
        $('.opt-user-'+id).each(function(){
            //console.log("Elemento: "+$(this).text() );
            $(this).prop('disabled', disabled);
        });
    }



    $scope.getUsers = function(){
        UsersService.getUsers(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification.primary('No hay usuarios registrados');
                }
                else{
                    // Notification.success('Datos obtenidos');
                    $scope.users = success.data;
                }
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data.message);
            });
    }


    /**
     * 
     * @param {*} user 
     */
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
                Notification.error("Error al registrar usuario: "+error.data.message);
            });
    }

    $scope.delete = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, user_id);

        UsersService.deleteUser(user_id,
            function(success){
                Notification.success("Usuario eliminado con exito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error al eliminar usuarios: " + error.data.message);
                //Habilita botones
                $scope.disableButtons(false, user_id);
            });
    }

    $scope.enable = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, user_id);

        Notification('Procesando...');
        UsersService.changeStatus(user_id, ENABLED, 
            function(success){
                Notification.success("Habilitado con exito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error al Habilitar usuario: " + error.data.message);
                //Habilita botones
                $scope.disableButtons(false, user_id);
            });
    }


    /**
     * 
     * @param {int} user_id ID del usuario
     */
    $scope.disable = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, user_id);

        Notification('Procesando...');
        UsersService.changeStatus(user_id, DISABLED, 
            function(success){
                Notification.success("Deshabilitado con exito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error al deshabilitar usuario: " + error.data.message);
                //Habilita botones
                $scope.disableButtons(false, user_id);
            });
    }

    

    //Se carguen datos al iniciar pagina
    $scope.getUsers();

});