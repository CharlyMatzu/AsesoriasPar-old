app.controller('StudentDetailController', function($scope, $http, $window, Notification, StudentDetailService, $routeParams){
    $scope.page.title = "Estudiante";
    
    $scope.student = [];

    
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
                        $scope.student = success.data;
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


    $scope.openStudent = function(student_id){
        //TODO: mostrar informacion detallada   
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