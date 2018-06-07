app.controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService){
    $scope.page.title = "Asesorias";
    $scope.advisories = [];

    $scope.getAdvisories = function(){
        AdvisoriesService.getAdvisories(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification("No hay asesorias registradas en el periodo actual");
                }
                else{
                    $scope.advisories = success.data;
                }
            },
            function(error){
                Notification.error("Error al cargar asesorias: "+error.data);
            }
        );
    };

    (function(){
        $scope.getAdvisories();
    })();
    

});