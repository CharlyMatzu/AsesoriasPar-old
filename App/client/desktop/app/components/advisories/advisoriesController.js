angular.module("Desktop").controller('AdvisoriesController', function($scope, $http, Notification, AdvisoriesService, RequestFactory){

    $scope.requestedAds = [];
    $scope.showRequestedAds = false;

    $scope.adviserAds = [];
    $scope.showAdviserdAds = false;
    

    $scope.showAdvisories = false;
    $scope.showNewAdvisory = false;

    //Request advisory
    $scope.subjects = {};
    $scope.selectedSub = null;



    $scope.getRequestedAds = function(){
        $scope.loading.status = true;
        
        $scope.showRequestedAds = true;
        $scope.showAdviserdAds = false;

        AdvisoriesService.getRequestedAdvisories( $scope.student.id )
            .then(
                function(success){
                    $scope.requestedAds = success.data;
                    $scope.loading.status = false;
                },
                function(error){
                    Notification.error("Ocurrio un error: "+error.data);
                    $scope.loading.status = false;
                }
            );
    };

    $scope.getAdviserAds = function(){
        $scope.loading.status = true;
        $scope.showRequestedAds = false;
        $scope.showAdviserdAds = true;

        AdvisoriesService.getAdviserAdvisories( $scope.student.id )
            .then(
                function(success){
                    $scope.adviserAds = success.data;
                    $scope.loading.status = false;
                },
                function(error){
                    Notification.error("Ocurrio un error: "+error.data);
                    $scope.loading.status = false;
                }
            );
    };
    

    $scope.getSubjects = function(){
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

    $scope.newAdvisory = function(){
        //TODO: obtener solo materias que no se han solicitado
        $scope.getSubjects();
        $scope.selectedSub = null;
        $scope.showNewAdvisory = true;
    }

    $scope.closeNewAdvisory = function(){
        $scope.showNewAdvisory = false;
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
                    Notification.success("Solicitado con exito");
                    $scope.closeNewAdvisory();
                    $scope.getRequestedAds();
                },
                function(error){
                    if( error.status == CONFLICT ){
                        Notification.warning("Error al solicitar asesoria: "+error.data);
                    }
                    else{
                        Notification.error("Error al solicitar asesoria: "+error.data);
                    }
                }
            );
        
    };

    $scope.finalize = function(advisory_id){
        Notification("Procesando...");

        AdvisoriesService.finalizeAdvisory(advisory_id)
            .then(
                function(success){
                    Notification.success("Asesoria finalizada con éxito");
                    //TODO: obtener asesorias
                },
                function(error){
                    Notification.error("Ocurrio un error");
                }
            );
    };


    (function(){
        $scope.loadData(
            function(){
                $scope.getRequestedAds();
            }
        );
    })();


});