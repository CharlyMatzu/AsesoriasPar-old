angular.module("Dashboard").controller('StudentDetailController', function($scope,  $window, Notification, StudentDetailService, CareerService, $routeParams, STATUS){

    $scope.page.title = "Estudiante > Detalle";
    
    $scope.student = [];
    $scope.schedule = {};
    $scope.daysAndHours = [];
    $scope.loading = true;
    $scope.careers = [];



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

    
    $scope.loadData = function(){
        //Se obtiene id de ruta
        var id = $routeParams.id;

        $scope.loading = true;
        
        //Si esta vacio o no es númerico, regresa a estudiantes
        if( id == null || !Number.parseInt(id) ){
            $window.location.href = "#!/estudiantes";
        }
        else{

            CareerService.getCareers()
                //Promesa carreras
                .then(function(success){
                    if( success.status === STATUS.NO_CONTENT ){
                        alert("No se encontraron carreras registradas");
                        $window.location = "#!/carreras";
                        $scope.careers = [];
                    }
                    else{
                        $scope.careers = success.data;
                        return StudentDetailService.getStudent(id);
                    }
                    
                })
                
                //Promesa estudiante;
                .then(function(success){

                    //Se asignar informacion
                    $scope.student = success.data;
                    //Se obtienen horario
                    return StudentDetailService.getStudentSchedule( id );

                    
                })
                
                //Promesa horario
                .then(function(success){

                    if( success.status == STATUS.NO_CONTENT ){
                        $scope.schedule = null;
                        throw "Sin horario";
                    }
                    else{
                        $scope.schedule = success.data;
                        //Se obtiene dias y horas
                        return StudentDetailService.getDaysAndHours_source();
                    }

                })
                
                //Promesa dias y horas
                .then(function(success){
                    if( success.status == STATUS.NO_CONTENT )
                        alert("Error al cargar horas");
                    else
                        $scope.daysAndHours = success.data;

                    $scope.loading = false;    
                })
                //FIXME: arreglar el manejo de errores
                .catch(function(error){
                    if( error.data )
                        Notification.warning( error.data );
                    $scope.loading = false;
                });

        }
    }


    /**
     * 
     * @param {int} user_id ID del Estudiante
     */
    $scope.enable = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        Notification('Procesando...');
        StudentDetailService.changeStatus(user_id, ACTIVE)
            .then(function(success){
                Notification.success("Habilitado con exito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getStudent();
            },
            function(error){
                Notification.error("Error al Habilitar Estudiante");
                //Habilita botones
                $scope.disableButtons(false, '.opt-student-'+user_id);
            }
        );
    }


    /**
     * 
     * @param {int} user_id ID del Estudiante
     */
    $scope.disable = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        Notification('Procesando...');
        StudentDetailService.changeStatus(user_id, DISABLED) 
            .then(function(success){
                Notification.success("Deshabilitado con exito");
                $scope.getStudent();
            },
            function(error){
                Notification.error("Error al deshabilitar Estudiante: "+ error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-student-'+user_id);
            }
        );
    }

    $scope.updateStudentData = function(student){
        $scope.loading = true;

        StudentDetailService.updateStudent(student)
            .then(function(success){
                Notification.success("Actualizado con éxito");
                $scope.getStudent();
            }, function(error){
                Notification.error("Ocurrio un error al actualizar: "+error.data);
                $scope.loading = false;
            });        
    }


    // /**
    //  * 
    //  * @param {int} user_id ID del Estudiante
    //  */
    // $scope.deleteStudent = function(user_id){
    //     //Deshabilita botones
    //     $scope.disableButtons(true, '.opt-student-'+user_id);

    //     StudentDetailService.deleteStudent(user_id)
    //         .then(function(success){
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


    //Se carguen datos al iniciar pagina
    $scope.loadData();

});