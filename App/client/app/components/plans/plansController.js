app.controller('PlansController', function($scope, $http,Notification, PlansService){
    $scope.page.title = "Planes academicos"
    $scope.plans = [];

    $scope.showForm = false;
    $scope.newPlan = "";
    

    $scope.getPlans = function(){
        PlansService.getPlans(
            function(success){
                if( success.status == NO_CONTENT )
                    $scope.loading.message = "No se encontraron planes";
                else
                    $scope.plans = success.data;

                //Enabling refresh button
                // $scope.loading.status = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data);
                $scope.loading.message = "Ocurrio un error =(";
                $scope.loading.status = false;
            }
        );
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
    $scope.getPlans();

});