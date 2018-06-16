angular.module("LoginApp", ['ngRoute', 'ui-notification', 'HostModule', 'AuthModule'])


    .run(function($rootScope, $window, $timeout, AuthFactory){

        //metodo para verificar si esta logeado, se ejecuta primero
        (function(){

            if( AuthFactory.isAuthenticated() ){
                if( AuthFactory.isStudent() )
                    $window.location = "desktop";
                else if( AuthFactory.isStaff() )
                    $window.location = "dashboard";
                // else
                //     $window.location = "errorPage";
            }
            else
                console.log( "No Autenticado" );

        })();

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

        
    });
