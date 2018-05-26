app.config( function ($routeProvider) {
    $routeProvider
        .when("/", {
            controller: "",
            templateUrl: "app/components/home/homeView.html"
        })
        .when("/signin", {
            controller: "",
            templateUrl: "app/components/home/signinView.html"
        })
        // .when("/users", {
        //     controller: "UsersController",
        //     templateUrl: "app/components/users/usersView.html"
        // })
        .otherwise("/");
});
