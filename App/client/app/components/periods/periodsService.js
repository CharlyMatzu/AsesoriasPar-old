
app.service('PeriodsService', function($http){
    // this.data = [];
    this.getPeriods = function(callback){
        $http({
            method: 'GET',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/periods"
        }).then(function(success){
            var data = success.data;
            console.log( data );
            callback(data);
        }, function(error){
            console.log( error );
            callback(data);
        });
    }

    this.addPeriod = function(callback, period){
        $http({
            method: 'POST',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/periods",
            data: {
                start: period.start,
                end: period.end
            }
        }).then(function(success){
            console.log( success );
            callback(success) 
        }, function(error){
            console.log( error );
            callback(error)
        });
    }

    this.updatePeriod = function(callback, period){
        $http({
            method: 'PUT',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/periods/"+period.id,
            data: {
                start: period.start,
                end: period.end
            }
        }).then(function (success){
            console.log( success );
            callback(success);
        },function (error){
            console.log( error );
            callback(error);
        });
    }
    
    this.updateStatusPeriod = function(callback, period){
        $http({
            method: 'PATCH',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/periods/"+period.id+"/status/"+period.status
        }).then(function (success){
            console.log( success );
            callback(success);
        },function (error){
            console.log( error );
            callback(error);
        });
    }

    this.deletePeriod = function(callback, period_id){
        $http({
            method: 'DELETE',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/periods/"+period_id
        }).then(function (success){
            // console.log( response.data.message );
            callback(success);
        },function (error){
            // console.log( response.data.message );
            callback(error);
        });
    }
    
    

});