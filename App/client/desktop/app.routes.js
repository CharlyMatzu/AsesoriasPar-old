app.config( function ($routeProvider) {
    $routeProvider
        
        .when("/asesorias", {
            controller: "AdvisoriesController",
            templateUrl: "app/components/advisories/advisoriesView.html"
        })
        .when("/horario", {
            controller: "ScheduleController",
            templateUrl: "app/components/schedule/scheduleView.html"
        })
        .when("/horario/actualizar", {
            controller: "HoursAndDaysController",
            templateUrl: "app/components/schedule/hoursAndDaysView.html"
        })
        .otherwise("/asesorias");
});
