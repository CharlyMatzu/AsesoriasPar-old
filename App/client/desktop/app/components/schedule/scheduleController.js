app.controller('ScheduleController', function($scope, $http, Notification, ScheduleService){

    // $scope.student.id
    $scope.daysAndHours = [];
    $scope.schedule = {};
    $scope.showUpdateHours = false;
    $scope.showUpdateSubjects = false;
    $scope.subjects = [];
    $scope.period = {};
    
    $scope.loading = {
        status: false,
        message: ""
    }
    var count = 0;
    var backupSelectedItems = [];

    

    var setNoRepeatSubjects = function(data){
        let subs = $scope.schedule.subjects;
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
            //Si no se encontro, entonces se agrega para seleccionar
            if( !isSelected ){
                //Se agrega materia que no esta en horario para mostrar
                $scope.subjects.push( data[i] );
            }
        }
    }


    var getSubjects = function(){
        ScheduleService.getSubjects(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification.warning("No hay materias");
                    $scope.showUpdateSubjects = false;
                }
                else{
                    // Notification.success("Materias cargadas");
                    setNoRepeatSubjects(success.data);
                }
                    
            }, 
            function(error){
                Notification.error("Error al cargar materias: "+error.data);
            }
        );
    }
    
    $scope.openSubjectsUpdate = function(){
        $scope.showUpdateSubjects = true;
        //Obtiene materias
        getSubjects();
    }


    var updateSubjects = function(schedule_id, subjects){
        Notification("Actualizando materias");

        ScheduleService.updateScheduleSubjects(schedule_id, subjects,
            function(success){
                // Recarga materias
                // Notification.success("Actualizando con exito");
                getStudentSchedule( $scope.student.id );
            },
            function(error){
                Notification.error("Error al actualizar materias: "+error.data);
            }
        );
    }

    $scope.removeSubject = function(event){
        
        //TODO: pedir confirmacion
        if( $( event.currentTarget ).parents().hasClass('selectable') ){
            //Verifica que sea un elemento selecionado
            if( $( event.currentTarget ).parents().hasClass('selected-items') ){
                //Remueve elemento del array
                let index = $( event.currentTarget ).data('index');
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
    }

    
    $scope.addSubject = function(event){
        
        if( $( event.currentTarget ).parents().hasClass('selectable') ){
            //Verifica que sea un elemento selecionado
            if( $( event.currentTarget ).parents().hasClass('available') ){

                var subjects = [];
                //Obtiene materia seleccionada
                var sub = $(event.currentTarget).data('subject-id');
                subjects.push( sub );
                //Obtiene materias ya seleccionadas
                for( var i=0; i < $scope.schedule.subjects.length; i++ ){
                    subjects.push( $scope.schedule.subjects[i]['subject_id'] );
                }

                //Envia para actualizar
                updateSubjects( $scope.schedule.id, subjects );
            }
        }
    }
    


    /**
     * Obtiene periodo actual
     */
    var loadData = function(){
        Notification("Cargando elementos");
        $scope.loading.message = "Cargando periodo";
        $scope.loading.status = true;

        //Se obtiene horario actual
        ScheduleService.getCurrentPeriod(
            function(success){
                if( success.status == NO_CONTENT ){
                    $scope.period = "No hay un periodo actual";
                    $scope.loading.message = "No hay un periodo actual";
                    $scope.loading.status = false;
                }
                else{
                    //Se asigna informacion
                    $scope.period = success.data;
                    //Se manda a llamar la siguiente funcion
                    //TODO: cambiar por id de localstorage
                    //TODO: usar servicio (factory) para obtener id y que este regrese al index si no hay
                    getStudentSchedule( $scope.student.id );
                }
                
            },
            function(error){
                Notification.error("No se pudo obtener periodo actual");
                $scope.loading.status = false;
            }
        );
    }

    /**
     * Crear un horario
     * @param {} studen_id 
     */
    var createSchedule = function(studen_id){
        $scope.loading.message = "Creando horario";

        ScheduleService.createSchedule(studen_id,
            function(success){
                //Se intenta obtener de nuevo
                getStudentSchedule(studen_id);
            },
            function(error){
                Notification.error("Error al crear horario");
                $scope.loading.status = false;
            }
        );
    }

  

    /**
     * Obtiene horario de estudiante
     */
    var getStudentSchedule = function(studen_id){
        $scope.loading.message = "Obteniendo horario de alumno";
        $scope.showUpdateHours = false;

        ScheduleService.getStudentSchedule(studen_id,
            function(success){
                if( success.status == NO_CONTENT ){
                    //Si no tiene un horario, se crea
                    createSchedule(studen_id);
                }
                else{
                    //Se asigna informacion
                    $scope.schedule = success.data;
                    //Se manda a llamar la funcion de las horas disponibles
                    getDaysAndHours();
                    //Se recargan materias disponibles
                    getSubjects();
                }
                
            },
            function(error){
                Notification.error("Error al obtener horario de alumno");
                $scope.loading.status = false;
            }
        );
    }

    /**
     * Obtiene dias y horas disponibles en el sistema
     */
    var getDaysAndHours = function(){
        $scope.loading.message = "Cargando horas";

        ScheduleService.getDaysAndHours(
            function(success){
                // Notification.success("Horario cargado con exito");
                $scope.daysAndHours = success.data;
                $scope.loading.message = "";
                $scope.loading.status = false;
            },
            function(error){
                Notification.error("Error al inicializar horario");
                $scope.loading.status = false;
                $scope.loading.message = "Ocurrio un error";
                $scope.loading.status = false;
            }
        );
    }



    /**
     * Compara el id con el del horario para saber si es parte de su horario
     * @param {int} dh_id 
     * @returns String className
     */
    $scope.checkIsExist = function(dh_id){
        //Obtiene solo horario
        let dh = $scope.schedule['days_hours'];
        //Si hay horas
        
        // if( count >= 55* )
        //     return;
        // else
        //     count++;

        //FIXME: solo debe llegar al limite una vez y terminar
        if( dh.length > 0 ){
            //Recorre el data
            for(let i=0; i < dh.length; i++){
                let data = dh[i]['data'];
                //Recorreo cada dia
                for(let j=0; j < data.length; j++){
                    let day_hour = data[j]['day_hour_id'];
                    // console.log(" -- ID: "+dh_id+ " -- Schedule:"+ day_hour );
                    if( day_hour === dh_id ){
                        // console.log( "Coincide" );
                        return 'active';
                    }   
                }
            }
        }
    }


    /**
     * Elemento seleccionado que permite ser "activado" si un elemento padre tiene su clase "selectable"
     * el cual agrega/quita al item la clase 'active'
     * @param {EventTarget} event 
     */
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

    //Iniciar la carga de datos
    loadData();


    $scope.initUpdateHours = function(){
        //Obtiene todos los elementos con .active
        $('.schedule .active').each(function(){
            backupSelectedItems.push($(this));
            
        });
        //Se activa actualizador
        $scope.showUpdateHours = true; 
    }


    $scope.cancelUpdate = function(){
        Notification("Cancelado");
        $scope.showUpdateHours = false;
        // $scope.loading.status = true;

        //TODO: cambiar por id de localstorage
        //TODO: usar servicio (factory) para obtener id y que este regrese al index si no hay
        //Recarga contenido
        // getStudentSchedule( $scope.student.id );


        //Le quita eleentos a todos
        $('.schedule .active').each(function(){
            $(this).removeClass('active');
        });
        //Pone elementos seleccionados como estaban
        if( backupSelectedItems.length > 0 ){
            for( var i=0; i < backupSelectedItems.length; i++ ){
                backupSelectedItems[i].addClass('active');
            }
            //Limpia array
            backupSelectedItems = [];
        }
    }

    $scope.updateScheduleHours = function(schedule_id){
        // $scope.loading.status = true;

        //Obtenemos todos los elementos con la clase .active
        //TODO:pedir confirmacion
        let selectedItems = [];
        $('.schedule .active').each(function(){
            let item = $(this).data('hour-day-id');
            selectedItems.push( item );
        });

        //Peticiones
        ScheduleService.updateScheduleHours(schedule_id, selectedItems,
            function(success){
                // Notification.success("Horario actualizado con exito");
                $scope.showUpdateHours = false;
                // $scope.loading.status = false;

                //TODO: recargar datos
                // getStudentSchedule( $scope.student.id );
            },
            function(error){
                Notification.error("Error al actualizar horario: "+error.data);
                $scope.loading.status = false;
            }
        );
    }

    $scope.updateScheduleSubjects = function(){
        //obtenemos todos los elementos activos
        
    }



});