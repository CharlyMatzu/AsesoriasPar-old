app.controller('StudentDetailController', function($scope, $http, $window, Notification, StudentDetailService, $routeParams){
    $scope.page.title = "Estudiante";
    
    $scope.student = [];
    $scope.schedule = {};
    $scope.daysAndHours = [];


    var getDaysAndHours = function(){
        $scope.loading.message = "Cargando horas";

        StudentDetailService.getDaysAndHours(
            function(success){
                Notification.success("Horario cargado con exito");
                $scope.daysAndHours = success.data;
            },
            function(error){
                Notification.error("Error al iniciarlizar horario");
            }
        );
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
    };


    $scope.getStudentSchedule = function(student_id){

        StudentDetailService.getStudentSchedule(student_id,
            function(success){
                if( success.status == NO_CONTENT ){
                    //Si no tiene un horario, se crea
                    // createSchedule(studen_id);
                    // TODO: mostrar mensaje de horario no creado
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

    
    $scope.getStudent = function(){
        //Se obtiene id de ruta
        var id = $routeParams.id;
        
        //Si esta vacio o no es númerico, regresa a estudiantes
        if( id == null || !Number.parseInt(id) ){
            $window.location.href = "#!/estudiantes";
        }
        else{
            $scope.loading.status = true;
            $scope.loading.message = "Obteniendo Datos de usuario";

            StudentDetailService.getStudent(id,
                function(success){
                    if( success.status == NO_CONTENT ){
                        Notification("No existe estudiante");
                        $window.location.href = "#!/estudiantes";
                    }
                    else{
                        //Se asignar informacion
                        $scope.student = success.data;

                        //Se obtiene horario
                        $scope.getStudentSchedule( id );

                    }
                    //Enabling refresh button
                    $scope.loading.status = false;
                        
                },
                function( error ){
                    Notification.error("Error al obtener estudiante");
                }
            );
        }
    }


    // /**
    //  * 
    //  * @param {int} user_id ID del Estudiante
    //  */
    // $scope.enableStudent = function(user_id){
    //     //Deshabilita botones
    //     $scope.disableButtons(true, '.opt-student-'+user_id);

    //     Notification('Procesando...');
    //     StudentDetailService.changeStatus(user_id, ENABLED, 
    //         function(success){
    //             Notification.success("Habilitado con exito");
    //             //TODO: debe actualizarse solo dicha fila de la tabla
    //             $scope.getStudent();
    //         },
    //         function(error){
    //             Notification.error("Error al Habilitar Estudiante");
    //             //Habilita botones
    //             $scope.disableButtons(false, '.opt-student-'+user_id);
    //         }
    //     );
    // }


    // /**
    //  * 
    //  * @param {int} user_id ID del Estudiante
    //  */
    // $scope.deleteStudent = function(user_id){
    //     //Deshabilita botones
    //     $scope.disableButtons(true, '.opt-student-'+user_id);

    //     StudentDetailService.deleteStudent(user_id,
    //         function(success){
    //             Notification.success("Estudiante eliminado con exito");
    //             $scope.getStudent();
    //         },
    //         function(error){
    //             Notification.error("Error al eliminar Estudiante");
    //             //Habilita botones
    //             $scope.disableButtons(false, '.opt-student-'+user_id);
    //         }
    //     );
    // }


    // /**
    //  * 
    //  * @param {int} user_id ID del Estudiante
    //  */
    // $scope.disableStudent = function(user_id){
    //     //Deshabilita botones
    //     $scope.disableButtons(true, '.opt-student-'+user_id);

    //     Notification('Procesando...');
    //     StudentDetailService.changeStatus(user_id, DISABLED, 
    //         function(success){
    //             Notification.success("Deshabilitado con exito");
    //             $scope.getStudent();
    //         },
    //         function(error){
    //             Notification.error("Error al deshabilitar Estudiante");
    //             //Habilita botones
    //             $scope.disableButtons(false, '.opt-student-'+user_id);
    //         }
    //     );
    // }


    //Se carguen datos al iniciar pagina
    $scope.getStudent();

});