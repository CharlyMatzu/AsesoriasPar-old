angular.module("LoginApp").controller('SigninController', function($scope, $window, $timeout, Notification, SigninService, AuthFactory, STATUS){

    $scope.alert.type = '';
    $scope.loading.status = false;
    $scope.loading.message = "";


    $scope.signin = function(user){
        $scope.loading.status = true;
        $scope.loading.message = "Iniciando sesion";

        SigninService.signin(user,
            function(success){
                $scope.alert.type = 'success';
                $scope.alert.message = "Autenticado correctamente, redireccionando";
                
                $scope.loading.status = false;
                //Se almacena info de usuario
                AuthFactory.setUser( success.data );

                $timeout(function(){
                    $scope.redirect();
                },1000);
            },
            function(error){
                if( error.status === STATUS.UNAUTHORIZED ){
                    $scope.alert.type = 'warning';
                    $scope.alert.message = "Correo o contraseña incorrectas";
                }
                else if( error.status === STATUS.CONFLICT ){
                    $scope.alert.type = 'warning';
                    $scope.alert.message = error.data;
                }
                else{
                    $scope.alert.type = 'error';
                    $scope.alert.message = error.data;
                }

                $timeout(function(){
                    $scope.alert.type = '';
                }, 5000);
                
                $scope.loading.status = false;
            }
        );
    }

    

});