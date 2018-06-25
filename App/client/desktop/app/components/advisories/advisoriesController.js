angular.module("Desktop").controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService, RequestFactory, STATUS){

    $scope.loading = false;

    $scope.requestedAds = [];
    $scope.showRequestedAds = true;

    $scope.adviserAds = [];
    $scope.showAdviserdAds = true;
    
    $scope.showAdvisories = false;
    $scope.showNewRequest = false;

    //Request advisory
    $scope.subjects = null;
    $scope.selectedSub = null;



    var getRequestedAds = function(){
        $scope.loading = true;

        AdvisoriesService.getRequestedAdvisories( $scope.student.id )
            .then(
                function(success){
                    $scope.requestedAds = success.data;
                    $scope.loading = false;
                },
                function(error){
                    Notification.error("Ocurrio un error: "+error.data);
                    $scope.loading = false;
                }
            )
            .finally(function(){
                $scope.showRequestedAds = true;
            });
    };

    var getAdviserAds = function(){
        $scope.loading = true;

        AdvisoriesService.getAdviserAdvisories( $scope.student.id )
            .then(
                function(success){
                    $scope.adviserAds = success.data;
                    $scope.loading = false;
                },
                function(error){
                    Notification.error("Ocurrio un error: "+error.data);
                    $scope.loading = false;
                }
            );
    };
    

    var getSubjects = function(){
        AdvisoriesService.getSubjects()
            .then(
                function(success){
                    $scope.subjects = success.data;
                },
                function(error){
                    Notification.error("Error: "+error.data);
                }
            );
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
                    getRequestedAds();
                },
                function(error){
                    if( error.status == STATUS.CONFLICT )
                        Notification.warning(error.data);
                    else
                        Notification.error(error.data);
                }
            );
        
    };

    $scope.finalize = function(advisory_id){
        Notification("Procesando...");

        AdvisoriesService.finalizeAdvisory(advisory_id)
            .then(
                function(success){
                    Notification.success("Asesoria finalizada con éxito");
                    
                    //TODO: Verificar cual tipo es
                    //obtener asesorias
                    getRequestedAds();
                    getAdviserAds();
                },
                function(error){
                    Notification.error("Ocurrio un error");
                }
            );
    };


    (function(){
        $scope.showRequestedAds = false;
        $scope.showAdviserdAds = false;
        $scope.loadData(
            function(){
                getRequestedAds();
            }
        );
    })();


});