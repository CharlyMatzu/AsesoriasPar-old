angular.module("Desktop")
    .controller('SubjestsController', function($scope, Notification, SubjestsService, ScheduleService, STATUS){


    $scope.page.title = 'Escritorio > Materias';
    $scope.showRequireSchedule = false;
    $scope.showUpdateSubjects = false;
    $scope.schedule = null;
    $scope.subjects = [];
    $scope.noRepeatedSubjects = [];
    $scope.loadingSubjects = false;
    
    
    

    //TODO: las materias que no son parte del horario deben solicitar a la API
    var setNoRepeatSubjects = function(data){
        var subs = $scope.schedule.subjects;
        $scope.subjects = [];

        //Recorre materias
        
        for( var i=0; i < data.length; i++ ){
            //Recorre materias
            var isSelected = false;
            for( var j=0; j < subs.length; j++ ){
                //Recorre materias individuales
                if( data[i]['id'] === subs[j]['subject_id'] ){
                    isSelected = true;
                    break;
                }
            }
            //Si no se encontró, entonces se agrega para seleccionar
            if( !isSelected ){
                //Se agrega materia que no esta en horario para mostrar
                $scope.subjects.push( data[i] );
            }
        }

        $scope.loadingSubjects = false;
    };


    //Obtiene materias para seleccionar
    var getSubjects = function(){
        $scope.loadingSubjects = true;
        
        SubjestsService.getSubjects()
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT ){
                    Notification.warning("No hay materias disponibles");
                    $scope.showUpdateSubjects = false;
                    $scope.loadingSubjects = false;
                }
                else{
                    // Notification.success("Materias cargadas");
                    // $scope.subjects = success.data;
                    //TODO: hacer que la API regresa dichas materias y no que se haga desde el cliente
                    setNoRepeatSubjects(success.data);
                }
                    
            }, 
            function(error){
                Notification.error("Error al cargar materias: "+error.data);
                $scope.showUpdateSubjects = false;
                $scope.loadingSubjects = false;
            });
    };
    
    
    $scope.openSubjectsUpdate = function(){
        $scope.showUpdateSubjects = true;
        // $scope.schedule.subjects = [];
        $scope.subjects = [];
        //Obtiene materias
        getSubjects();
    };

    $scope.closeSubjectsUpdate = function(){
        $scope.showUpdateSubjects = false;
        $scope.subjects = [];
    };


    var updateSubjects = function(schedule_id, subjects){
        Notification("Actualizando materias");

        SubjestsService.updateScheduleSubjects(schedule_id, subjects)
            .then(function(success){
                // Recarga materias
                // Notification.success("Actualizando con éxito");
                getStudentSchedule( $scope.student.id );
            },
            function(error){
                Notification.error("Error al actualizar materias: "+error.data);
            });
    };



    $scope.removeSubject = function(event){
        
        //TODO: pedir confirmacion
        if( $( event.currentTarget ).parents().hasClass('selectable') ){
            //Verifica que sea un elemento selecionado
            if( $( event.currentTarget ).parents().hasClass('selected-items') ){
                //Remueve elemento del array
                var index = $( event.currentTarget ).data('index');
                var scheduleSubs = $scope.schedule.subjects;
                scheduleSubs.splice(index, 1);
                
                var subjects = [];
                for( var i=0; i < scheduleSubs.length; i++ ){
                    subjects.push( scheduleSubs[i]['subject_id'] );
                }
                //Se recarga
                updateSubjects( $scope.schedule.id, subjects );
            }
        }
    };

    
    $scope.addSubject = function(event){
        
        if( $( event.currentTarget ).parents().hasClass('selectable') ){
            //Verifica que sea un elemento seleccionado
            if( $( event.currentTarget ).parents().hasClass('available') ){

                var subjects = [];
                //Obtiene materia seleccionada
                var sub = $(event.currentTarget).data('subject-id');
                subjects.push( sub );
                //Obtiene materias ya seleccionadas
                for( var i=0; i < $scope.schedule.subjects.length; i++ ){
                    subjects.push( $scope.schedule.subjects[i]['subject_id'] );
                }

                //Envía para actualizar
                updateSubjects( $scope.schedule.id, subjects );
            }
        }
    };


    /**
     * Obtiene horario de estudiante
     */
    var getStudentSchedule = function(studen_id){
        $scope.showUpdateSchedule = false;
        $scope.loading = true;

        ScheduleService.getStudentSchedule(studen_id)
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT ){
                    //Si no tiene un horario, se crea
                    return ScheduleService.createSchedule(studen_id)
                }
                else
                    $scope.schedule = success.data;
                
                },
                function(error){
                    Notification.error("Error al obtener horario de alumno");
            
            })
            //Petición de crear horario
            .then(function(success){
                return ScheduleService.getStudentSchedule(studen_id);
            }, function(error){
                Notification.error("Error al crear horario");
            })
            //Obtener horario nuevamente
            .then(function(success){
                $scope.schedule = success.data;
            }, function(error){
                Notification.error("Error al obtener horario");
            })
            .finally(function(){
                $scope.loading = false;
            });
    };


    (function(){
        getStudentSchedule( $scope.student.id );
    })();
    

});