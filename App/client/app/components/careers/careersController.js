app.controller('CareersController', function($scope, $http, CareersService){
    $scope.newCareer = false;
    $scope.updateCareer = false;
    $scope.updateStatusCareer = false;
    $scope.careers = [];
    $scope.loader = {
        loading: true,
        message: "Cargando"
    };

    $scope.getCareers = function(){
        CareersService.getCareers(function(response){
            $scope.careers = response;
        });
    }

    $scope.add = function(career){
        CareersService.addCareer(function(response){
            $scope.getCareers();
        }, career);
    }

    $scope.deleteCareer = function(career_id){
        CareersService.deleteCareer(function(response){
            $scope.getCareers();
        }, career_id);
    }

    $scope.update = function(career){
        CareersService.updateCareer(function(response){
            $scope.getCareers();
        }, career);
    }

    $scope.updateStatus = function(career){
        CareersService.updateStatusCareer(function(response){
            $scope.getCareers();
        }, career);
    }
    //Obtiene todos por default
    $scope.getCareers();

});