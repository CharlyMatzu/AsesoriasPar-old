app.controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService){
    
    $scope.page.title = "Asesorias";
    $scope.advisories = [];

    $scope.showAssign = false;
    $scope.showAdvisers = false;
    $scope.showSchedule = false;

    $scope.advisers = [];

    $scope.selectedAdviser = {};
    $scope.selectedAlumn = {};

    // $scope.adviserSchedule = [];
    // $scope.mySchedule = [];
    $scope.matchHours = [];
    $scope.daysAndHours = [];


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

    var getSubjectAdvisers = function(advisory){
        var subject_id = advisory.subject_id;
        var student_id = advisory.alumn_id;

        AdvisoriesService.getSubjectAdvisers_Ignore(subject_id, student_id,
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
    };


    var checkScheduleMatch = function(adviser_id, alumn_id){
        AdvisoriesService.getMatchHours(adviser_id, alumn_id,
            function(success){
                if( success.status == NO_CONTENT )
                    $scope.matchHours = null;
                else
                    $scope.matchHours = success.data;

                getDaysAndHours();
            },
            function(error){
                Notification.error("Error al cargar horario coincidente: "+error.data);
            }
        );
    };

    $scope.toggleHour = function(event){
        
        //Verifica si el padre tiene "selectable"
        if( $( event.currentTarget ).parents().hasClass('selectable') ){
            //verifica si es de tipo hora
            if( $(event.currentTarget ).hasClass('cell-hour') ){
                //agrega/quita clase
                $( event.currentTarget ).toggleClass('active');
            }
        }
    };

    var getDaysAndHours = function(){
        AdvisoriesService.getDaysAndHours(
            function(success){
                Notification.success("Horas cargadas");
                $scope.daysAndHours = success.data;
            },
            function(error){
                Notification.error("Error al cargar horas: "+error.data);
            }
        );
    };

    $scope.checkIsExist = function(hour_id){
        for(var i=0; i < $scope.matchHours.length; i++){
            if( hour_id == $scope.matchHours[i]['day_hour_id'] )
                return 'active';
        }
    };

    $scope.getMatchSchedule = function(adviser){
        checkScheduleMatch( adviser.id, $scope.selectedAlumn );
        $scope.selectedAdviser = adviser;
        $scope.showSchedule = true;
        $scope.showAdvisers = false;
    };

    
    $scope.openAssign = function(advisory){
        $scope.showAssign = true;
        $scope.showAdvisers = true;
        //Se obtiene alumno que solicito asesoria
        $scope.selectedAlumn = advisory.alumn_id;
        //Se obtiene asesores
        getSubjectAdvisers(advisory);
    };

    $scope.backToAdvisers = function(){
        $scope.showAdvisers = true;
        $scope.showSchedule = false;
    };

    // $scope.openAdviserSchedule = function(adviser, alumn){
    //     // Notification("Funciona: "+adviser.id);
    //     $scope.selectedAdviser = adviser;
    //     //Se obtiene horario del asesor y del alumno
        
    //     //$scope.showSchedule = true;
    // };



    (function(){
        $scope.getAdvisories();
    })();
    

});