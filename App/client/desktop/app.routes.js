angular.module("Desktop")

    .config( function ($routeProvider) {
        $routeProvider
            
            .when("/escritorio/:route", {
                controller: "DesktopController",
                templateUrl: "app/components/desktop/desktopView.html"
            })
            .when("/perfil", {
                controller: "ProfileController",
                templateUrl: "app/components/profile/profileView.html"
            })
            .otherwise("/escritorio/asesorias");
    })