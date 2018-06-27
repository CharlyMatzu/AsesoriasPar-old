angular.module("Dashboard")
    .controller('EmailController', function($scope, STATUS){

        $scope.page.title = "Email app";

        // $scope.loading = true;
        // $scope.selectedEmails = [];

        // $scope.selectetUser = function(email){
        //     $scope.selectedEmails.push( email );
        // };

        // // $scope.removeUser = function(email){
        // //     $scope.selectedEmails.push( email )
        // // }

        // var searchUsers = function(data){
        //     EmailService.searchUsers(data)
        //         .then(function(success){

        //         }, function(error){

        //         });
        // };

        // var getUsers = function(){
        //     EmailService.getUsers()
        //         .then(function(success){

        //         }, function(error){

        //         });
        // };

        // var sendEmail = function(data){
        //     EmailService.sendEmail(mail, $scope.selectedEmails)
        //         .then(function(success){

        //         }, function(error){

        //         });
        // };


    });