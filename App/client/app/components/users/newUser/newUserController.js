app.controller('NewUserController', function($scope, $http, $timeout, NewUserService, Notification){
    $scope.page.title = "Usuarios > Nuevo";
    $scope.loading = false;
    $scope.update = false;

    

    //TODO: hacer variables globales y adicionar funciones
    $scope.error = {
        status: false,
        message: ""
    };
    $scope.success = false;

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

        $scope.success = false;
        $scope.error.status = false;
        
        if( !validate(user) )
            return;

        //Se pone en cargando
        $scope.loading = true;

        NewUserService.addUser(user,
            function(success){
                $scope.success = true;
                $scope.loading = false;

                $timeout(function(){
                    $scope.success = false;
                },3000);
            },
            function (error){
                $scope.loading = false;
                $scope.error.status = true;
                $scope.error.message = error.data;

                $timeout(function(){
                    $scope.error.status = false;
                },5000);        
            });

    }

});