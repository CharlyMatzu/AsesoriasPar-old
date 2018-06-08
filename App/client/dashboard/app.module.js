var app = angular.module("AsesoriasPar", ['ngRoute', 'ui-notification', 'LocalStorageModule']);

app.run(function($rootScope, $window, localStorageService){
    
    $rootScope.user = {};
    $rootScope.token = {};
    
    //Verifica la sesion
    (function(){
        if( localStorageService.get('user') ){
            var data = localStorageService.get('user');
            data = JSON.parse( data );
            //Se verifica rol y se redirecciona
            if( data.user.role === 'basic' )
                $window.location.href = "/desktop";
            else{
                //Obtiene datos de estudiante
                $rootScope.user = data.user;
                $rootScope.token = data.token;
            }
        }
        else
            $window.location.href = "/";
    })();

    $rootScope.signOut = function(){
        localStorageService.remove('user');
        $window.location.href = "/";
    }



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

app.factory("RequestFactory", function() {
    // var url = "http://api.ronintopics.com";
    var url = "http://api.asesoriaspar.com";

    return {
        getURL: function() {
            return url+'/index.php';
        },
        getBaseURL: function() {
            return url;
        }
    };
});