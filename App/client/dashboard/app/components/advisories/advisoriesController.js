angular.module("Dashboard").controller('AdvisoriesController', function($scope, Notification, AdvisoriesService, PlansService, STATUS, $window){
    
    $scope.page.title = "Asesorias";
    $scope.advisories = [];

    $scope.loading = false;
    $scope.loadingAdvisers = false;
    $scope.loadingSchedule = false;

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

        $scope.loading = true;

        AdvisoriesService.getAdvisories()
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    Notification("No hay asesorias registradas en el periodo actual");
                }
                else
                    $scope.advisories = success.data;
            },
            function(error){
                Notification.error("Error al cargar asesorias: "+error.data);
            })
            .finally(function(){
                $scope.loading = false;
            });
    };

    var getSubjectAdvisers = function(advisory){
        var subject_id = advisory.subject_id;
        var student_id = advisory.alumn_id;

        $scope.loadingAdvisers = true;

        AdvisoriesService.getSubjectAdvisers_Ignore(subject_id, student_id)
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT )
                    Notification("No hay asesores disponibles");
                else
                    $scope.advisers = success.data;

                $scope.loadingAdvisers = false;
            },
            function(error){
                Notification.error("Error al cargar asesores: "+error.data);
                $scope.loadingAdvisers = false;
            }
        );
    };


    var checkScheduleMatch = function(adviser_id, alumn_id){
        AdvisoriesService.getMatchHours(adviser_id, alumn_id)
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT )
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
        AdvisoriesService.getDaysAndHours()
            .then(function(success){
                Notification.success("Horas cargadas");
                $scope.daysAndHours = success.data;
            },
            function(error){
                Notification.error("Error al cargar horas: "+error.data);
            }
        );
    };


    var registerAdvisoryHours = function(hours, advisory_id, adviser_id){
        AdvisoriesService.assignAdviser(advisory_id, hours, adviser_id)
            .then(function(success){
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
        $scope.advisers = [];
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
        $scope.loading = true;

        //verifica si hay un periodo actual
        PlansService.getCurrentPlan()
            .then(function(success){
                
                if( success.status == STATUS.NO_CONTENT ){
                    alert("No hay un periodo actual activo");
                    $window.location = "#!/planes";
                }
                else
                    $scope.getAdvisories();

            }, function(error){
                $scope.loading = false;
                Notification.error("Ocurrio un error verificar un periodo actual activo");
            })
        
    })();
    

});