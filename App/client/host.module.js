angular.module("HostModule", [])

    // .constant('AUTH_EVENTS', {
    //     loginSuccess: 'auth-login-success',
    //     loginFailed: 'auth-login-failed',
    //     logoutSuccess: 'auth-logout-success',
    //     sessionTimeout: 'auth-session-timeout',
    //     notAuthenticated: 'auth-not-authenticated',
    //     notAuthorized: 'auth-not-authorized'
    // })

    .constant('STATUS',{
        OK: 200,
        CREATED: 201,
        NO_CONTENT: 204,

        BAD_REQUEST: 400,
        UNAUTHORIZED: 401,
        FORBIDDEN: 403,
        NOT_FOUND: 404,
        CONFLICT: 409,

        INTERNAL: 500
    })




    .factory('RequestFactory', function($http){

        var DEVELOPMENT = "http://192.168.1.72/AsesoriasPar-Web/App/server"
        var PRODUCTION = "http://api.ronintopics.com";
        var DEVELOP_MODE = false;
            
            
        var getServerURL = function(){
            if( DEVELOP_MODE === true )
                return DEVELOPMENT;
            else
                return PRODUCTION;
        };

        return {
            getURL: function() {
                return getServerURL()+'/index.php';
            },

            // getBaseURL: function() {
            //     return getServerURL();
            // },

            /**
             * @param {String} method   GET, POST, PUT, PATCH, DELETE
             * @param {String} route    /route/param
             * @param {String} data     Informacion en formato JSON
             */
            makeRequest: function(method, route, data){
                
                //Retorna promesa
                return $http({
                    method: method,
                    url: this.getURL()+route,
                    data: data
                });
            },

            /**
             * @param {String} method   GET, POST, PUT, PATCH, DELETE
             * @param {String} route    /route/param
             * @param {String} data     Informacion en formato JSON
             * @param {boolean} token   Para utilizar o no el token
             */
            makeTokenRequest: function(method, route, data, token){
                if( token == null )
                    token = "";

                //Retorna promesa
                return $http({
                    method: method,
                    url: this.getURL()+route,
                    data: data,
                    headers: {'Authorization': 'Bearer ' + token}
                });
            },
        };
            
    })

    



// angular.module("HostModule", [])
//     //CONSTANTES
//     // .constant('SERVERS',
//     //     {  
//     //         DEVELOPMENT:    "http://api.asesoriaspar.com",
//     //         PRODUCTION:     "http://asesoriaspar.ronintopics.com",
//     //         DEVELOP_MODE:    true
//     //     }
//     // )

//     .provider('HostProvider', function(){
//         var DEVELOPMENT = "http://api.asesoriaspar.com";
//         var PRODUCTION = "http://asesoriaspar.ronintopics.com";
//         var DEVELOP_MODE = true;


//         //--------------------------Parte del provider
//         this.setProductionUrl = function(prodUrl){
//             PRODUCTION = prodUrl;
//         };

//         this.setDevelopmentUrl = function(devUrl){
//             DEVELOPMENT = devUrl;
//         };

//         /**
//          * 
//          * @param {boolean} flag 
//          */
//         this.setProductionMode = function(){
//             DEVELOP_MODE = false;
//         };

        
//         // this.getURL = function(){
//         //     if( DEVELOP_MODE === true )
//         //         return DEVELOPMENT;
//         //     else
//         //         return PRODUCTION;
//         // };

//         //--------------------------Parte del factory
//         this.$get = function(){
//             return {
//                 getURL: function() {
//                     if( DEVELOP_MODE === true )
//                         return DEVELOPMENT+"/index.php";
//                     else
//                         return PRODUCTION+"/index.php";
//                 }
//             };
//         };
//     })



