app.controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService){

    $scope.requestedAds = [];
    $scope.adviserAds = [];
    $scope.showAdvisories = false;
    $scope.showNewAdvisory = false;

    //Request advisory
    $scope.subject = {};

    $scope.requestedAds = function(){
        AdvisoriesService.getRequestedAdvisories( $scope.student.id,
            function(success){
                $scope.requestedAds = success.data;
            },
            function(error){
                Notification.error("Ocurrio un error: "+error.message);
            }
        );
    };

    $scope.adviserAds = function(){
        AdvisoriesService.getAdviserAdvisories( $scope.student.id,
            function(success){
                $scope.adviserAds = success.data;
            },
            function(error){
                Notification.error("Ocurrio un error: "+error.message);
            }
        );
    };
    


    (function(){
        
        AdvisoriesService.getCurrentPeriod(
            function(success){
                if( success.status == NO_CONTENT ){
                    $scope.loading.status = false;
                    $scope.period.message = "No hay un periodo actual disponible";
                }
                else{
                    $scope.period.data = success.data;
                    $scope.loading.status = false;
                    $scope.showAdvisories = true;

                    $scope.requestedAds();
                }
            },
            function(error){
                $scope.loading.status = false;
            }
        );
        
        
    })();


});