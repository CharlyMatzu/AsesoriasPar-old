app.controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService){
    $scope.page.title = "Asesorias";
    $scope.advisories = [];

    $scope.advisers = [];
    $scope.adviserSchedule = [];
    $scope.mySchedule = [];


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

    var getSubjectAdvisers = function(subject_id, student_id){
        AdvisoriesService.getSubjectAdvisers_Ignore(
            function(success){
                if( success.status == NO_CONTENT )
                    Notification("No hay asesores disponibles");
                else
                $scope.advisers = success.data;
            },
            function(error){
                Notification.error("Error al cargar asesores: "+error.data);
            }
        );
    }

    var getAdviserSchedule = function(){}

    var getMySchedule = function(){}

    var checkScheduleMatch = function(){}

    $scope.getSchedules = function(adviser_id){
        
    }



    (function(){
        $scope.getAdvisories();
    })();
    

});