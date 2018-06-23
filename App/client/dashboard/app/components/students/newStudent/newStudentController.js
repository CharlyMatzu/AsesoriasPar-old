angular.module("Dashboard").controller('NewUserController', function($scope, $http, $timeout, NewUserService, Notification){
    $scope.page.title = "Usuarios > Nuevo";
    $scope.loading = false;


    /**
     * 
     * @param {*} user 
     * @return bool
     */
    var validate = function(user){
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
     * 
     * @param {*} user 
     */
    $scope.addUser = function(user){
        if( !validate(user) )
            return;

        
        //Para quitar alerts actuales
        $scope.alert.type = '';
        //Se pone en cargando
        $scope.loading.status = true;

        //Peticion
        NewUserService.addUser(user,
            function(success){
                $scope.alert.type = 'success';
                $scope.alert.message = "Se ha registrado usuario correctamente"
                $scope.loading.status = false;
            },
            function (error){
                if( error.status == CONFLICT )
                    $scope.alert.type = 'warning';
                else
                    $scope.alert.type = 'error';

                $scope.alert.message = error.data;
                $scope.loading.status = false;
                
            }
        );
    }


});