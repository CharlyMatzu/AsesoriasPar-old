app.controller('SignupController', function($scope, SignupService){

    $scope.title = "FORMULARIO";

    $scope.signup = function(){
        console.log( $scope.student.first_name );
    }

});