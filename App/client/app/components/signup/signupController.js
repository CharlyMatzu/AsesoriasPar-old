angular.module("LoginApp").controller('SignupController', function($scope, $window, $timeout, Notification, SignupService){
    
    $scope.careers = [];
    $scope.alert.type = '';
    $scope.loading = false;
    

    $scope.loadCareers = function(){
        
        $scope.loading = true;

        SignupService.getCareers()
            .then(function(success){
                if( success.status == NO_CONTENT ){
                    alert("No hay carreras disponibles por lo que no será posible continuar con el registro");
                    // $scope.alert.type = 'warning';
                    // $scope.alert.message = "No se encontraron carreras disponibles";
                }   
                else
                    $scope.careers = success.data;

                $scope.loading = false;
            },
            function(error){
                // Notification.error("Error al cargar carreras: "+error.data);
                $scope.alert.type = 'danger';
                $scope.alert.message = "Error al cargar carreras";
                $scope.loading = false;
            });
    };

    $scope.signup = function(student){

        if( student.pass !== student.pass2 ){
            Notification.warning("Contraseñas no coinciden");
            return;
        }

        if( !student.facebook )
            student.facebook = "";

        if( !student.phone )
            student.phone = "";

        $scope.loading = true;

        SignupService.signup(student)
            .then(function(success){
                $scope.alert.type = 'success';
                $scope.alert.message = "Registrado con exito,redireccionando";
                $scope.loading = false;
                
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
                
                $scope.loading = false;
            }
        );
    };

    $scope.loadCareers();

});