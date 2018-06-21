angular.module("Dashboard").controller('PlansController', function($scope, $http,Notification, PlansService){
    $scope.page.title = "Planes academicos"
    $scope.plans = [];

    $scope.plan = {
        id: 0,
        year: 0
    };

    $scope.showUpdateForm = false;
    $scope.newPlan = "";
    

    $scope.getPlans = function(){

        //Vaciando campo
        $scope.plans = [];

        $scope.showUpdateForm = false;

        $scope.loading.status = true;
        $scope.loading.message = "Obteniendo registros";

        PlansService.getPlans(
            function(success){
                if( success.status == NO_CONTENT )
                    $scope.loading.message = "No se encontraron planes";
                else
                    $scope.plans = success.data;

                //Enabling refresh button
                $scope.loading.status = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data);
                $scope.loading.status = false;
                $scope.loading.message = "Ocurrio un error =(";
            }
        );
    }
    
    /**
     * 
     * @param {*} plan 
     */
    $scope.addPlan = function(plan){
        if( plan == "" || plan == null ){
            Notification.warning('Campo vacio');
            return;
        }
        
        Notification.primary('Procesando registro...');

        //Se hace peticion
        PlansService.insertPlan(plan,
            function(success){
                Notification.success('Plan registrado con exito');
                $scope.getPlans();
            }, 
            function(error){
                Notification.error("Error al registrar plan: "+error.data);
            });
    }
    

    $scope.editPlan = function(plan){
        $scope.plan.id = plan.id;
        $scope.plan.year = plan.year;

        //Muestra form
        $scope.showUpdateForm = true;
    }


    $scope.updatePlan = function(plan){
       
        //Se hace peticion
        PlansService.updatePlan(plan, 
            function(success){
                Notification.success('Plan actualizado');
                $scope.getPlans();
            }, 
            function(error){
                $scope.errorSnack("Error al actualizar plan: "+error.data);
            });
    }


    $scope.deletePlan = function(plan_id){

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-plan-'+plan_id);
        Notification('Procesando...');

        PlansService.deletePlan(plan_id,
            function(success){
                Notification.success('Plan eliminado con exito');
                $scope.getPlans();
            },
            function(error){
                Notification.error('Error: '+error.data);
                $scope.disableButtons(false, '.opt-plan-'+plan_id);
            });
    }

    /**
     * 
     * @param {int} plan_id ID del plan
     */
    $scope.enablePlan = function(plan_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-plan-'+plan_id);

        Notification('Procesando...');
        PlansService.changeStatus(plan_id, ENABLED, 
            function(success){
                Notification.success("Habilitado con exito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getPlans();
            },
            function(error){
                Notification.error("Error al Habilitar Plan: " + error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-plan-'+plan_id);
            }
        );
    }


    /**
     * 
     * @param {int} plan_id ID del plan
     */
    $scope.disablePlan = function(plan_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-plan-'+plan_id);

        Notification('Procesando...');
        PlansService.changeStatus(plan_id, DISABLED, 
            function(success){
                Notification.success("Deshabilitado con exito");
                $scope.getPlans();
            },
            function(error){
                Notification.error("Error al deshabilitar plan: " + error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-plan-'+plan_id);
            }
        );
    }

    //Se carguen datos al iniciar pagina
    $scope.getPlans();

});