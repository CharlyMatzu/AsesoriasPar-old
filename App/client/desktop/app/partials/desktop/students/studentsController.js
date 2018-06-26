angular.module("Desktop").controller('StudentsController', function($scope, $http, Notification, StudentsService, RequestFactory, STATUS){


    $scope.page.title = 'Escritorio > Alumnos';

    $scope.loading = false;

    $scope.advisories = [];



    var getAdvisories = function(){
        $scope.loading = true;

        StudentsService.getAdviserAdvisories( $scope.student.id )
            .then(
                function(success){
                    $scope.adviserAds = success.data;
                },
                function(error){
                    Notification.error(error.data);
                }
            )
            .finally(function(){
                $scope.loading = false;
            });
    };
    

    var getSubjects = function(){
        StudentsService.getSubjects()
            .then(
                function(success){
                    $scope.subjects = success.data;
                },
                function(error){
                    Notification.error("Error: "+error.data);
                }
            );
    };

    $scope.finalize = function(advisory_id){
        Notification("Procesando...");

        StudentsService.finalizeAdvisory(advisory_id)
            .then(
                function(success){
                    Notification.success("Asesoria finalizada con éxito");
                    getAdvisories();
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