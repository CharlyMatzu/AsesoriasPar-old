angular.module("Dashboard").controller('SubjectsController', function($scope, $http, $timeout, $window, Notification, SubjectService){
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

    //TODO: antes de obtener datos debe registrarse una carrera y un plan

    
    /**
     * Obtiene materias registrados
     */
    $scope.getSubjects = function(){

        $scope.showUpdateForm = false;
        $scope.loading.status = true;
        $scope.loading.message = "Obteniendo registros";

        $scope.subjects = [];

        SubjectService.getSubjects()
            .then(function(success){
                if( success.status == NO_CONTENT ){
                    //Notification.primary("no hay registros");
                    $scope.loading.message = "No hay registros";
                    $scope.subjects = [];
                }
                else
                    $scope.subjects = success.data;
                    
                $scope.loading.status = false;
               
            },
            function(error){
                Notification.error("Error al obtener materias: "+error.data);
                $scope.loading.status = false;
                $scope.loading.message = "Error: "+error.data;
            }
        );
    }

    $scope.getSubject_Search = function(subject){
        //$scope.showUpdateForm = false;
        $scope.loading.status = true;
        $scope.loading.message = "Obteniendo registros";

        $scope.subjects = [];

        SubjectService.getSubject_Search(subject)
            .then(function(success){
                if( success.status == NO_CONTENT ){
                    //Notification.primary("no hay registros");
                    $scope.loading.message = "No hay registros";
                    $scope.subjects = [];
                }
                else
                    $scope.subjects = success.data;
                    

                $scope.loading.status = false;
                console.log($scope.subjects);
            },
            function(error){
                Notification.error("No se encontraron materias "+error.data);
                $scope.loading.status = false;
                $scope.loading.message = "No se encontraron Materias";
            }
        );
    }



    $scope.searchSubjectByName = function(data){
        if( data == null || data == "" ) 
            return;

        $scope.subjects = [];
        $scope.loading.status = true;
        $scope.loading.message = "Buscando materias con "+data;

        SubjectService.searchSubjects(data)
            .then(function(success){
                if( success.status == NO_CONTENT )
                    $scope.loading.message = "No se encontraron materias";
                else
                    $scope.subjects = success.data;

                //Enabling refresh button
                $scope.loading.status = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener materias: " + error.data);
                $scope.loading.message = "Ocurrio un error =(";
                $scope.loading.status = false;
            }
        );
    }

    

    /**
     * 
     * @param {*} subject 
     */
    $scope.editSubject = function(subject){
        Notification("Cargando datos...");
        $scope.disableButtons(true, '.opt-subjects-'+subject.id);
        
        //Se obtienen Carreras
        SubjectService.getCareers()

            .then(function(success){
                Notification.success("Carreras cargadas");

                $scope.careers = success.data;
                
                //Se obtien planes
                SubjectService.getPlans(
                    function(success){
                        Notification.success("Planes cargados");
                        $scope.plans = success.data;

                        //Se agregan igualan campos
                        // y Asignando valores
                        $scope.subject.id = subject.id;
                        $scope.subject.name = subject.name;
                        $scope.subject.short_name = subject.short_name;
                        $scope.subject.description = subject.description;
                        //++ para que funcione al ser input number
                        $scope.subject.semester = ++subject.semester;
                        $scope.subject.plan = subject.plan_id;
                        $scope.subject.career = subject.career_id;

                        //Se abre form
                        $scope.showUpdateForm = true;
                    },
                    function(error){
                        Notification.error("Error al cargar planes, se detuvo actualizacion");
                        $scope.disableButtons(false, '.opt-subjects-'+subject.id);
                    }
                );
            },
            function(error){
                Notification.error("Error al cargar carreras, se detuvo actualizacion");
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
            }
        );
    }


    $scope.updateSubject = function(subject){

        Notification("Procesando...");

        if( subject.career == null || subject.career == "" ){
            Notification.warning("Debe seleccionar una carrera");
            return;
        }
        if( subject.plan == null || subject.plan == "" ){
            Notification.warning("Debe seleccionar una Plan");
            return;
        }
        if( subject.semester == null || subject.semester == "" || 
            subject.semester < 1 || subject.semester > 12 ){
            Notification.warning("Semestre debe ser numerico y debe estar entre 1 y 12");
            return;
        }


        
        $scope.disableButtons(true, '.opt-subjects-'+subject.id);
        
        SubjectService.updateSubject(subject)
            .then(function(success){
                Notification.success("Actualizado con exito");
                $scope.getSubjects();
            },
            function(error){
                Notification.error("Error: "+error.data);
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
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

    // //Obteniendo planes
    //     //Se obtien planes
    //     SubjectService.getPlans()
    //         .then(function(success){
    //             if( success.status == NO_CONTENT ){
    //                 Notification.warning("No hay planes registrados, redireccionando...");
    //                 //Si no hay, redirecciona
    //                 $timeout(function(){
    //                     $window.location.href = '#!/planes';
    //                 }, 2000);
    //             }
    //             else{
    //                 Notification.success("Planes cargados");
    //                 $scope.plans = success.data;
    //             }
    //         },
    //         function(error){
    //             Notification.error("Error al cargar planes: "+error.data);
    //             $scope.disableButtons(false, '.opt-subjects-'+subject.id);
    //         }
    //     );
    //     //Se obtienen Carreras
    //     SubjectService.getCareers()

    //         .then(function(success){
    //             if( success.status == NO_CONTENT ){
    //                 Notification.warning("No hay carreras registradas, redireccionando...");
    //                 //Si no hay, redirecciona
    //                 $timeout(function(){
    //                     $window.location.href = '#!/carreras';
    //                 }, 2000);
    //             }
    //             else{
    //                 Notification.success("Carreras cargadas");
    //                 $scope.careers = success.data;
    //             }
    //         },
    //         function(error){
    //             Notification.error("Error al cargar carreras: "+error.data);
    //             $scope.disableButtons(false, '.opt-subjects-'+subject.id);
    //         }
    //     );

    //Obtiene todos por default
    $scope.getSubjects();

});