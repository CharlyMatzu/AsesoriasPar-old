var app = angular.module("AsesoriasPar", ['ngRoute', 'ui-notification', 'LocalStorageModule']);

    app.run(function($rootScope, $window, localStorageService){
        //TODO: metodo para verificar si esta logeado

        //-----------VARIABLES GLOBALES
        $rootScope.page = {
            title: "PAGE TITLE"
        };

        //User
        $rootScope.user = {},

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


        /**
         * 
         */
        $rootScope.signOut = function(){
            //Borra datos del local storage
            localStorageService.set( 'user', null );
            goToSignin();
        }


        $rootScope.isLogged = function(){
            if( localStorageService.get( 'user' ) ){
                //Si existe, se inicializa lista con lo que esta guardado
                $scope.user = localStorageService.get( 'user' );
                //Se verifica token en servidor
            }
            else{
                //Si no hay, redirecciona
                goToSignin();
            }
        }

        var goToSignin = function(){
            $window.location.href = '#!/signin';
        }

        //Checa login
        //$rootScope.isLogged();
        
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