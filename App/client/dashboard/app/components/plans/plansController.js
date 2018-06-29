angular.module("Dashboard").controller('PlansController', function($scope, Notification, PlansService, STATUS){


    $scope.page.title = "Planes académicos"
    $scope.plans = [];
    $scope.loading = true;
    $scope.showUpdatePlan = false;
    $scope.showNewPlan = false;
    $scope.plan = {
        id: 0,
        year: 0
    };

    $scope.showUpdatePlan = false;
    $scope.newPlan = "";
    

    $scope.getPlans = function(){

        //Vaciando campo
        $scope.plans = [];
        $scope.showUpdatePlan = false;
        $scope.loading = true;

        PlansService.getPlans()
            .then(function(success){
                if( success.status === STATUS.NO_CONTENT )
                    $scope.plans = [];
                else
                    $scope.plans = success.data;

                //Enabling refresh button
                $scope.loading = false;
                    
            },
            function( error ){
                Notification.error("Error al obtener usuarios: " + error.data);
                $scope.loading = false;
            }
        );
    };

    /**
     * Verifica que el plan sea un valor valido
     * @param {String} plan 
     */
    var validatePlan = function(plan){
        // if( plan.length != 4 )
        //     return "deben ser 4 dígitos";

        // if( !Number.isInteger(plan) )
        //     return "Debe ser numérico entero";

        return null;
    }
    
    /**
     * 
     * @param {*} plan 
     */
    $scope.addPlan = function(plan){
        if( validatePlan(plan) ){
            Notification.error( validatePlan(plan) );
            return;
        }
        
        
        Notification.primary('Procesando registro...');

        //Se hace petición
        PlansService.insertPlan(plan)
            .then(function(success){
                Notification.success('Plan registrado con éxito');
                $scope.getPlans();
                $scope.showNewPlan = false;
            }, 
            function(error){
                Notification.error("Error al registrar plan: "+error.data);
            });
    };
    

    $scope.editPlan = function(plan){
        $scope.plan.id = plan.id;
        $scope.plan.year = plan.year;

        //Muestra form
        $scope.showUpdatePlan = true;
    };


    $scope.updatePlan = function(plan){
        var message = "Se actualizará plan ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        if( validatePlan(plan) ){
            Notification.error( validatePlan(plan) );
            return;
        }
       
        //Se hace petición
        PlansService.updatePlan(plan)
            .then(function(success){
                Notification.success('Plan actualizado');
                $scope.getPlans();
            }, 
            function(error){
                Notification.error("Error al actualizar plan: "+error.data);
                // $scope.showUpdatePlan = false;
            });
    };


    $scope.deletePlan = function(plan_id){
        var message = "Las materias asociadas a dicho periodo serán eliminadas ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-plan-'+plan_id);
        Notification('Procesando...');

        PlansService.deletePlan(plan_id)
            .then(function(success){
                Notification.success('Plan eliminado con éxito');
                $scope.getPlans();
            },
            function(error){
                Notification.error('Error: '+error.data);
                $scope.disableButtons(false, '.opt-plan-'+plan_id);
            });
    };

    /**
     * 
     * @param {int} plan_id ID del plan
     */
    $scope.enablePlan = function(plan_id){
        //Deshabilita botones
        $scope.disableButtons(true, '.opt-plan-'+plan_id);

        Notification('Procesando...');
        PlansService.changeStatus(plan_id, ACTIVE)
            .then(function(success){
                Notification.success("Habilitado con éxito");
                //TODO: debe actualizarse solo dicha fila de la tabla
                $scope.getPlans();
            },
            function(error){
                Notification.error("Error al Habilitar Plan: " + error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-plan-'+plan_id);
            }
        );
    };


    /**
     * 
     * @param {int} plan_id ID del plan
     */
    $scope.disablePlan = function(plan_id){

        var message = "Las materias asociadas a dicho periodo no estarán disponibles ¿Desea continuar?";
        if( !$scope.confirm(message) )
            return;

        //Deshabilita botones
        $scope.disableButtons(true, '.opt-plan-'+plan_id);

        Notification('Procesando...');
        PlansService.changeStatus(plan_id, DISABLED)
            .then(function(success){
                Notification.success("Deshabilitado con éxito");
                $scope.getPlans();
            },
            function(error){
                Notification.error("Error al deshabilitar plan: " + error.data);
                //Habilita botones
                $scope.disableButtons(false, '.opt-plan-'+plan_id);
            }
        );
    };

    (function(){
        //Se carguen datos al iniciar pagina
        $scope.getPlans();
    })();

});