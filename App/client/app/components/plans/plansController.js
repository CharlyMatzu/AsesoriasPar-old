app.controller('PlansController', function($scope, $http,Notification, PlansService){
    $scope.plans = [];
    
    

    $scope.getPlans = function(){
        PlansService.getPlans(
            function(success){
                if( success.status == NO_CONTENT ){
                    Notification.primary('No hay plan registrados');
                }
                else{
                    Notification.success('Datos obtenidos');
                    $scope.plans = success.data;
                }
                    
            },
            function( error ){
                $scope.errorSnack("Error al obtener usuarios");
            });
    }
    
    $scope.add = function(plan){
        //Se pone en cargando
        $scope.newPlan.status = true

        //Se hace peticion
        PlansService.addPlan(plan, 
            function(success){
                Notification.success('Datos obtenidos');
                $scope.getPlans();
            }, 
            function(error){
                $scope.errorSnack("Error al registrar plan: "+error.data.message);
            });
    }

    $scope.edit = function(plan){
       
        //Se hace peticion
        PlansService.updatePlan(plan, 
            function(success){
                Notification.success('Datos obtenidos');
                $scope.getPlans();
            }, 
            function(error){
                $scope.errorSnack("Error al registrar plan: "+error.data.message);
            });
    }


    $scope.deletePlan = function(plan_id){
        PlansService.deletePlan(plan_id,
            function(success){
                $scope.getPlans();
            },
            function(error){
                $scope.errorSnack("Error al eliminar plan");
            });
    }

    //Se carguen datos al iniciar pagina
    // $scope.getPlans();

});