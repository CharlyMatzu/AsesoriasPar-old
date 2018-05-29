app.controller('PeriodsController', function($scope, $http, PeriodsService){
    $scope.CurrentDate = new Date();
    $scope.newPeriod = false;
    $scope.updatePeriod = false;
    $scope.periods = [];
    $scope.loader = {
        loading: true,
        message: "Cargando"
    };

    $scope.getPeriods = function(){
        PeriodsService.getPeriods(function(response){
            $scope.periods = response;
        });
    }

    $scope.add = function(period){
        PeriodsService.addPeriod(function(response){
            $scope.getPeriods();
        },period);
    }

    $scope.deletePeriod = function(period_id){
        PeriodsService.deletePeriod(function(response){
            $scope.getPeriods();
        }, period_id);
    }

    $scope.update = function(period){
        PeriodsService.updatePeriod(function(response){
            $scope.getPeriods();
        }, period);
    }

    $scope.updateStatus = function(period){
        PeriodsService.updateStatusPeriod(function(response){
            $scope.getPeriods();
        }, period);
    }
    //Obtiene todos por default
    $scope.getPeriods();

});