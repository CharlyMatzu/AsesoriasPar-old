app.controller('UsersController', function($scope, $http, $window, Notification, UsersService){
    $scope.page.title = "Staff > Registrados";
    
    $scope.users = [];
    $scope.user = {
        id: 0,
        email: "",
        pass: "",
        role: ""
    };

    /**
     * redirect
     */
    $scope.goToNewUser = function(){
        $window.location.href = '#!/usuarios/nuevo';
    }


    $scope.getUsers = function(){
        $scope.loading.status = true;

        UsersService.getUsers(
            function(success){
                if( success.status == NO_CONTENT ){
                    $scope.loading.status = "No se encontraron usuarios";
                    $scope.users = [];
                }
                else{
                    $scope.users = success.data;
                }
                //Enabling refresh button
                $scope.loading.status = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data.message);
            }
        );
    }


    /**
     * 
     * @param {String} data Informacion a buscar (correo)
     */
    $scope.searchUser = function(data){
        if( data == null || data == "" ) 
            return;

        $scope.users = [];
        $scope.loading.status = true;
        $scope.loading.message = "Buscando usuarios con "+data;

        UsersService.searchUsers(data,
            function(success){
                if( success.status == NO_CONTENT )
                    $scope.loading.message = "No se encontraron usuarios";
                else
                    $scope.users = success.data;

                //Enabling refresh button
                $scope.loading.status = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data);
                $scope.loading.message = "Ocurrio un error =(";
                $scope.loading.status = false;
            }
        );
    }


    /**
     * 
     * @param {*} user 
     * @return bool
     */
    var validate = function(user){
        if( user.id == 0 || user.id == "" || user.id == null){
            Notification.error("Hay un error con el ID: contactar a un administrador");
            return false;
        }
        if( user.pass != user.pass2 ){
            Notification.warning('Contraseñas no coinciden');
            return false;
        }
        if( user.role == null || user.role == "" ){
            Notification.warning('No se ha seleccionado un rol');
            return false;
        }
            
        return true;
    }


    /**
     * Encargado de abrir el panel de edicion
     * @param {*} user 
     */
    $scope.editUser = function(user){
        if( user.role == "basic" ){
            Notification.warning("No soporta tipo Basic, se debe arreglar");
            return;
        }

        //Asignacion de datos al formulario
        $scope.user.id = user.id;
        $scope.user.email = user.email;
        $scope.user.pass = user.pass;
        $scope.user.role = user.role;
        //Open update form
        $scope.showForm = true;
    }

    $scope.updateFormUser = function(user){
        if( !validate(user) )
            return;
        

        //Deshabilita botones
        $scope.disableButtons(true, user.id);

        UsersService.updateUser(user,
            function(success){
                Notification.success("Actualizado con exito");
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error: "+error.data);
                //Habilita botones
                $scope.disableButtons(false, user.id);
            }

        );
        
        
        //Close form
        $scope.updateForm = false;
        //Clean value
        $scope.user.id = 0;
        $scope.user.email = "";
        $scope.user.pass = "";
        $scope.user.role = "";
    }


    /**
     * 
     * @param {int} user_id ID del usuario
     */
    $scope.deleteUser = function(user_id){
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
            }
        );
    }

    /**
     * 
     * @param {int} user_id ID del usuario
     */
    $scope.enableUser = function(user_id){
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
            }
        );
    }


    /**
     * 
     * @param {int} user_id ID del usuario
     */
    $scope.disableUser = function(user_id){
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
            }
        );
    }


    //Se carguen datos al iniciar pagina
    $scope.getUsers();

});