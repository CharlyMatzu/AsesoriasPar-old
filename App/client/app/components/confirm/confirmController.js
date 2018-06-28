app.controller('ConfirmController', function($scope, $window, $timeout, Notification, ConfirmService){

    $scope.status = "Procesando...";

    $scope.confirm = function(user, $routeParams){

        ConfirmService.confirm($routeParams.token,
            function(success){
                Notification.success("Confirmado con éxito");
                $scope.status = "Confirmado";

                // $timeout(function(){
                //     $window.location.href = "";
                // }, 2000);
            },
            function(error){
                Notification.error("No se pudo confirmar correo");
                $scope.status = "Error: "+error.data;
            }
        );
        
    }

    

});