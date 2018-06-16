angular.module("LoginApp").controller('SigninController', function($scope, $window, $timeout, Notification, SigninService, AuthFactory){


    $scope.signin = function(user){
        $scope.loading.status = true;
        $scope.loading.message = "Iniciando sesion";

        SigninService.signin(user,
            function(success){
                $scope.alert.type = 'success';
                $scope.alert.message = "Autenticado correctamente, redireccionando";
                // $scope.loading.status = false;
                // $timeout(function(){
                //     $scope.saveSession(success.data);
                // },2000);

                AuthFactory.setUser( success.data );
            },
            function(error){
                Notification.error("Ocurrio un error");
                $scope.alert.type = 'warning';
                $scope.alert.message = error.data;

                $timeout(function(){
                    $scope.alert.type = '';
                }, 5000);
                
                $scope.loading.status = false;
            }
        );
    }

    

});