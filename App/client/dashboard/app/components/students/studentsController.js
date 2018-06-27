angular.module("Dashboard").controller('StudentsController', function($scope,  $window, Notification, StudentsService, STATUS){
    
    
    $scope.page.title = "Estudiantes > Detalle";
    $scope.students = [];
    $scope.loading = true;



    //-----------------------

    $scope.goToNewStudent = function(){
        $window.location.href = '#!/estudiantes/nuevo';
    }

    /**
     * 
     * @param {String} data Informacion a buscar (correo)
     */
    $scope.searchStudent = function(data){
        
        //Validation
        if( data == null || data == "" ) 
            return;

        $scope.loading = true;
        
        StudentsService.searchStudent(data)

            .then(function(success){
                if( success.status == STATUS.NO_CONTENT )
                    $scope.students = [];
                else
                    $scope.users = success.data;

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
                if( success.status == STATUS.NO_CONTENT ){
                    $scope.students = [];
                }
                else{
                    $scope.students = success.data;
                }

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener estudiantes");
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
                Notification.success("Habilitado con exito");
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
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        StudentsService.deleteStudent(user_id)
            .then(function(success){
                Notification.success("Estudiante eliminado con exito");
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
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        Notification('Procesando...');
        StudentsService.changeStatus(user_id, DISABLED)
            .then(function(success){
                Notification.success("Deshabilitado con exito");
                $scope.getStudents();
            },
            function(error){
                Notification.error("Error al deshabilitar Estudiante");
                //Habilita botones
                $scope.disableButtons(false, '.opt-student-'+user_id);
            }
        );
    }


    //Se carguen datos al iniciar pagina
    $scope.getStudents();

});