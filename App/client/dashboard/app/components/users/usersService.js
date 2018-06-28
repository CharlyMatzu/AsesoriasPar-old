angular.module("Dashboard").service('UsersService', function( RequestFactory, AuthFactory ){
    
    this.getUsers = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            '/users/staff',
            null,
            AuthFactory.getToken()
        );
    }

    this.searchUsers = function(data){
        return RequestFactory.makeTokenRequest(
            'GET',
            '/users/search/'+data+'/staff',
            null,
            AuthFactory.getToken()
        );
    }

    this.updateUser = function(user){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/users/"+user.id,
            data = {
                email: user.email,
                password: user.pass,
                role: user.role
            },
            AuthFactory.getToken()
        );
    }

    this.changeStatus = function(user_id, status){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/users/"+user_id+"/status/"+status,
            null,
            AuthFactory.getToken()
        );
    }

    this.updateUserPassword = function(user_id, pass){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/users/"+user_id+"/password",
            data = {
                password: pass
            },
            AuthFactory.getToken()
        );
    }
    
    this.deleteUser = function(user_id){
        return RequestFactory.makeTokenRequest(
            'DELETE',
            "/users/"+user_id,
            null,
            AuthFactory.getToken()
        );
    }
    
    

});