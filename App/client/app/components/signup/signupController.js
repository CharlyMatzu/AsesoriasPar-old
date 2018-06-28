app.controller('SignupController', function($scope, $window, $timeout, Notification, SignupService){
    
    $scope.careers = [];
    // $scope.student = {
    //     email: "carlosrozuma@gmail.com",
    //     pass: "123",
    //     pass2: "123",
    //     first_name: "Carlos",
    //     last_name: "Zuñiga",
    //     career: 1,
    //     itson_id: "00000162156",
    //     phone: "6448980949"
    // }

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
                Notification.success("Bien!");
                $scope.alert.type = 'success';
                $scope.alert.message = "Registrado con éxito, redireccionando";
                $scope.loading.status = false;
                
                $scope.student = {};

                $timeout(function(){
                    $scope.alert.type = '';
                    $window.location.href = '#!/signin';
                }, 2000);
            },
            function(error){
                Notification.error("Ocurrió un error: "+error.data);
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