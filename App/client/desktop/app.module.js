var app = angular.module("Desktop", ['ngRoute', 'ui-notification', 'LocalStorageModule']);

    app.run(function($rootScope, $window, localStorageService){
        // //TODO: metodo para verificar si esta logeado


        // //-STATUS VARIABLES
        // $rootScope.alert = {
        //     type: "",
        //     status: false,
        //     message: "",
        // };
        // $rootScope.loading = {
        //     status: false,
        //     message: "",
        // };
        
        // $rootScope.showUpdateForm = false;
        // $rootScope.showCreateForm = false;
        // $rootScope.showModalForm = false;


        // //-----------FUNCIONES GLOBALES
        // /**
        //  * Funcion para habilitar/deshabilitar los botones de un elemento para que no se presionen de nuevo
        //  * @param {bool} disabled TRUE para deshabilitar, FALSE para habilitar
        //  * @param {String} btnClass clase de referencia de botones a deshabilitar/habilitar
        //  */
        // $rootScope.disableButtons = function(disabled, btnClass){
        //     $(btnClass).each(function(){
        //         //console.log("Elemento: "+$(this).text() );
        //         $(this).prop('disabled', disabled);
        //     });
        // }
        
    });
