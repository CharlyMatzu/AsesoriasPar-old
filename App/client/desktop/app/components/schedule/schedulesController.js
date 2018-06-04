app.controller('ScheduleController', function($scope, $http, Notification, ScheduleService){

    $scope.daysAndHours = [];
    $scope.status = {
        status: false,
        message: ""
    }

    $scope.toggleHour = function(event){
        //Verifica si el padre tiene "selectable"
        if( $( event.currentTarget ).parents().hasClass('selectable') ){
            //verifica si es de tipo hora
            if( $(event.currentTarget ).hasClass('cell-hour') ){
                //agrega/quita clase
                $( event.currentTarget ).toggleClass('active');
            }
        }
    }

    var getDaysAndHours = function(){
        $scope.status.message = "Cargando horas";


        ScheduleService.getDaysAndHours(
            function(success){
                Notification.success("Obtenido horas");
                $scope.daysAndHours = success.data;
                $scope.status.message = "";
            },
            function(error){
                Notification.error("Error");
                $scope.status.message = "Ocurrio un error";
            }
        );
    }

    var getCurrentPeriod = function(){
        ScheduleService.getCurrentPeriod(
            function(success){
                Notification.success("Obtenido periodo");
                $scope.daysAndHours = success.data;
            },
            function(error){
                Notification.error("Error");
            }
        );
    }

    var getStudentSchedule = function(){
        ScheduleService.getStudentSchedule(studen_id,
            function(success){
                Notification.success("Obtenido horario");
                $scope.daysAndHours = success.data;
            },
            function(error){
                Notification.error("Error");
            }
        );
    }

    getDaysAndHours();

});