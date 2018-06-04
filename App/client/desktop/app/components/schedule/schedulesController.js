app.controller('ScheduleController', function($scope, $http, Notification, ScheduleService){

    $scope.daysAndHours = [];

    var getDaysAndHours = function(){
        ScheduleService.getDaysAndHours(
            function(success){
                Notification.success("Obtenido horas");
                $scope.daysAndHours = success.data;
                console.log( success.data );
            },
            function(error){
                Notification.error("Error");
                console.log( erro.data );
            }
        );
    }

    var getCurrentPeriod = function(){
        ScheduleService.getCurrentPeriod(
            function(success){
                Notification.success("Obtenido periodo");
                $scope.daysAndHours = success.data;
                console.log( success.data );
            },
            function(error){
                Notification.error("Error");
                console.log( erro.data );
            }
        );
    }

    var getStudentSchedule = function(){
        ScheduleService.getStudentSchedule(studen_id,
            function(success){
                Notification.success("Obtenido horario");
                $scope.daysAndHours = success.data;
                console.log( success.data );
            },
            function(error){
                Notification.error("Error");
                console.log( erro.data );
            }
        );
    }

    getDaysAndHours();

});