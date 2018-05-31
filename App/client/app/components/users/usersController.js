app.controller('UsersController', function($scope, $http, $window, Notification, UsersService){
    $scope.page.title = "Usuarios > Registrados";

    $scope.users = [];
    $scope.status = "";
    $scope.loading = false;


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

    $scope.goToNewUser = function(){
        $window.location.href = '#!/usuarios/nuevo';
    }



    $scope.getUsers = function(){
        $scope.status = "";
        $scope.loading = true;

        UsersService.getUsers(
            function(success){
                if( success.status == NO_CONTENT ){
                    $scope.status = "No se encontraron usuarios";
                    $scope.users = [];
                }
                else{
                    // Notification.success('Datos obtenidos');
                    $scope.users = success.data;
                }
                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data.message);
                $scope.status = "Error";
                //Enabling refresh button
                $scope.loading = false;
            });
    }


    $scope.searchUser = function(data){
        if( data == null || data == "" ) 
            return;


        $scope.status = "";
        $scope.loading = true;

        UsersService.searchUsers(data,
            function(success){
                if( success.status == NO_CONTENT ){
                    $scope.status = "No se encontraron usuarios";
                    $scope.users = [];
                }
                else{
                    $scope.users = success.data;
                }
                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data);
                $scope.status = "Error";
                //Enabling refresh button
                $scope.loading = false;
            });
    }


    $scope.delete = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, user_id);

        UsersService.deleteUser(user_id,
            function(success){
                Notification.success("Usuario eliminado con exito");
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