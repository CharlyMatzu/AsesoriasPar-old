app.controller('StudentsController', function($scope, $http, $window, Notification, StudentsService){
    $scope.page.title = "Estudiantes";
    
    $scope.students = [];



    //-----------------------

    $scope.goToNewUser = function(){
        $window.location.href = '#!/estudiantes/nuevo';
    }

    /**
     * 
     * @param {String} data Informacion a buscar (correo)
     */
    $scope.searchStudent = function(data){
        if( data == null || data == "" ) 
            return;

        $scope.users = [];
        $scope.loading.status = true;
        $scope.loading.message = "Buscando estudiantes con "+data;

        StudentsService.searchStudent(data,
            function(success){
                if( success.status == NO_CONTENT )
                    $scope.loading.message = "No se encontraron estudiantes";
                else
                    $scope.users = success.data;

                //Enabling refresh button
                $scope.loading.status = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener estudiantes");
                $scope.loading.message = "Ocurrio un error =(";
                $scope.loading.status = false;
            }
        );
    }


    $scope.getStudents = function(){
        $scope.loading.status = true;
        $scope.loading.message = "Obteniendo registros";

        // $scope.students = [];

        StudentsService.getStudents(
            function(success){
                if( success.status == NO_CONTENT ){
                    $scope.students = [];
                }
                else{
                    $scope.students = success.data;
                }
                //Enabling refresh button
                $scope.loading.status = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener estudiantes");
            }
        );
    }


    $scope.openStudent = function(student_id){
        //TODO: mostrar informacion detallada   
    }


    /**
     * 
     * @param {int} user_id ID del Estudiante
     */
    $scope.enableStudent = function(user_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-student-'+user_id);

        Notification('Procesando...');
        StudentsService.changeStatus(user_id, ENABLED, 
            function(success){
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

        StudentsService.deleteStudent(user_id,
            function(success){
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
        StudentsService.changeStatus(user_id, DISABLED, 
            function(success){
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