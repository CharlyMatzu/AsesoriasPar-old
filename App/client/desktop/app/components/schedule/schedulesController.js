app.controller('ScheduleController', function($scope, $http, Notification, ScheduleService){

    $scope.daysAndHours = [];
    $scope.schedule = {};
    $scope.period = {};
    var count = 0;
    $scope.loading = {
        status: false,
        message: ""
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
                    getStudentSchedule(3);
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
     * Obtiene las materias del horario
     */
    var getSubjects = function(schedule_id){
        $scope.loading.message = "Creando horario";

        ScheduleService.getSubjects(schedule_id,
            function(success){
                
            },
            function(error){
                Notification.error("Error al obtener materias");
                $scope.loading.status = false;
            }
        );
    }

    /**
     * Obtiene horario de estudiante
     */
    var getStudentSchedule = function(studen_id){
        $scope.loading.message = "Obteniendo horario de alumno";

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

    loadData();

});