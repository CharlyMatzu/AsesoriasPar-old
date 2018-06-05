app.controller('ScheduleController', function($scope, $http, Notification, ScheduleService){

    $scope.studentID = 3;
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

        //Recorre materias de horario
        for( let i=0; i < subs.length; i++ ){
            for( let j=0; j < data.length; j++ ){
                //Recorre materias individuales
                if( subs[i]['subject_id'] != data[j]['id'] ){
                    //Se agrega materia que no esta en horario para mostrar
                    $scope.subjects.push( data[j] );
                }
            }
        }
    }


    $scope.getSubjects = function(){
        ScheduleService.getSubjects(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification.warning("No hay materias");
                    $scope.showUpdateSubjects = false;
                }
                else{
                    Notification("Materias cargadas");
                    setNoRepeatSubjects(success.data);
                }
                    
            }, 
            function(b){
                Notification.error("Ggsd");
            }
        );
    }
    
    $scope.openSubjectsUpdate = function(){
        $scope.showUpdateSubjects = true;
        //Obtiene materias
        $scope.getSubjects();
    }


    $scope.removeSubject = function($event){
        
        if( $( event.currentTarget ).parents().hasClass('selectable') ){
            //Verifica que sea un elemento selecionado
            if( $( event.currentTarget ).parents().hasClass('selected-items') ){
                //Remueve elemento del array
                let index = $( event.currentTarget ).data('index');
                $scope.schedule.subjects.splice( index );
                //Recarga materias
                $scope.getSubjects();
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
                    getStudentSchedule( $scope.studentID );
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

    // /**
    //  * Obtiene las materias del horario
    //  */
    // var getSubjects = function(schedule_id){
    //     $scope.loading.message = "Creando horario";

    //     ScheduleService.getSubjects(schedule_id,
    //         function(success){
                
    //         },
    //         function(error){
    //             Notification.error("Error al obtener materias");
    //             $scope.loading.status = false;
    //         }
    //     );
    // }

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
                Notification.success("Horario cargado con exito");
                $scope.daysAndHours = success.data;
                $scope.loading.message = "";
                $scope.loading.status = false;
            },
            function(error){
                Notification.error("Error al iniciarlizar horario");
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
        // getStudentSchedule( $scope.studentID );


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
        let selectedItems = [];
        $('.schedule .active').each(function(){
            let item = $(this).data('hour-day-id');
            selectedItems.push( item );
        });

        //Peticiones
        ScheduleService.updateScheduleHours(schedule_id, selectedItems,
            function(success){
                Notification.success("Horario actualizado con exito");
                $scope.showUpdateHours = false;
                // $scope.loading.status = false;

                //TODO: recargar datos
                // getStudentSchedule( $scope.studentID );
            },
            function(error){
                Notification.error("Error al actualziar horario: "+error.data);
                $scope.loading.status = false;
            }
        );
    }

    $scope.updateScheduleSubjects = function(){
        //obtenemos todos los elementos activos
        
    }



});