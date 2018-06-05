var app = angular.module("LoginApp", ['ngRoute', 'ui-notification', 'LocalStorageModule']);

    app.run(function($rootScope, $window, $timeout, localStorageService){
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
            localStorageService.remove('user-id');
            localStorageService.remove('user-role');
            localStorageService.remove('user-token');
            goToSignin();
        }

        $rootScope.signIn = function(id, role, token){
            //Borra datos del local storage
            localStorageService.set( 'user-id', id );
            localStorageService.set( 'user-role', role );
            localStorageService.set( 'user-token', token );

            $timeout(function(){
                $window.location.href = 'dashboard/';
            }, 2000);
        }


        $rootScope.isLogged = function(){
            if( localStorageService.get( 'user-token' ) ){
                //Si existe, se inicializa lista con lo que esta guardado
                $scope.user = localStorageService.get( 'user-token' );
                //Se verifica token en servidor
            }
            else{
                //Si no hay, redirecciona
                $window.location.href = '#!/signin';
            }
        }


        //Checa login
        //$rootScope.isLogged();
        
    });