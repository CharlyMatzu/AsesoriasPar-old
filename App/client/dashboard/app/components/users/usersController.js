angular.module("Dashboard").controller('UsersController', function($scope,  $window, Notification, UsersService, STATUS){
    
    
    $scope.page.title = "Staff > Registrados";
    
    $scope.users = [];
    $scope.user = {
        id: 0,
        email: "",
        pass: "",
        role: ""
    };

    $scope.showUpdateUser = false;
    $scope.loading = true;

    /**
     * redirect
     */
    $scope.goToNewUser = function(){
        $window.location.href = '#!/usuarios/nuevo';
        return;
    }


    $scope.getUsers = function(){
        $scope.loading = true;

        UsersService.getUsers()
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT )
                    $scope.users = [];
                else
                    $scope.users = success.data;

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data);
                $scope.loading = false;
            });
    };


    /**
     * 
     * @param {String} data Información a buscar (correo)
     */
    $scope.searchUser = function(data){
        if( data == null || data == "" )
            return;

        $scope.users = [];
        $scope.loading = true;

        UsersService.searchUsers(data)
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT )
                    $scope.users = [];
                else
                    $scope.users = success.data;

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data);
                $scope.loading = false;
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
     * Encargado de abrir el panel de edición
     * @param {*} user 
     */
    $scope.editUser = function(user){
        if( user.role === "basic" ){
            Notification.warning("No soporta tipo Basic, se debe arreglar");
            return;
        }

        //Asignación de datos al formulario
        $scope.user.id = user.id;
        $scope.user.email = user.email;
        $scope.user.pass = user.pass;
        $scope.user.role = user.role;
        //Open update form
        $scope.showUpdateUser = true;
    }

    $scope.updateUser = function(user){
        if( !validate(user) )
            return;

        var message = "Se actualizarán datos de acceso ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        $scope.loading = true;

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-user-'+user.id);

        UsersService.updateUser(user)
            .then(function(success){
                Notification.success("Actualizado con éxito");
                $scope.showUpdateUser = false;
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error: "+error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-user-'+user.id);
                $scope.loading = false;
                $scope.showUpdateUser = false;
            });
    };



    $scope.updatePass = function(user_id, pass){
        if( pass.pass !== pass.pass2 ){
            Notification.warning("Contraseñas no coinciden")
            return;
        }

        var message = "Se actualizará contraseña ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        $scope.loading = true;

        //Deshabilita botones

        UsersService.updatePassword(user_id, pass.pass)
            .then(function(success){
                Notification.success("Actualizado con éxito");
                $scope.showUpdateUser = false;
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error: "+error.data);
                //Habilita botones
                $scope.loading = false;
                $scope.showUpdateUser = false;
            });
    };


    /**
     * 
     * @param {int} user_id ID del usuario
     */
    $scope.deleteUser = function(user_id){
        var message = "Se eliminará usuario ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-user-'+user_id);

        UsersService.deleteUser(user_id)
            .then(function(success){
                Notification.success("Usuario eliminado con éxito");
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error al eliminar usuarios: " + error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-user-'+user_id);
            });
    }

    /**
     * 
     * @param {int} user_id ID del usuario
     */
    $scope.enableUser = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-user-'+user_id);

        Notification('Procesando...');
        UsersService.changeStatus(user_id, ACTIVE)
            .then(function(success){
                Notification.success("Habilitado con éxito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error al Habilitar usuario: " + error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-user-'+user_id);
            });
    }


    /**
     * 
     * @param {int} user_id ID del usuario
     */
    $scope.disableUser = function(user_id){
        var message = "Se deshabilitará usuario ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-user-'+user_id);

        Notification('Procesando...');
        UsersService.changeStatus(user_id, DISABLED)
            .then(function(success){
                Notification.success("Deshabilitado con éxito");
                $scope.getUsers();
            },
            function(error){
                Notification.error("Error al deshabilitar usuario: " + error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-user-'+user_id);
            });
    };


    //Se carguen datos al iniciar pagina
    (function(){
        $scope.getUsers();
    })();

});