app.controller('NewUserController', function($scope, $http, Notification, UsersService){
    $scope.page.title = "Usuarios > Nuevo";

    /**
     * 
     * @param {*} user 
     */
    $scope.addUser = function(user){
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

});