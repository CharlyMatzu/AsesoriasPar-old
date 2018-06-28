angular.module("Dashboard").controller('SubjectsController', function($scope,  $timeout, $window, Notification, SubjectService, STATUS){
    $scope.page.title = "Materias > Registros";
    
    $scope.subjects = [];
    $scope.plans = [];
    $scope.careers = [];

    $scope.subject = {
        name: null,
        short_name: null,
        description: null,
        career: null,
        semester: null,
        plan: null
    }

    $scope.showUpdateSubject = false;
    $scope.loading = true;

    $scope.closeUpdate = function(){
        $scope.showUpdateSubject = false;
    }

    $scope.goToNewSubject = function(){
        $window.location = "#!/materias/nuevo";
        return;
    }

    $scope.editSubject = function(subject){
        $scope.showUpdateSubject = true;
        $scope.subject = subject;
        $scope.subject.semester = Number.parseInt(subject.semester);
    }

    
    /**
     * Obtiene materias registrados
     */
    $scope.getSubjects = function(){

        $scope.showUpdateSubject = false;
        $scope.loading = true;

        $scope.subjects = [];

        SubjectService.getSubjects()
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    $scope.subjects = [];
                }
                else
                    $scope.subjects = success.data;
                    
                $scope.loading = false;
            },
            function(error){
                Notification.error("Error al obtener materias: "+error.data);
                $scope.loading = false;
            }
        );
    }

    $scope.getSubject_Search = function(subject){
        //$scope.showUpdateSubject = false;
        $scope.loading = true;

        $scope.subjects = [];

        SubjectService.getSubject_Search(subject)
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    //Notification.primary("no hay registros");
                    $scope.subjects = [];
                }
                else
                    $scope.subjects = success.data;
                    

                $scope.loading = false;
                console.log($scope.subjects);
            },
            function(error){
                Notification.error("No se encontraron materias "+error.data);
                $scope.loading = false;
            }
        );
    }



    $scope.searchSubjectByName = function(data){
        if( data == null || data == "" ) 
            return;

        $scope.subjects = [];
        $scope.loading = true;

        SubjectService.searchSubjects(data)
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT )
                    $scope.subjects = [];
                else
                    $scope.subjects = success.data;

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener materias: " + error.data);
                $scope.loading = false;
            }
        );
    }

    

    /**
     * 
     * @param {*} subject 
     */
    $scope.loadData = function(subject){
        $scope.loading = true;
        
        //Se obtienen Carreras
        SubjectService.getCareers()
            //Carreras
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    $scope.careers = [];
                    alert("No hay carreras registradas, se redireccionará");
                    $window.location = "#!/carreras";
                }
                else{
                    $scope.careers = success.data;
                    //Se obtien planes
                    return SubjectService.getPlans();
                }
            },
            function(error){
                Notification.error("Error al cargar carreras, se detuvo actualizacion");
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
            })
            //Planes
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    $scope.plans = [];
                    alert("No hay planes registrados, se redireccionará");
                    $window.location = "#!/carreras";
                }
                else{
                    $scope.plans = success.data;
                    $scope.loading = false;
                    //Se cargan materias aparte
                    $scope.getSubjects();
                }

            },
            function(error){
                Notification.error("Error al cargar planes, se detuvo actualizacion");
                $scope.loading = false;
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
            });
    }


    $scope.updateSubject = function(subject){

        Notification("Procesando...");

        if( !subject.career_id ){
            Notification.warning("Debe seleccionar una carrera");
            return;
        }
        if( !subject.plan_id ){
            Notification.warning("Debe seleccionar una Plan");
            return;
        }
        if( !subject.semester || subject.semester < 1 || subject.semester > 12 ){
            Notification.warning("Semestre debe ser numerico y debe estar entre 1 y 12");
            return;
        }

        $scope.disableButtons(true, '.opt-subjects-'+subject.id);
        
        SubjectService.updateSubject(subject)
            .then(function(success){
                Notification.success("Actualizado con exito");
                $scope.getSubjects();
                $scope.showUpdateSubject = false;
            },
            function(error){
                Notification.error("Error: "+ error.data);
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
                $scope.showUpdateSubject = false;
            }
        );
    }


    

    $scope.deleteSubject = function(subject_id){

        Notification("Procesando...");
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-subject-'+subject_id);
        
        SubjectService.deleteSubject(subject_id)
            .then(function(success){
                Notification.success("Eliminado con exito");
                $scope.getSubjects();
            },
            function(error){
                Notification.success("Error: "+error.data);
                $scope.disableButtons(false, '.opt-subject-'+subject_id);
            }
        );
    }

    /**
     * 
     * @param {*} subject_id 
     */
    $scope.disableSubject = function(subject_id){
        $scope.disableButtons(true, '.opt-subject-'+subject_id);
        Notification("Procesando...");

        SubjectService.changeStatus(subject_id, DISABLED)
            .then(function(success){
                Notification.success("Deshabilitado con exito");
                $scope.getSubjects();
            },
            function(error){
                Notification.error("Error al Deshabilitar materia: "+error.data);
                $scope.disableButtons(false, '.opt-subject-'+subject_id);
            }
        );
    }

    /**
     * 
     * @param {*} subject_id 
     */
    $scope.enableSubject = function(subject_id){
        $scope.disableButtons(true, '.opt-subject-'+subject_id);
        Notification("Procesando...");

        SubjectService.changeStatus(subject_id, ACTIVE)
            .then(function(success){
                Notification.success("habilitado con exito");
                $scope.getSubjects();
            },
            function(error){
                Notification.error("Error al habilitar materia: "+error.data);
                $scope.disableButtons(false, '.opt-subject-'+subject_id);
            }
        );
    }

    //Obtiene todos por default
    $scope.loadData();

});