angular.module("LoginApp").controller('SignupController', function($scope, $window, $timeout, Notification, SignupService){
    
    $scope.careers = [];

    $scope.alert.type = '';
    $scope.loading.status = false;
    $scope.loading.message = "";
    

    $scope.loadCareers = function(){
        Notification.primary("Obteniendo carreras");

        SignupService.getCareers(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification.warning("No se encontraron carreras disponibles");
                    $scope.alert.type = 'warning';
                    $scope.alert.message = "No se encontraron carreras disponibles";
                }   
                else{
                    Notification.success("Carreras cargadas");
                    $scope.careers = success.data;
                    // $scope.alert.type = 'warning';
                    // $scope.alert.message = "No se encontraron carreras disponibles";
                }
            },
            function(error){
                Notification.error("Error al cargar carreras: "+error.data);
                $scope.alert.type = 'warning';
                $scope.alert.message = "Error al cargar carreras: "+error.data;
            }
        );
    }

    $scope.loadCareers();

    $scope.signup = function(student){

        // if( student.pass != status.pass2 ){
        //     Notification.warning("Contraseñas no coinciden");
        //     return;
        // }

        $scope.loading.status = true;
        $scope.loading.message = "Registrando usuario";

        SignupService.signup(student,
            function(success){
                // Notification.success("Registrado con exito!");
                $scope.alert.type = 'success';
                $scope.alert.message = "Registrado con exito,redireccionando";
                $scope.loading.status = false;
                
                $scope.student = {};

                $timeout(function(){
                    $scope.alert.type = '';
                    $window.location.href = '#!/signin';
                }, 2000);
            },
            function(error){
                Notification.error("Ocurrio un error: "+error.data);
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