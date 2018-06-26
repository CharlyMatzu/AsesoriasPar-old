angular.module("Desktop").controller('AdvisoriesController', function($scope, Notification, AdvisoriesService, STATUS){


    $scope.page.title = 'Escritorio > Asesorías';    

    $scope.loading = false;
    $scope.loadingSubjects = false;

    $scope.advisories = [];
    $scope.showNewRequest = false;

    //Request advisory
    $scope.subjects = [];
    $scope.selectedSub = null;



    var getAdvisories = function(){
        $scope.loading = true;

        AdvisoriesService.getRequestedAdvisories( $scope.student.id )
            .then(
                function(success){
                    $scope.advisories = success.data;
                },
                function(error){
                    Notification.error("Ocurrio un error: "+error.data);
                }
            )
            .finally(function(){
                $scope.loading = false;
            });
    };
    

    var getSubjects = function(){
        $scope.loadingSubjects = true;
        $scope.subjects = [];

        AdvisoriesService.getSubjects()
            .then(
                function(success){
                    $scope.subjects = success.data;
                },
                function(error){
                    Notification.error("Error: "+error.data);
                }
            )
            .finally(function(){
                $scope.loadingSubjects = false;
            });
    };


    $scope.newRequest = function(){
        //TODO: obtener solo materias que no se han solicitado
        getSubjects();
        $scope.selectedSub = null;
        $scope.showNewRequest = true;
    }
    

    $scope.closeNewAdvisory = function(){
        $scope.showNewRequest = false;
        $scope.selectedSub = null;
    }

    $scope.selectSub = function(subject){
        $scope.selectedSub = subject;
    };


    $scope.requestAdvisory = function(){
        if( $scope.selectedSub == null){
            Notification.warning("No ha seleccionado materia");
            return;    
        }

        Notification("Procesando...");
        var advsory = {
            subject: $scope.selectedSub.id,
            description: "Sin descripcion",
            student: $scope.student.id
        };

        AdvisoriesService.requestAdvisory(advsory)
            .then(
                function(success){
                    if( success.status === STATUS.NO_CONTENT )
                        Notification.warning("No se pudo finalizar solicitud");
                    else
                        Notification.success(success.data);

                    $scope.closeNewAdvisory();
                    getAdvisories();
                },
                function(error){
                    if( error.status == STATUS.CONFLICT )
                        Notification.warning(error.data);
                    else
                        Notification.error(error.data);

                    $scope.showNewRequest = false;
                }
            )
        
    };

    $scope.finalize = function(advisory_id){
        Notification("Procesando...");

        AdvisoriesService.finalizeAdvisory(advisory_id)
            .then(
                function(success){
                    Notification.success("Asesoria finalizada con éxito");
                    
                    //TODO: Verificar cual tipo es
                    //obtener asesorias
                    getAdvisories();
                    // getAdviserAds();
                },
                function(error){
                    Notification.error("Ocurrio un error");
                }
            );
    };


    //Si se ejecuta, se considera un periodo como existente (desktopController lo determina)
    (function(){
        getAdvisories();
    })();

});