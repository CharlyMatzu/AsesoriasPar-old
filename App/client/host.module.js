angular.module("HostModule", [])
    .factory('RequestFactory', function(){

        // var DEVELOPMENT = "http://api.asesoriaspar.com";
        var DEVELOPMENT = "http://192.168.1.72/AsesoriasPar-Web/App/server"
        var PRODUCTION = "http://asesoriaspar.ronintopics.com";
        var DEVELOP_MODE = true;
            
            
        var getServerURL = function(){
            if( DEVELOP_MODE == true )
                return DEVELOPMENT;
            else
                return PRODUCTION;
        };

        return {
            getURL: function() {
                return getServerURL()+'/index.php';
            }
        };
            
    });

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



