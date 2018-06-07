app.controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService){

    $scope.requestedAds = [];
    $scope.myAds = [];
    

    (function(){
        
        AdvisoriesService.getCurrentPeriod(
            function(success){
                if( success.status == NO_CONTENT ){
                    $scope.loading.status = false;
                    $scope.period.message = "No hay un periodo actual disponible";
                    console.log("Periodo no encontrado");
                }
                else{
                    $scope.period.data = success.data;
                    $scope.loading.status = false;
                    // getStudentSchedule( $scope.student.id );
                }
            },
            function(error){
                $scope.loading.status = false;
            }
        );
        
        
    })();

});