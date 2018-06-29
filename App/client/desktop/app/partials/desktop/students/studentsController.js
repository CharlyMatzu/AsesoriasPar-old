angular.module("Desktop").controller('StudentsController', function($scope, Notification, StudentsService, ScheduleService, STATUS){


    $scope.page.title = 'Escritorio > Alumnos';
    $scope.loading = true;
    $scope.advisories = [];
    $scope.showRequireSchedule = false;
    $scope.showRequireSubjects = false;



    var getAdvisories = function(){
        $scope.loading = true;

        StudentsService.getAdviserAdvisories( $scope.student.id )
            .then(
                function(success){
                    $scope.advisories = success.data;
                },
                function(error){
                    Notification.error(error.data);
                }
            )
            .finally(function(){
                $scope.loading = false;
            });
    };
    

    var getSubjects = function(){
        StudentsService.getSubjects()
            .then(
                function(success){
                    $scope.subjects = success.data;
                },
                function(error){
                    Notification.error("Error: "+error.data);
                }
            );
    };

    $scope.finalize = function(advisory_id){
        Notification("Procesando...");

        StudentsService.finalizeAdvisory(advisory_id)
            .then(
                function(success){
                    Notification.success("asesoría finalizada con éxito");
                    getAdvisories();
                },
                function(error){
                    Notification.error("Ocurrió un error");
                }
            );
    };


    var checkSchedule = function(){

        //Si ya tiene un horario
        if( $scope.schedule != null ){
            //Si tiene horas disponibles
            if( $scope.schedule.days_hours.length > 0 &&
                $scope.schedule.subjects.length > 0)
                getAdvisories();
            else{
                $scope.showRequireSchedule = true;
                $scope.showRequireSubjects = true;
                $scope.loading = false;
            }
        }
        else{
            //Si no tiene horario, se hace petición a la BD
            ScheduleService.getStudentSchedule( $scope.student.id )
                .then(
                    function(success){
                        //
                        if( success.status == STATUS.NO_CONTENT ){
                            $scope.showRequireSchedule = true;
                            $scope.showRequireSubjects = true;
                            $scope.loading = false;
                        }
                        else{
                            $scope.schedule = success.data;

                            var load = true;

                            //Si tiene horas disponibles
                            if( $scope.schedule.days_hours.length == 0){
                                load = false;
                                $scope.showRequireSchedule = true;
                                $scope.loading = false;
                            }

                            //Si tiene materias disponibles
                            if( $scope.schedule.subjects.length == 0){
                                load = false;
                                $scope.showRequireSubjects = true;
                                $scope.loading = false;
                            }
                                

                            //Si hay ambas
                            if( load )
                                getAdvisories();
                        }
                },
                function(error){
                    Notification.error("Error: "+error.data);
                    $scope.loading = false;
                });
            }
    };


    //Si se ejecuta, se considera un periodo como existente (desktopController lo determina)
    (function(){
        checkSchedule();
    })();

});