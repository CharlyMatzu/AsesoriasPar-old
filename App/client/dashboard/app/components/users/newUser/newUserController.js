angular.module("Dashboard").controller('NewUserController', function($scope,  $timeout, NewUserService, Notification){
    
    
    
    $scope.page.title = "Staff > Nuevo";
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
        $scope.loading = true;

        //Peticion
        NewUserService.addUser(user)
            .then(function(success){
                $scope.alert.type = 'success';
                $scope.alert.message = "Se ha registrado usuario correctamente"
                $scope.loading = false;
            },
            function (error){
                if( error.status == CONFLICT )
                    $scope.alert.type = 'warning';
                else
                    $scope.alert.type = 'error';

                $scope.alert.message = error.data;
                $scope.loading = false;
                
            }
        );
    }


});