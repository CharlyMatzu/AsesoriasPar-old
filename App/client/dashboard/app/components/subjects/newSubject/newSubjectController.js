angular.module("Dashboard").controller('NewSubjectController', function($scope, $window, $timeout,  NewSubjectService, Notification, STATUS){
    $scope.page.title = "Materias > Nuevo";

    $scope.plans = [];
    $scope.careers = [];
    $scope.subject = {
        name: null,
        short_name: null,
        description: null,
        career: null,
        semester: null,
        plan: null
    };



    /**
     * 
     * @param {*} user 
     */
    var loadData = function(user){
        Notification("Cargando datos...");
        
        //Se obtienen Carreras
        NewSubjectService.getCareers()

            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    Notification.warning("No hay carreras registradas, redireccionando...");
                    //Si no hay, redirecciona
                    $timeout(function(){
                        $window.location.href = '#!/carreras';
                    }, 2000);
                }
                else{
                    Notification.success("Carreras cargadas");
                    $scope.careers = success.data;
                }
            },
            function(error){
                Notification.error("Error al cargar carreras: "+error.data);
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
            }
        );

        //Obteniendo planes
        //Se obtien planes
        NewSubjectService.getPlans()
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT ){
                    Notification.warning("No hay planes registrados, redireccionando...");
                    //Si no hay, redirecciona
                    $timeout(function(){
                        $window.location.href = '#!/planes';
                    }, 2000);
                }
                else{
                    Notification.success("Planes cargados");
                    $scope.plans = success.data;
                }
            },
            function(error){
                Notification.error("Error al cargar planes: "+error.data);
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
            }
        );
    };


    /**
     * Agrega un materia
     * @param {*} subject 
     */
    $scope.addSubject = function(subject){
        Notification("Procesando...");
        if( subject.career_id == null || subject.career_id == "" ){
            Notification.warning("Debe seleccionar una carrera");
            return;
        }
        if( subject.plan_id == null || subject.plan_id == "" ){
            Notification.warning("Debe seleccionar una Plan");
            return;
        }
        if( subject.semester == null || subject.semester == "" || 
            subject.semester < 1 || subject.semester > 12 ){
            Notification.warning("Semestre debe ser numerico y debe estar entre 1 y 12");
            return;
        }
        
        NewSubjectService.addSubject(subject)
            .then(function(success){
                Notification.success("Registrado con exito");
                //TODO: Limpiar campo
                // $scope.subject = {};
            },
            function(error){
                Notification.error("Error: "+error.data);
            }
        );
    };


    //Para cargar datos
    (function(){
        loadData();
    })();

});