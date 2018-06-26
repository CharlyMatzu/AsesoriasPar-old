angular.module("Desktop")

    .config( function ($routeProvider) {
        $routeProvider
            
            .when("/loading", {
                template: null,
                controller: "InitController"
            })

            //Parametro opcional con signo ?
            .when("/escritorio/:route?", {
                controller: "DesktopController",
                templateUrl: "app/components/desktop/desktopView.html"
            })
            
            .when("/perfil", {
                controller: "ProfileController",
                templateUrl: "app/components/profile/profileView.html"
            })

            .otherwise("/loading");
    })