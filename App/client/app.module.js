var app = angular.module("AsesoriasPar", ['ngRoute', 'ui-notification']);

    app.run(function($rootScope){

        //-----------VARIABLES GLOBALES
        $rootScope.page = {
            title: "PAGE TITLE"
        };

        //-STATUS VARIABLES
        $rootScope.alert = {
            type: "",
            status: false,
            message: "",
        };
        $rootScope.loading = {
            status: false,
            message: "",
        };
        // $rootScope.success = {
        //     status: false,
        //     message: "",
        // };
        // $rootScope.error = {
        //     status: false,
        //     message: "",
        // };
        // $rootScope.warning = {
        //     status: false,
        //     message: "",
        // };
        
        $rootScope.showForm = false;


        //-----------FUNCIONES GLOBALES
        /**
         * Funcion para habilitar/deshabilitar los botones de un elemento para que no se presionen de nuevo
         * @param {bool} disabled TRUE para deshabilitar, FALSE para habilitar
         * @param {int} id id del usuario al cual hacen referencia los botones de la tabla
         */
        $rootScope.disableButtons = function(disabled, id){
            $('.opt-user-'+id).each(function(){
                //console.log("Elemento: "+$(this).text() );
                $(this).prop('disabled', disabled);
            });
        }
        
    });





    // .controller('MainController', function($scope, $http){
    //     $scope.page.title = "MAIN";
    // });

    //-------------Angular Notifications
    // app .config(function(NotificationProvider) {
    //     NotificationProvider.setOptions({
    //         delay: 2000,
    //         startTop: 20,
    //         startRight: 10,
    //         verticalSpacing: 20,
    //         horizontalSpacing: 20,
    //         positionX: 'left',
    //         positionY: 'bottom'
    //     });
    // });