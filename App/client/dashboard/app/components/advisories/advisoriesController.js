angular.module("Dashboard").controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService, RequestFactory){
    
    $scope.page.title = "Asesorias";
    $scope.advisories = [];

    $scope.showAssign = false;
    $scope.showAdvisers = false;
    $scope.showSchedule = false;

    $scope.advisers = [];

    $scope.selectedAdviser = {};
    $scope.selectedAlumn = {};
    $scope.selectedAdvisory = {};

    // $scope.adviserSchedule = [];
    // $scope.mySchedule = [];
    $scope.matchHours = [];
    $scope.daysAndHours = [];


    $scope.getAdvisories = function(){
        $scope.showAssign = false;
        $scope.showAdvisers = false;
        $scope.showSchedule = false;

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
        if( $( event.currentTarget ).parents().hasClass('selectable-actives') ){
            //verifica si es de tipo hora
            if( $(event.currentTarget ).hasClass('cell-hour') ){
                //Que sea un elemento activo
                if( $(event.currentTarget ).hasClass('active') ){
                    //agrega/quita clase
                    $( event.currentTarget ).toggleClass('active-selected');
                }
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


    var registerAdvisoryHours = function(hours, advisory_id, adviser_id){
        AdvisoriesService.assignAdviser(advisory_id, hours, adviser_id,
            function(success){
                Notification.success("Asignado con exito");
                //Recarga asesorias
                $scope.getAdvisories();
            },
            function(error){
                Notification.error("Error al asignar: "+error.data);
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
        $scope.selectedAdvisory = advisory;
        //Se obtiene asesores
        getSubjectAdvisers(advisory);
    };

    $scope.backToAdvisers = function(){
        $scope.showAdvisers = true;
        $scope.showSchedule = false;
    };

    $scope.closeAssign = function(){
        $scope.showSchedule = false;
        $scope.showAdvisers = false;
        $scope.showAssign = false;
    };

    $scope.saveAssign = function(){
        selectedHours = [];
        //Obtiene cada elemento seleccionado del tipo "horario"
        $('.active-selected').each(function(){
            selectedHours.push( $(this).data('hour-day-id') );
        });

        //Obtiene cada elemento del horario del asesor
        hours = [];
        for( var i=0; i < selectedHours.length; i++ ){
            for( var j=0; j < $scope.matchHours.length; j++ ){
                if( selectedHours[i] == $scope.matchHours[j]['day_hour_id'] )
                    hours.push( $scope.matchHours[j]['id'] )
            }    
        }
        
        
        if( hours.length == 0 )
            Notification.warning("No ha seleccionado ninguna hora");
        else
            registerAdvisoryHours( hours, $scope.selectedAdvisory.id, $scope.selectedAdviser.id );
        
    };



    (function(){
        $scope.getAdvisories();
    })();
    

});