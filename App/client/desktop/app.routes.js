angular.module("Desktop")

    .config( function ($routeProvider) {
        $routeProvider
            
            .when("/asesorias", {
                controller: "AdvisoriesController",
                templateUrl: "app/components/advisories/advisoriesView.html"
            })
            .when("/horario", {
                controller: "ScheduleController",
                templateUrl: "app/components/schedule/scheduleView.html"
            })
            // .when("/perfil", {
            //     controller: "ScheduleController",
            //     templateUrl: "app/components/schedule/scheduleView.html"
            // })
            .otherwise("/asesorias");
    })