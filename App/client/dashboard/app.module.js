angular.module("Dashboard", ['ngRoute', 'ui-notification', 'LocalStorageModule', 'HostModule', 'AuthModule', 'angularTrix']);

angular.module("Dashboard")
    //Cuando iniciar el modulo
    .run(function($rootScope, $window, AuthFactory){
        
        $rootScope.session = {};
        $rootScope.token = {};

        //-----------VARIABLES GLOBALES
        $rootScope.page = {
            title: "PAGE TITLE"
        };

        //User
        $rootScope.session = {};

        //-STATUS VARIABLES
        $rootScope.alert = {
            type: "",
            status: false,
            message: "",
        };
        
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
        };

        $rootScope.signOut = function(){
            AuthFactory.removeSession();
            $window.location.href = "/";
        };


        //Verifica la sesion
        (function(){
            if( AuthFactory.isAuthenticated() ){
                if( !AuthFactory.isStaff() )
                    $window.location.href = "/";
                // Se obtiene informacion del usuario
                else
                    $rootScope.session = AuthFactory.getUser();
            }
            else{
                $window.location.href = "/";
            }
        })();


        
        
    })


    .controller('SignoutController', function($scope){

        (function(){
            $scope.signOut();
        })();
    
    });