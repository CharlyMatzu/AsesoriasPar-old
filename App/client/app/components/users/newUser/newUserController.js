app.controller('NewUserController', function($scope, $http, $timeout, NewUserService, Notification){
    $scope.page.title = "Usuarios > Nuevo";
    $scope.loading = false;
    // $scope.error = {
    //     status: false,
    //     message: ""
    // };
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
            
        return true;
    }

    /**
     * 
     * @param {*} user 
     */
    $scope.addUser = function(user){
        
        if( !validate(user) )
            return;

        //Se pone en cargando
        $scope.loading = true;

        NewUserService.addUser(user,
            function(success){
                
            },
            function (error){
                
            });

        //Se hace peticion
        // NewUserService.addUser(user, 
        //     function(success){
        //         $scope.success = true;
        //         $scope.loading = false;
        //         $scope.getUsers();

        //         $timeout(function(){
        //             $scope.success = false;
        //         },2000);
        //     }, 
        //     function(error){
        //         $scope.loading = false;

        //         $scope.error = {
        //             status = true,
        //             message: "asdad"
        //         }
        //         $timeout(function(){
        //             $scope.error.status = false;
        //         },2000);
        //     });
    }

});