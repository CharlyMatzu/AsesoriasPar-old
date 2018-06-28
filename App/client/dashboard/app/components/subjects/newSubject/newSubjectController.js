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
    $scope.loading = true;



    /**
     * 
     * @param {*} user 
     */
    var loadData = function(user){
        $scope.loading = true;
        
        //Se obtienen Carreras
        NewSubjectService.getCareers()
            .then(function(success){
                if( success.status == STATUS.NO_CONTENT ){
                    alert("No hay carreras registradas, redireccionando...");
                    $scope.loading = false;
                    $window.location.href = '#!/carreras';
                    return;
                }
                else{
                    $scope.careers = success.data;
                    //Obteniendo promesa de planes
                    return NewSubjectService.getPlans();
                }
            },
            function(error){
                Notification.error("Error al cargar carreras: "+error.data);
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
            })

            //Promesa de plan
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT ){
                    alert("No hay planes registrados, redireccionando...");
                    $window.location.href = '#!/planes';
                    return;
                }
                else
                    $scope.plans = success.data;

                $scope.loading = false;
            },
            function(error){
                Notification.error("Error al cargar planes: "+error.data);
                $scope.disableButtons(false, '.opt-subjects-'+subject.id);
                $scope.loading = false;
            });
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
                $window.location.href = '#!/materias';
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