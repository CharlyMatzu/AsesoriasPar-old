angular.module("Dashboard").controller('StudentsController', function($scope,  $window, Notification, StudentsService, CareerService, STATUS){
    
    
    $scope.page.title = "Estudiantes";
    $scope.students = [];
    $scope.loading = true;
    $scope.careers = [];


    //-----------------------

    $scope.goToNewStudent = function(){
        $window.location.href = '#!/estudiantes/nuevo';
    }

    /**
     * 
     * @param {String} data Información a buscar (correo)
     */
    $scope.searchStudents = function(data){
        
        //Validation
        if( data == null || data == "" ) 
            return;

        $scope.loading = true;
        
        StudentsService.searchStudents(data)

            .then(function(success){
                if( success.status === STATUS.NO_CONTENT )
                    $scope.students = [];
                else
                    $scope.students = success.data;

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener estudiantes");
                $scope.loading = false;
            }
        );
    }


    $scope.getStudents = function(){
        $scope.loading = true;

        StudentsService.getStudents()
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT ){
                    $scope.students = [];
                }
                else
                    $scope.students = success.data;

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener estudiantes");
                $scope.loading = false;
            }
        );
    }

    $scope.getStudentsByCareer = function(career_id){
        $scope.loading = true;

        StudentsService.getStudentsByCareer(career_id)
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT ){
                    $scope.students = [];
                }
                else
                    $scope.students = success.data;
                

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener estudiantes por carrera");
                $scope.loading = false;
            }
        );
    }


    $scope.openStudentDetail = function(student_id){
        $window.location = "#!/estudiantes/"+student_id+"/detalle";
    }


    /**
     * 
     * @param {int} user_id ID del Estudiante
     */
    $scope.enableStudent = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        Notification('Procesando...');
        StudentsService.changeStatus(user_id, ACTIVE)
            .then(function(success){
                Notification.success("Habilitado con éxito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getStudents();
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
    $scope.deleteStudent = function(user_id){
        var message = "Se eliminará el estudiante ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        StudentsService.deleteStudent(user_id)
            .then(function(success){
                Notification.success("Estudiante eliminado con éxito");
                $scope.getStudents();
            },
            function(error){
                Notification.error("Error al eliminar Estudiantes");
                //Habilita botones
                $scope.disableButtons(false, '.opt-student-'+user_id);
            }
        );
    }


    /**
     * 
     * @param {int} user_id ID del Estudiante
     */
    $scope.disableStudent = function(user_id){
        var message = "Se desactivará acceso y aparición del estudiante ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        Notification('Procesando...');
        StudentsService.changeStatus(user_id, DISABLED)
            .then(function(success){
                Notification.success("Deshabilitado con éxito");
                $scope.getStudents();
            },
            function(error){
                Notification.error("Error al deshabilitar Estudiante");
                //Habilita botones
                $scope.disableButtons(false, '.opt-student-'+user_id);
            }
        );
    };

    /**
     * 
     * @param {int} user_id ID del Estudiante
     */
    $scope.enableStudent = function(user_id){

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        Notification('Procesando...');
        StudentsService.changeStatus(user_id, ACTIVE)
            .then(function(success){
                Notification.success("Deshabilitado con éxito");
                $scope.getStudents();
            },
            function(error){
                Notification.error("Error al deshabilitar Estudiante");
                //Habilita botones
                $scope.disableButtons(false, '.opt-student-'+user_id);
            }
        );
    };


    (function(){
        $scope.loading = true;

        CareerService.getCareers()
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT ){
                    alert("No hay carreras registradas");
                    $window.location = "#!/carreras";
                    return;
                }
                else{
                    $scope.careers = success.data;
                    $scope.getStudents();
                }
                
            }, function(){
                Notification.error("Ocurrió un error al obtener carreras: "+error.data);
                $scope.loading = false;
            })
        
    })();
    //Se carguen datos al iniciar pagina
    

});