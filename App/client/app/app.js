var app = angular.module("AsesoriasPar", ['ngRoute', 'LocalStorageModule']);

app.config( function ($routeProvider) {
    $routeProvider
        .when("/", {
            controller: "",
            templateUrl: "template/home.html"
        })
        .otherwise("/");
});