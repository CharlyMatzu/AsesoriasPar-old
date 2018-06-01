app.config( function ($routeProvider) {
    $routeProvider
        
        .when("/", {
            controller: "HomeController",
            templateUrl: "app/components/home/homeView.html"
        })

        .when("/periodos", {
            controller: "PeriodsController",
            templateUrl: "app/components/periods/periodsView.html"
        })

        .when("/planes", {
            controller: "PlansController",
            templateUrl: "app/components/plans/plansView.html"
        })

        .when("/carreras", {
            controller: "CareersController",
            templateUrl: "app/components/careers/careersView.html"
        })


        //--------------USUARIOS (STAFF)
        .when("/usuarios", {
            controller: "UsersController",
            templateUrl: "app/components/users/usersView.html"
        })
        .when("/usuarios/nuevo", {
            controller: "NewUserController",
            templateUrl: "app/components/users/newUser/newUserView.html"
        })
        .otherwise("/");
});
