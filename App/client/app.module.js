var app = angular.module("AsesoriasPar", ['ngRoute', 'ui-notification']);

    app.run(function($rootScope){
        //TODO: metodo para verificar si esta logeado

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
        
        $rootScope.showUpdateForm = false;
        $rootScope.showCreateForm = false;
        $rootScope.showModalForm = false;


        //-----------FUNCIONES GLOBALES
        /**
         * Funcion para habilitar/deshabilitar los botones de un elemento para que no se presionen de nuevo
         * @param {bool} disabled TRUE para deshabilitar, FALSE para habilitar
         * @param {String} btnClass clase de referencia de botones a deshabilitar/habilitar
         */
        $rootScope.disableButtons = function(disabled, btnClass){
            $(btnClass).each(function(){
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