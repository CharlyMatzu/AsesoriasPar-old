angular.module("Desktop").controller('ScheduleController', function($scope, Notification, STATUS, ScheduleService){

    $scope.page.title = 'Escritorio > Horario';

    $scope.loading = true;
    
    $scope.daysAndHours = [];
    $scope.schedule = {};
    $scope.showUpdateSchedule = false;
    var backupSelectedItems = [];

    
    

    /**
     * Crear un horario
     * @param {} studen_id 
     */
    var createSchedule = function(studen_id){
    

        ScheduleService.createSchedule(studen_id)
            .then(function(success){
                //Se intenta obtener de nuevo
                getStudentSchedule(studen_id);
            },
            function(error){
                Notification.error("Error al crear horario");
                $scope.loading = false;
            });
    };


    /**
     * Obtiene dias y horas disponibles en el sistema
     */
    var getDaysAndHours = function(){

        ScheduleService.getDaysAndHours()
            .then(function(success){
                // Notification.success("Horario cargado con éxito");
                $scope.daysAndHours = success.data;
            },
            function(error){
                Notification.error("Error al iniciarlizar horario");
            })
            .finally(function(){
                $scope.loading = false;
            })
    };

  

    /**
     * Obtiene horario de estudiante
     */
    var getStudentSchedule = function(studen_id){
        $scope.showUpdateSchedule = false;
        $scope.loading = true;

        ScheduleService.getStudentSchedule(studen_id)
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    //Si no tiene un horario, se crea
                    createSchedule(studen_id);
                }
                else{
                    //Se asigna Información
                    $scope.schedule = success.data;
                    //Se manda a llamar la funcion de las horas disponibles
                    getDaysAndHours();
                }
                
                },
                function(error){
                    Notification.error("Error al obtener horario de alumno");
                })
                .finally(function(){
                    // $scope.loading = false;
                });
    };

    



    /**
     * Compara el id con el del horario para saber si es parte de su horario
     * @param {int} dh_id 
     * @returns String className
     */
    $scope.checkIsExist = function(dh_id){
        //Obtiene solo horario
        let dh = $scope.schedule['days_hours'];
        //Si hay horas

        //FIXME: solo debe llegar al limite una vez y terminar
        if( dh.length > 0 ){
            //Recorre el data
            for(let i=0; i < dh.length; i++){
                let data = dh[i]['hours'];
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
    };


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
    };


    $scope.initUpdateSchedule = function(){
        //Obtiene todos los elementos con .active para respaldarlos en caso de cancelar
        $('.schedule .active').each(function(){
            backupSelectedItems.push($(this)); 
        });

        //Se activa actualizador
        $scope.showUpdateSchedule = true;
    };


    $scope.cancelUpdate = function(){
        Notification("Cancelado");
        $scope.showUpdateSchedule = false;
        // $scope.loading = true;

        //TODO: cambiar por id de localstorage
        //TODO: usar servicio (factory) para obtener id y que este regrese al index si no hay
        //Recarga contenido
        // getStudentSchedule( $scope.student.id );


        //Le quita .active a todos los elementos
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
    };

    $scope.updateScheduleHours = function(schedule_id){
        $scope.loading = true;

        //Obtenemos todos los elementos con la clase .active
        //TODO:pedir confirmacion
        let selectedItems = [];
        $('.schedule .active').each(function(){
            let item = $(this).data('hour-day-id');
            selectedItems.push( item );
        });

        //Peticiones
        ScheduleService.updateScheduleHours(schedule_id, selectedItems)
            .then(function(success){
                Notification.success("Horario actualizado con éxito");
                //TODO: recargar datos
                getStudentSchedule( $scope.student.id );
            },
            function(error){
                Notification.error("Error al actualziar horario: "+error.data);
                $scope.loading = false;
            })
            .finally(function(){
                $scope.showUpdateSchedule = false;
            });
    };

    

    (function(){
        getStudentSchedule( $scope.student.id );
    })();

})