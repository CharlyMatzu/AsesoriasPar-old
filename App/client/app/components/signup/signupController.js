app.controller('SignupController', function($scope, $window, $timeout, Notification, SignupService){
    
    $scope.careers = [];

    $scope.loadCareers = function(){
        Notification.primary("Obteniendo carreras");

        SignupService.getCareers(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification("No se encontraron carreras disponibles");
                    $scope.alert.type = 'warning';
                    $scope.alert.message = "No se encontraron carreras disponibles";
                }   
                else{
                    Notification("Carreras cargadas");
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

        $scope.loading.status = true;
        $scope.loading.message = "Registrando usuario";

        SignupService.signup(student,
            function(success){
                $scope.alert.type = 'success';
                $scope.alert.message = "Registrado con exito, redireccionando";
                $scope.loading.status = true;

                $timeout(function(){
                    $window.location.href = '#!/signin';
                }, 2000);
            },
            function(error){
                $scope.alert.type = 'warning';
                $scope.alert.message = error.data;
                
                $scope.loading.status = false;
            }
        );
    }

});