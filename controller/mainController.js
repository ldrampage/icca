

var app = angular.module('myApp', ['ngRoute','ngStorage','ui.bootstrap']);



app.config(['$routeProvider', function($routeProvider){
                $routeProvider
                .when('/',{templateUrl:'views/dashboard/view.html'})
                .when('/computers',{template:'This is the default Route'})
                .when('/printers',{template:'This is the printers Route'})
                .when('/login',{templateUrl:'views/login/login.php', controller: 'loginController'})
                .when('/user',{templateUrl:'views/user/index.php', controller: 'userController'})
                .when('/suppliers/',{templateUrl:'views/suppliers/index.php', controller: 'supplierController'})
                .when('/suppliers/view/:id',{templateUrl:'views/suppliers/view.php', controller: 'supplierController'})
                .when('/clients/',{templateUrl:'views/clients/index.php', controller: 'clientController'})
                .when('/clients/view/:id',{templateUrl:'views/clients/view.php', controller: 'clientController'})
                .when('/clients/aggregates',{templateUrl:'views/aggregates/clients.php', controller: 'aggreagatesController'})
                .when('/suppliers/aggregates',{templateUrl:'views/aggregates/supplier.php', controller: 'aggreagatesController'})
                .when('/employees',{templateUrl:'views/employees/index.php', controller: 'employeesController'})
                .when('/truck',{templateUrl:'views/truck/index.php', controller: 'truckController'})
                .when('/trips',{templateUrl:'views/trips/index.php', controller: 'tripController'})
                .when('/receivable/billings',{templateUrl:'views/receivables/index.php', controller: 'cbillingController'})
                .when('/receivable/billings/view/:id',{templateUrl:'views/receivables/view.php', controller: 'cbillingController'})
                .when('/receivable/billings/print/:id',{templateUrl:'views/receivables/print.php', controller: 'cbillingController'})
                .otherwise({redirectTo:'/'});
            }]);


app.directive('a', function() {
    return {
        restrict: 'E',
        link: function(scope, elem, attrs) {
            if(attrs.ngClick || attrs.href === '' || attrs.href === '#'){
                elem.on('click', function(e){
                    e.preventDefault();
                });
            }
        }
   };
});









// app.config(function($routeProvider, $locationProvider) {
//   $routeProvider
//    .when('/book/:bookId', {
//     templateUrl: 'book.html',
//     controller: 'BookController',
//     resolve: {
//       // I will cause a 1 second delay
//       delay: function($q, $timeout) {
//         var delay = $q.defer();
//         $timeout(delay.resolve, 1000);
//         return delay.promise;
//       }
//     }
//   })
//   .when('/Book/:bookId/ch/:chapterId', {
//     templateUrl: 'chapter.html',
//     controller: 'ChapterController'
//   })
//   .when('/computers', {
//     templateUrl: 'pages/forms/editors.html',
//     controller: 'mainController'
//   });

//   // configure html5 to get links working on jsfiddle
//   $locationProvider.html5Mode(true);
// });


app.controller('mainController', function($scope, $http, $location, $sessionStorage) {
    // more angular JS codes will be here

    $scope.mainURL = "http://localhost/trucking";
    $scope.currentUrl = $location.path();
    $scope.titlePage = "Dashboard";
    // var checkSession = $http({
    //     method: "post",
    //     url: $scope.mainURL + "/api/index.php",
    //     data: {model: 'user', method: 'check', keys: {}, conditions:{}},
    //     headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'} // { 'Content-Type': 'application/x-www-form-urlencoded' }
    // });

    $scope.displayOffice = false;
    //sessionStorage.user = JSON.stringify('1');
     if(typeof sessionStorage.user === 'undefined'){
        sessionStorage.user = null;
     }
    

     $scope.removeModal = function(){
        //$('div.modal-backdrop').remove();
        location.reload();
        //alert(1);
     }

     $scope.datefrom = "";
     $scope.dateto = "";
    //alert(x.timestamp);
    $scope.$on('$routeChangeStart', function(next, current) { 
        $scope.currentUrl = $location.path();
        if($scope.currentUrl.indexOf("login")!=-1){
            $scope.displayOffice = false;
        }else{
            $scope.displayOffice = true;
        }
        if($scope.currentUrl=="/user"){
            $scope.titlePage = "User";
        }else if($scope.currentUrl=="/login"){
            $scope.titlePage = "Login";
        }
         //$scope.user = JSON.parse(sessionStorage.user);
         //alert($scope.user);
         //sessionStorage.removeItem('user');
        if(typeof sessionStorage.user === 'undefined' || sessionStorage.user == null || sessionStorage.user == 'null'){
            $scope.displayOffice = false;
            window.location.href = "#/login";
         }else{
            //var milliseconds = new Date().getTime();
            var sessionUser = JSON.parse(sessionStorage.user);
            //alert(sessionUser);
            var currentTime = sessionUser.timestamp;
            var expiry = (60000 * 5) + currentTime;
            sessionStorage.user = JSON.stringify({id: sessionUser.id, name: sessionUser.name, designation: sessionUser.designation, user: sessionUser.user, tps: sessionUser.tps ,timestamp: expiry});
            if($scope.currentUrl.indexOf("login")!=-1){       
                window.location.href = $scope.mainURL;
            }
        }

    });

     $scope.$watch(function () {
           return sessionStorage.user;
        }, function (newVal, oldVal) {
           var sessionUserIdentity = JSON.parse(sessionStorage.user);
           if(sessionUserIdentity!=null){
               $scope.userCompleteName = sessionUserIdentity.name;
               $scope.userName = sessionUserIdentity.user;
               $scope.userDesignation= sessionUserIdentity.designation;
               $scope.userTps= sessionUserIdentity.tps;
           }else{
                $scope.userCompleteName = "";
               $scope.userName = "";
               $scope.userDesignation= "";
               $scope.userTps= 3;
           }
           //alert(sessionUserIdentity.name);      
    }, true);

    
     
   

    // var checkUser = $http({
    //     method: "post",
    //     url: $scope.mainURL + "/api/index.php",
    //     data: {model: 'user', method: 'get', keys: {}, conditions:{}, url: $scope.mainURL+ "/api/index.php"},
    //     headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    // });
    // checkUser.success(function (data) {
    //     console.log(data);
    // });



     
   


    $scope.showCreateForm = function(){
    // clear form
    $scope.clearForm();
     
    // change modal title
    $('#modal-product-title').text("Create New Product");
     
    // hide update product button
    $('#btn-update-product').hide();
     
    // show create product button
    $('#btn-create-product').show();
     
}


// clear variable / form values
$scope.clearForm = function(){
    $scope.id = "";
    $scope.name = "";
    $scope.description = "";
    $scope.price = "";
}


// create new product 
$scope.createProduct = function(){
         
    // fields in key-value pairs
    $http.post('api/api.php', {
            'name' : $scope.name, 
            'description' : $scope.description, 
            'price' : $scope.price
        }
    ).success(function (data, status, headers, config) {
        console.log(data);
        // tell the user new product was created
        Materialize.toast(data, 4000);
         
        // close modal
        $('#modal-product-form').closeModal();
         
        // clear modal content
        $scope.clearForm();
         
        // refresh the list
        $scope.getAll();
    });
}


});

app.controller('headerController', function($scope, $http, $location, $sessionStorage) {
   
   var sessionLogout = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: {model: 'user', method: 'logout', keys: {}, conditions:{}},
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });


    $scope.logout = function(){
        //sessionStorage.user = JSON.stringify({id: 12});
        sessionStorage.removeItem('user');
        window.location = '#/login';
    }

});

app.controller('loginController', function($scope, $http, $location, $sessionStorage) {
    $scope.login_user = null;
    $scope.login_pass = null;


    $scope.fname = null;
    $scope.mname = null;
    $scope.lname = null;
    $scope.secuser = null;
    $scope.secpass = null;
    $scope.email = null;
    $scope.number = null;
    $scope.designation = null;
    $scope.type = null;
    $scope.recordstate = null;
    $scope.state = null;


    $scope.submitForm = function(){
        var loginUser = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: { method: 'login', inputs: { 'secuser': $scope.login_user, 'secpass': sha256_digest($scope.login_pass)}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        loginUser.success(function (data) {
            console.log(data);
            //alert("-"+data+'-');
            var fetchData = data[0].data[0];
            var currentStamp = new Date().getTime();
            //alert(data[0].data[0].id)
            sessionStorage.user = JSON.stringify({id: fetchData.id, name: fetchData.user_Fname+' '+fetchData.user_Lname, designation: fetchData.user_Designation, user: fetchData.user_Secuser, timestamp: currentStamp, tps: fetchData.user_Type});
            window.location = $scope.mainURL;
        });
    }


    


     $scope.register = function(){
         /**
         * sample add post
         * */
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'user', method: 'insert', inputs: { fname: $scope.fname, mname: $scope.mname, lname: $scope.lname, secuser: $scope.secuser, secpass: sha256_digest($scope.secpass), email: $scope.email, number: $scope.number, designation: $scope.designation, type: $scope.type, recordstate: $scope.recordstate, state: $scope.state}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
        }); 
    }

  

});



app.controller('userController', function($scope, $http, $location, $sessionStorage) {
   


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */

    


    $scope.fname = null;
    $scope.mname = null;
    $scope.lname = null;
    $scope.secuser = null;
    $scope.secpass = null;
    $scope.email = null;
    $scope.number = null;
    $scope.designation = null;
    $scope.type = null;
    $scope.recordstate = null;
    $scope.state = null;

    $scope.target = 0;
    $scope.listIndex = null;


 $scope.setValue = function(data, index){
        $scope.target = data;
        $scope.listIndex = index;
        //alert(index);
   }

    
   $scope.goSet = function(index = null){
         if(index==null){ index=$scope.listIndex; }
          $scope.fname = $scope.userList[index].user_Fname;
            $scope.mname = $scope.userList[index].user_Mname;
            $scope.lname = $scope.userList[index].user_Lname;
            $scope.secuser = $scope.userList[index].user_Secuser;
            $scope.secpass = $scope.userList[index].user_Secpass;
            $scope.email = $scope.userList[index].user_Email;
            $scope.number = $scope.userList[index].user_Number;
            $scope.designation = $scope.userList[index].user_Designation;
            $scope.type = $scope.userList[index].user_Type;

   }

   $scope.clearForm = function(){
    $scope.target = 0;
    $scope.listIndex = null;
    $scope.fname = "";
    $scope.mname = "";
    $scope.lname = "";
    $scope.secuser = "";
    $scope.secpass = "";
    $scope.email = "";
    $scope.number = "";
    $scope.designation = "";
    $scope.type = "";
    $scope.recordstate = "";
    $scope.state = "";
   }

 $scope.retrieveUsers = function(){
    var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: {model: 'user', method: 'get', keys: {}, conditions:{}},
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.userList = data.data;    
        console.log(data.data);
    });
}

 $scope.retrieveUsers();
 $scope.register = function(){
         /**
         * sample add post
         * */
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'user', method: 'insert', inputs: { fname: $scope.fname, mname: $scope.mname, lname: $scope.lname, secuser: $scope.secuser, secpass: sha256_digest($scope.secpass), email: $scope.email, number: $scope.number, designation: $scope.designation, type: $scope.type, recordstate: 1, state: 1}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            $scope.retrieveUsers();
            $('#closeCreate').trigger("click");
            $scope.removeModal();
        }); 
    }


  $scope.updateUser = function(){

        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'user', method: 'update', inputs: { fname: $scope.fname, mname: $scope.mname, lname: $scope.lname, secuser: $scope.secuser, secpass: sha256_digest($scope.secpass), email: $scope.email, number: $scope.number, designation: $scope.designation, type: $scope.type}, conditions:{id:$scope.target}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveUsers();
            $scope.clearForm();
            $('#closeUpdate').trigger("click");
            $scope.removeModal();
        });
    }  

});

app.controller('supplierController', function($scope, $http, $location, $sessionStorage, $routeParams) {
    $scope.currentHref = $location.path();


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */
     $scope.target = 0;
     $scope.targetPrice = 0;
     $scope.listIndex = null;
     $scope.listPriceIndex = null;
     $scope.clearForm = function(){
         $scope.supplier_code = '';
         $scope.supplier_trade = '';
         $scope.supplier_email = '';
         $scope.supplier_phone = '';
         $scope.supplier_landline = '';
         $scope.supplier_contactperson = '';
         $scope.supplier_address = '';
         $scope.fname = '';
    }
  

     /** for Date Picker **/
  $scope.today = function() {
    $scope.aggregate_date = new Date();
  };
  $scope.today2 = function() {
    $scope.dt2 = new Date();
  };
  $scope.today();
  $scope.today2();

  $scope.clear = function() {
    $scope.aggregate_date = null;
  };
  $scope.clear2 = function() {
    $scope.dt2 = null;
  };

  $scope.inlineOptions = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.inlineOptions2 = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.dateOptions2 = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  // Disable weekend selection
  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 7);
  }

  $scope.toggleMin = function() {
    $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
    $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
  };
  $scope.toggleMin2 = function() {
    $scope.inlineOptions2.minDate = $scope.inlineOptions2.minDate ? null : new Date();
    $scope.dateOptions2.minDate = $scope.inlineOptions2.minDate;
  };

  $scope.toggleMin();
  $scope.toggleMin2();

  $scope.open1 = function() {
    $scope.popup1.opened = true;
  };

  $scope.open2 = function() {
    $scope.popup2.opened = true;
  };

  $scope.setDate = function(year, month, day) {
    $scope.truck_date = new Date(year, month, day);
  };

  $scope.setDate2 = function(year, month, day) {
    $scope.dt2 = new Date(year, month, day);
  };

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[0];
  $scope.altInputFormats = ['M!/d!/yyyy'];

  $scope.popup1 = {
    opened: false
  };

  $scope.popup2 = {
    opened: false
  };

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 1);
  $scope.events = [
    {
      date: tomorrow,
      status: 'full'
    },
    {
      date: afterTomorrow,
      status: 'partially'
    }
  ];

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }

    return '';
  }

  /** End date picker**/


  $scope.retrieveRecord = function(id = null){
     var dataheader 
     if(id ==null){
        dataheader = {model: 'supplier', method: 'get', keys: {}, conditions:{}};
     }else{
        dataheader = {model: 'supplier', method: 'get', keys: {}, conditions:{id:id}};
     }
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.supplierList = data.data; 
        if(id!=null){ $scope.goSet(0); }   
        console.log(data.data);
    });
  }

  

  $scope.createSupplier = function(){
         /**
         * sample add post
         * */

        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'supplier', method: 'insert', inputs: {  code: $scope.supplier_code, trade: $scope.supplier_trade, email: $scope.supplier_email, phone: $scope.supplier_phone, landline: $scope.supplier_landline, contactperson: $scope.supplier_contactperson, address: $scope.supplier_address}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            setTimeout(function(){ 
                $scope.removeModal();
                $('.close').trigger("click");
            }, 2000);
            
            //
            console.log(data);
            $scope.retrieveRecord();
            $scope.clearForm();
            
            //$('#closeCreate').trigger("click");
        }); 
    }

    $scope.deleteSupplier = function(){
         //alert($scope.target);
         var deleteRequest = $http({
                method: "post",
                url: $scope.mainURL + "/api/index.php",
                data: {model: 'supplier', method: 'delete', conditions:{id:$scope.target}},
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
            });
            deleteRequest.success(function (data) {
                console.log(data);
                $("#closeDelete").trigger("click");
                $scope.removeModal();
                $scope.retrieveRecord();

            });
    }
    $scope.updateSupplier = function(){
        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'supplier', method: 'update', inputs: {code: $scope.supplier_code, trade: $scope.supplier_trade, email: $scope.supplier_email, phone: $scope.supplier_phone, landline: $scope.supplier_landline, contactperson: $scope.supplier_contactperson, address: $scope.supplier_address}, conditions:{id:$scope.target}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveRecord();
            $scope.clearForm();
            $('#closeCreateUpdate').trigger("click");
            $scope.removeModal();
        });
    }
    $scope.goView = function(){
        if($scope.listIndex!=null){
            window.location.href = "#/suppliers/view/"+$scope.target;
        }
    }
    $scope.resetIndex = function(){
        //$scope.listIndex = null;
        //$scope.target = false;
    }
    
   $scope.setValue = function(data, index){
        $scope.target = data;
        $scope.listIndex = index;
        //alert(index);
   }

    
    $scope.setPriceValue = function(data, index){
        $scope.targetPrice = data;
        $scope.listPriceIndex = index;
      }

    $scope.goSetPrice = function(index = null){
         if(index==null){ index=$scope.listPriceIndex; }
         $scope.aggregate_desc = $scope.aggregateList[index].aggregates_Description;
         $scope.aggregate_unit = $scope.aggregateList[index].aggregates_Unit;
         $scope.aggregate_price = $scope.aggregateList[index].aggregates_Price;
         $scope.aggregate_date = $scope.aggregateList[index].aggregates_Date;
         //$scope.fname = '';
   }


   $scope.goSet = function(index = null){
         if(index==null){ index=$scope.listIndex; }
         //alert(index);
         $scope.supplier_code = $scope.supplierList[index].supplier_Code;
         $scope.supplier_trade = $scope.supplierList[index].supplier_Trade;
         $scope.supplier_email = $scope.supplierList[index].supplier_Email;
         $scope.supplier_phone = $scope.supplierList[index].supplier_Phone;
         $scope.supplier_landline = $scope.supplierList[index].supplier_Landline;
         $scope.supplier_contactperson = $scope.supplierList[index].supplier_Contactperson;
         $scope.supplier_address = $scope.supplierList[index].supplier_Address;
         //$scope.fname = '';
   }

   $scope.clearPriceForm = function(){
         $scope.aggregate_desc = ""; 
         $scope.aggregate_unit = "";
         $scope.aggregate_price = "";
         $scope.aggregate_date = "";
    }

   $scope.retrieveAggregates = function(){
    //alert(1 );
     var dataheader  = {model: 'aggregates', method: 'get', keys: {}, conditions:{foreign:$routeParams.id+' (AND)', model: 'supplier'}};
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.aggregateList = data.data; 

        $scope.goSet(0); 
        // console.log("=============START");
        // console.log(data.data);
        // console.log("=============END");
        // alert(JSON.stringify($scope.aggregateList));
    });
  }

  $scope.updateAggregate = function(){

        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'aggregates', method: 'update', inputs: {description: $scope.aggregate_desc, unit: $scope.aggregate_unit, price: $scope.aggregate_price, date: $scope.aggregate_date}, conditions:{id:$scope.targetPrice}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveAggregates();
            $scope.clearForm();
            $scope.clearPriceForm();
            $scope.setPriceValue(null,0);
            $('#closeUpdatePrice').trigger("click");
            $scope.removeModal();
        });
    }

   $scope.createAggregate = function(){
         /**
         * sample add post
         * */
         
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'aggregates', method: 'insert', inputs: {  description: $scope.aggregate_desc, unit: $scope.aggregate_unit, price: $scope.aggregate_price, date: $scope.aggregate_date, model: 'supplier', foreign: $routeParams.id}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            $scope.retrieveAggregates();
            $scope.clearForm();
            $('.close').trigger("click");
            $scope.removeModal();
            

            //alert(1);
        }); 
    }



   if($scope.currentHref.indexOf("/suppliers/view/")!=-1){
        $scope.retrieveRecord($routeParams.id);
        $scope.retrieveAggregates();
        //alert($routeParams.id);
        //console.log('asdasdas');
   }else{
        $scope.clearForm();
        $scope.retrieveRecord();
        //alert(1);
   }

   //alert($routeParams.id);

});



app.controller('clientController', function($scope, $http, $location, $sessionStorage, $routeParams) {
    $scope.currentHref = $location.path();


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */
     $scope.target = 0;
     $scope.targetPrice = 0;
     $scope.listIndex = null;
     $scope.listPriceIndex = null;
     $scope.clearForm = function(){
         $scope.supplier_code = '';
         $scope.supplier_trade = '';
         $scope.supplier_email = '';
         $scope.supplier_phone = '';
         $scope.supplier_landline = '';
         $scope.supplier_contactperson = '';
         $scope.supplier_address = '';
         $scope.fname = '';
    }
  

    /** for Date Picker **/
  $scope.today = function() {
    $scope.aggregate_date = new Date();
  };
  $scope.today2 = function() {
    $scope.dt2 = new Date();
  };
  $scope.today();
  $scope.today2();

  $scope.clear = function() {
    $scope.aggregate_date = null;
  };
  $scope.clear2 = function() {
    $scope.dt2 = null;
  };

  $scope.inlineOptions = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.inlineOptions2 = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.dateOptions2 = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  // Disable weekend selection
  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 7);
  }

  $scope.toggleMin = function() {
    $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
    $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
  };
  $scope.toggleMin2 = function() {
    $scope.inlineOptions2.minDate = $scope.inlineOptions2.minDate ? null : new Date();
    $scope.dateOptions2.minDate = $scope.inlineOptions2.minDate;
  };

  $scope.toggleMin();
  $scope.toggleMin2();

  $scope.open1 = function() {
    $scope.popup1.opened = true;
  };

  $scope.open2 = function() {
    $scope.popup2.opened = true;
  };

  $scope.setDate = function(year, month, day) {
    $scope.truck_date = new Date(year, month, day);
  };

  $scope.setDate2 = function(year, month, day) {
    $scope.dt2 = new Date(year, month, day);
  };

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[0];
  $scope.altInputFormats = ['M!/d!/yyyy'];

  $scope.popup1 = {
    opened: false
  };

  $scope.popup2 = {
    opened: false
  };

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 1);
  $scope.events = [
    {
      date: tomorrow,
      status: 'full'
    },
    {
      date: afterTomorrow,
      status: 'partially'
    }
  ];

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }

    return '';
  }

  /** End date picker**/


  $scope.retrieveRecord = function(id = null){
     var dataheader 
     if(id ==null){
        dataheader = {model: 'clients', method: 'get', keys: {}, conditions:{}};
     }else{
        dataheader = {model: 'clients', method: 'get', keys: {}, conditions:{id:id}};
     }
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.supplierList = data.data; 
        if(id!=null){ $scope.goSet(0); }   
        console.log(data.data);
    });
  }

  

  $scope.createSupplier = function(){
         /**
         * sample add post
         * */

        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'clients', method: 'insert', inputs: {  code: $scope.supplier_code, trade: $scope.supplier_trade, email: $scope.supplier_email, phone: $scope.supplier_phone, landline: $scope.supplier_landline, contactperson: $scope.supplier_contactperson, address: $scope.supplier_address}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            $scope.retrieveRecord();
            $scope.clearForm();
            $('#closeCreate').trigger("click");
            $scope.removeModal();
        }); 
    }

    $scope.deleteSupplier = function(){
         alert($scope.target);
         var deleteRequest = $http({
                method: "post",
                url: $scope.mainURL + "/api/index.php",
                data: {model: 'clients', method: 'delete', conditions:{id:$scope.target}},
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
            });
            deleteRequest.success(function (data) {
                console.log(data);
                $("#closeDelete").trigger("click");
                $scope.removeModal();
                $scope.retrieveRecord();

            });
    }
    $scope.updateSupplier = function(){
        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'clients', method: 'update', inputs: {code: $scope.supplier_code, trade: $scope.supplier_trade, email: $scope.supplier_email, phone: $scope.supplier_phone, landline: $scope.supplier_landline, contactperson: $scope.supplier_contactperson, address: $scope.supplier_address}, conditions:{id:$scope.target}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveRecord();
            $scope.clearForm();
            $('#closeCreateUpdate').trigger("click");
            $scope.removeModal();
        });
    }
    $scope.goView = function(){
        if($scope.listIndex!=null){
            window.location.href = "#/clients/view/"+$scope.target;
        }
    }
    $scope.resetIndex = function(){
        //$scope.listIndex = null;
        //$scope.target = false;
    }
    
   $scope.setValue = function(data, index){
        $scope.target = data;
        $scope.listIndex = index;
        //alert(index);
   }

    
    $scope.setPriceValue = function(data, index){
        $scope.targetPrice = data;
        $scope.listPriceIndex = index;
      }

    $scope.goSetPrice = function(index = null){
         if(index==null){ index=$scope.listPriceIndex; }
         $scope.aggregate_desc = $scope.aggregateList[index].aggregates_Description;
         $scope.aggregate_unit = $scope.aggregateList[index].aggregates_Unit;
         $scope.aggregate_price = $scope.aggregateList[index].aggregates_Price;
         $scope.aggregate_date = $scope.aggregateList[index].aggregates_Date;
         //$scope.fname = '';
   }


   $scope.goSet = function(index = null){
         if(index==null){ index=$scope.listIndex; }
         //alert(index);
         $scope.supplier_code = $scope.supplierList[index].clients_Code;
         $scope.supplier_trade = $scope.supplierList[index].clients_Trade;
         $scope.supplier_email = $scope.supplierList[index].clients_Email;
         $scope.supplier_phone = $scope.supplierList[index].clients_Phone;
         $scope.supplier_landline = $scope.supplierList[index].clients_Landline;
         $scope.supplier_contactperson = $scope.supplierList[index].clients_Contactperson;
         $scope.supplier_address = $scope.supplierList[index].clients_Address;
         //$scope.fname = '';
   }

   $scope.clearPriceForm = function(){
         $scope.aggregate_desc = ""; 
         $scope.aggregate_unit = "";
         $scope.aggregate_price = "";
         $scope.aggregate_date = "";
    }

   $scope.retrieveAggregates = function(){
    //alert(1 );
     var dataheader  = {model: 'aggregates', method: 'get', keys: {}, conditions:{foreign:$routeParams.id+' (AND)', model: 'clients'}};
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.aggregateList = data.data; 

        $scope.goSet(0); 
        // console.log("=============START");
        // console.log(data.data);
        // console.log("=============END");
        // alert(JSON.stringify($scope.aggregateList));
    });
  }

  $scope.updateAggregate = function(){

        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'aggregates', method: 'update', inputs: {description: $scope.aggregate_desc, unit: $scope.aggregate_unit, price: $scope.aggregate_price, date: $scope.aggregate_date}, conditions:{id:$scope.targetPrice}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveAggregates();
            $scope.clearForm();
            $scope.clearPriceForm();
            $scope.setPriceValue(null,0);
            $('#closeUpdatePrice').trigger("click");
            $scope.removeModal();
        });
    }

   $scope.createAggregate = function(){
         /**
         * sample add post
         * */
         
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'aggregates', method: 'insert', inputs: {  description: $scope.aggregate_desc, unit: $scope.aggregate_unit, price: $scope.aggregate_price, date: $scope.aggregate_date, model: 'clients', foreign: $routeParams.id}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            $scope.retrieveAggregates();
            $scope.clearForm();
            $('#closeCreatePrice').trigger("click");
            $scope.removeModal();
        }); 
    }



   if($scope.currentHref.indexOf("/clients/view/")!=-1){
        $scope.retrieveRecord($routeParams.id);
        $scope.retrieveAggregates();
        //alert($routeParams.id);
        //console.log('asdasdas');
   }else{
        $scope.clearForm();
        $scope.retrieveRecord();
        //alert(1);
   }

   //alert($routeParams.id);

});


app.controller('aggreagatesController', function($scope, $http, $location, $sessionStorage, $routeParams) {
    $scope.currentHref = $location.path();


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */
     $scope.target = 0;
     $scope.targetPrice = 0;
     $scope.listIndex = null;
     $scope.listPriceIndex = null;
   
    if($scope.currentHref.indexOf("/suppliers/aggregates")!=-1){
        $scope.spa = "supplier"
    }else{
        $scope.spa = "clients"
    }
  
 
    $scope.goView = function(){
        if($scope.listIndex!=null){
            window.location.href = "#/clients/view/"+$scope.target;
        }
    }
    
 
    
    $scope.setPriceValue = function(data, index){
        $scope.targetPrice = data;
        $scope.listPriceIndex = index;
      }

    $scope.goSetPrice = function(index = null){
         if(index==null){ index=$scope.listPriceIndex; }
         $scope.aggregate_desc = $scope.aggregateList[index].aggregates_Description;
         $scope.aggregate_unit = $scope.aggregateList[index].aggregates_Unit;
         $scope.aggregate_price = $scope.aggregateList[index].aggregates_Price;
         $scope.aggregate_date = $scope.aggregateList[index].aggregates_Date;
         //$scope.fname = '';
   }



   $scope.clearPriceForm = function(){
         $scope.aggregate_desc = ""; 
         $scope.aggregate_unit = "";
         $scope.aggregate_price = "";
         $scope.aggregate_date = "";
    }

   $scope.retrieveAggregates = function(){
    //alert(1 );
     var dataheader  = {model: 'aggregates', method: 'get', keys: {}, conditions:{model: $scope.spa }, spa:$scope.spa };
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.aggregateList = data.data; 

        $scope.goSetPrice(0); 
        // console.log("=============START");
        // console.log(data.data);
        // console.log("=============END");
        // alert(JSON.stringify($scope.aggregateList));
    });
  }

  $scope.updateAggregate = function(){

        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'aggregates', method: 'update', inputs: {description: $scope.aggregate_desc, unit: $scope.aggregate_unit, price: $scope.aggregate_price, date: $scope.aggregate_date}, conditions:{id:$scope.targetPrice}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveAggregates();
            $scope.setPriceValue(null,0);
            $('#closeUpdatePrice').trigger("click");
            $scope.removeModal();
        });
    }

   $scope.createAggregate = function(){
         /**
         * sample add post
         * */
         
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'aggregates', method: 'insert', inputs: {  description: $scope.aggregate_desc, unit: $scope.aggregate_unit, price: $scope.aggregate_price, date: $scope.aggregate_date, model: 'clients', foreign: $routeParams.id}, spa: 'yes'},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            $scope.retrieveAggregates();
            $('#closeCreatePrice').trigger("click");
            $scope.removeModal();
        }); 
    }

    $scope.retrieveAggregates();
});



app.controller('employeesController', function($scope, $http, $location, $sessionStorage, $routeParams) {
    $scope.currentHref = $location.path();


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */
     $scope.target = 0;
     $scope.listIndex = null;
   
    if($scope.currentHref.indexOf("/employees/view")!=-1){
        $scope.spa = "supplier"
    }else{
        $scope.spa = "clients"
    }
  
 
    $scope.goView = function(){
        if($scope.listIndex!=null){
            window.location.href = "#/employees/view/"+$scope.target;
        }
    }
    
 
    
    $scope.setValue = function(data, index){
        $scope.targetPrice = data;
        $scope.listPriceIndex = index;
      }



   $scope.clearForm = function(){
         $scope.employee_fname = "";
         $scope.employee_mname = "";
         $scope.employee_lname = "";
         $scope.employee_address = "";
         $scope.employee_email = "";
         $scope.employee_start = "";
         $scope.employee_end = "";
         $scope.employee_category = "";
         $scope.employee_status = "";
    }

   $scope.retrieveEmployee = function(){
     var dataheader  = {model: 'employee', method: 'get', keys: {}, conditions:{} };
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.employeeList = data.data; 
        //$scope.setValue(0); 
    });
  }

  $scope.updateEmployee = function(){

        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'employee', method: 'update', inputs: {  fname: $scope.employee_fname, mname: $scope.employee_mname, lname: $scope.employee_lname, address: $scope.employee_address, phone: $scope.employee_phone, email: $scope.employee_email, start: $scope.employee_start, end: $scope.employee_end, category: $scope.employee_category, status: $scope.employee_status}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveAggregates();
            $scope.setPriceValue(null,0);
            $('#closeUpdatePrice').trigger("click");
            $scope.removeModal();
        });
    }

   $scope.createEmployee = function(){
         /**
         * sample add post
         * */
         
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'employee', method: 'insert', inputs: {  fname: $scope.employee_fname, mname: $scope.employee_mname, lname: $scope.employee_lname, address: $scope.employee_address, phone: $scope.employee_phone, email: $scope.employee_email, start: $scope.employee_start, end: $scope.employee_end, category: $scope.employee_category, status: $scope.employee_status}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            $scope.retrieveEmployee();
            $('#closeCreate').trigger("click");
            $scope.removeModal();
        }); 
    }
    $scope.goSetPrice = function(index = null){
         if(index==null){ index=$scope.listPriceIndex; }
         $scope.employee_fname = $scope.employeeList[index].employee_Fname;
         $scope.employee_mname = $scope.employeeList[index].employee_Mname;
         $scope.employee_lname = $scope.employeeList[index].employee_Lname;
         $scope.employee_address = $scope.employeeList[index].employee_Address;
         $scope.employee_email = $scope.employeeList[index].employee_Email;
         $scope.employee_start = $scope.employeeList[index].employee_Start;
         $scope.employee_end = $scope.employeeList[index].employee_End;
         $scope.employee_category = $scope.employeeList[index].employee_Category;
         $scope.employee_status = $scope.employeeList[index].employee_Status;
    }
    $scope.retrieveEmployee();
});



app.controller('truckController', function($scope, $http, $location, $sessionStorage, $routeParams) {
    $scope.currentHref = $location.path();


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */
     $scope.target = 0;
     $scope.listIndex = null;
   
    if($scope.currentHref.indexOf("/employees/view")!=-1){
        $scope.spa = "supplier"
    }else{
        $scope.spa = "clients"
    }
  

    /** for Date Picker **/
  $scope.today = function() {
    $scope.truck_date = new Date();
  };
  $scope.today2 = function() {
    $scope.dt2 = new Date();
  };
  $scope.today();
  $scope.today2();

  $scope.clear = function() {
    $scope.truck_date = null;
  };
  $scope.clear2 = function() {
    $scope.dt2 = null;
  };

  $scope.inlineOptions = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.inlineOptions2 = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.dateOptions2 = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  // Disable weekend selection
  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 7);
  }

  $scope.toggleMin = function() {
    $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
    $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
  };
  $scope.toggleMin2 = function() {
    $scope.inlineOptions2.minDate = $scope.inlineOptions2.minDate ? null : new Date();
    $scope.dateOptions2.minDate = $scope.inlineOptions2.minDate;
  };

  $scope.toggleMin();
  $scope.toggleMin2();

  $scope.open1 = function() {
    $scope.popup1.opened = true;
  };

  $scope.open2 = function() {
    $scope.popup2.opened = true;
  };

  $scope.setDate = function(year, month, day) {
    $scope.truck_date = new Date(year, month, day);
  };

  $scope.setDate2 = function(year, month, day) {
    $scope.dt2 = new Date(year, month, day);
  };

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[0];
  $scope.altInputFormats = ['M!/d!/yyyy'];

  $scope.popup1 = {
    opened: false
  };

  $scope.popup2 = {
    opened: false
  };

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 1);
  $scope.events = [
    {
      date: tomorrow,
      status: 'full'
    },
    {
      date: afterTomorrow,
      status: 'partially'
    }
  ];

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }

    return '';
  }

  /** End date picker**/

 
    $scope.goView = function(){
        if($scope.listIndex!=null){
            window.location.href = "#/employees/view/"+$scope.target;
        }
    }
    
 
    
    $scope.setValue = function(data, index){
        $scope.targetPrice = data;
        $scope.listPriceIndex = index;
      }



   $scope.clearForm = function(){
         $scope.truck_code = "";
         $scope.truck_plate = "";
         $scope.truck_driver = "";
         $scope.truck_helper = "";
         $scope.truck_acquired = "";
    }

   $scope.retrieveDriver = function(){
     var dataheader  = {model: 'employee', method: 'get', keys: {}, conditions:{category:'Driver'} };
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.driverList = data.data; 
        //$scope.setValue(0); 
    });
  }

  $scope.retrieveHelper = function(){
     var dataheader  = {model: 'employee', method: 'get', keys: {}, conditions:{category:'Helper'} };
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.helperList = data.data; 
        //$scope.setValue(0); 
    });
  }
  $scope.retrieveTruck = function(){
     var dataheader  = {model: 'truck', method: 'get', keys: {}, conditions:{}, spa:'yes' };
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.truckList = data.data; 
        //$scope.setValue(0); 
    });
  }

  $scope.updateTruck = function(){

        var updateRequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'truck', method: 'update', inputs: {  code: $scope.truck_code, plate: $scope.truck_plate, driver: $scope.truck_driver, helper: $scope.truck_helper, aquired: $scope.truck_acquired}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        updateRequest.success(function (data) {
            console.log(data);
            $scope.retrieveTruck();
            $scope.setPriceValue(null,0);
            $('#closeUpdatePrice').trigger("click");
            $scope.removeModal();
        });
    }

   $scope.createTruck = function(){
         /**
         * sample add post
         * */
         
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data: {model: 'truck', method: 'insert', inputs: {  code: $scope.truck_code, plate: $scope.truck_plate, driver: $scope.truck_driver, helper: $scope.truck_helper, aquired: $scope.truck_acquired}},
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            $scope.retrieveTruck();
            $('#closeCreate').trigger("click");
            $scope.removeModal();
        }); 
    }
    $scope.goSetPrice = function(index = null){
         if(index==null){ index=$scope.listPriceIndex; }
         $scope.truck_code = $scope.truckList[index].truck_Code;
         $scope.truck_plate = $scope.truckList[index].truck_Plate;
         $scope.truck_driver = $scope.truckList[index].truck_Driver;
         $scope.truck_helper = $scope.truckList[index].truck_Helper;
         $scope.truck_acquired = $scope.truckList[index].truck_Acquired;
    }
    $scope.retrieveDriver();
    $scope.retrieveHelper();
    $scope.retrieveTruck();
});



app.controller('tripController', function($scope, $http, $location, $sessionStorage, $routeParams) {
    $scope.currentHref = $location.path();


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */
     $scope.target = 0;
     $scope.listIndex = null;
     
    
     /** for Date Picker **/
  $scope.today = function() {
    $scope.dt = new Date();
  };
  $scope.today2 = function() {
    $scope.dt2 = new Date();
  };
  $scope.today();
  $scope.today2();

  $scope.clear = function() {
    $scope.dt = null;
  };
  $scope.clear2 = function() {
    $scope.dt2 = null;
  };

  $scope.inlineOptions = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.inlineOptions2 = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.dateOptions2 = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  // Disable weekend selection
  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 7);
  }

  $scope.toggleMin = function() {
    $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
    $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
  };
  $scope.toggleMin2 = function() {
    $scope.inlineOptions2.minDate = $scope.inlineOptions2.minDate ? null : new Date();
    $scope.dateOptions2.minDate = $scope.inlineOptions2.minDate;
  };

  $scope.toggleMin();
  $scope.toggleMin2();

  $scope.open1 = function() {
    $scope.popup1.opened = true;
  };

  $scope.open2 = function() {
    $scope.popup2.opened = true;
  };

  $scope.setDate = function(year, month, day) {
    $scope.dt = new Date(year, month, day);
  };

  $scope.setDate2 = function(year, month, day) {
    $scope.dt2 = new Date(year, month, day);
  };

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[0];
  $scope.altInputFormats = ['M!/d!/yyyy'];

  $scope.popup1 = {
    opened: false
  };

  $scope.popup2 = {
    opened: false
  };

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 1);
  $scope.events = [
    {
      date: tomorrow,
      status: 'full'
    },
    {
      date: afterTomorrow,
      status: 'partially'
    }
  ];

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }

    return '';
  }

  /** End date picker**/


   /** For Time **/


  $scope.trip_dispatch = new Date();

  $scope.hstep = 1;
  $scope.mstep = 1;

  $scope.options = {
    hstep: [1, 2, 3],
    mstep: [1, 5, 10, 15, 25, 30]
  };

  $scope.ismeridian = true;
  $scope.toggleMode = function() {
    $scope.ismeridian = ! $scope.ismeridian;
  };

  $scope.update = function() {
    var d = new Date();
    d.setHours( 14 );
    d.setMinutes( 0 );
    $scope.trip_dispatch = d;
  };

  $scope.changed = function () {
    //$log.log('Time changed to: ' + $scope.trip_dispatch);
  };

  $scope.clear = function() {
    $scope.trip_dispatch = null;
  };


$scope.trip_deliver = new Date();

  $scope.hstep2 = 1;
  $scope.mstep2 = 1;

  $scope.options2 = {
    hstep: [1, 2, 3],
    mstep: [1, 5, 10, 15, 25, 30]
  };

  $scope.ismeridian2 = true;
  $scope.toggleMode2 = function() {
    $scope.ismeridian2 = ! $scope.ismeridian2;
  };

  $scope.update2 = function() {
    var d = new Date();
    d.setHours( 14 );
    d.setMinutes( 0 );
    $scope.trip_deliver = d;
  };

  $scope.changed2 = function () {
    $log.log('Time changed to: ' + $scope.trip_deliver);
  };

  $scope.clear2 = function() {
    $scope.trip_dispatch = null;
  };




  /** End Time **/



   $scope.retrieveTrips = function(){
     var dataheader  = {model: 'trip', method: 'get', keys: {}, conditions:{}, spa: 'yes', order: 'trip_date DESC'};
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.tripList = data.data;
        $scope.tripListAll = data.data; 
        //$scope.setValue(0); 
    });
  }


  $scope.retrieveDriver = function(){
     var dataheader  = {model: 'employee', method: 'get', keys: {}, conditions:{category:'Driver'} };
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.driverList = data.data; 
        //$scope.setValue(0); 
    });
  }

   $scope.retrieveTruck = function(){
     var dataheader  = {model: 'truck', method: 'get', keys: {}, conditions:{}, spa:'yes' };
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.truckList = data.data; 
        //$scope.setValue(0); 
    });
  }
 

  $scope.retrieveClient = function(id = null){
     var dataheader 
     if(id ==null){
        dataheader = {model: 'clients', method: 'get', keys: {}, conditions:{}};
     }else{
        dataheader = {model: 'clients', method: 'get', keys: {}, conditions:{id:id}};
     }
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.clientList = data.data; 
       
    });
  }

  $scope.retrieveSupplier = function(id = null){
     var dataheader 
     if(id ==null){
        dataheader = {model: 'supplier', method: 'get', keys: {}, conditions:{}};
     }else{
        dataheader = {model: 'supplier', method: 'get', keys: {}, conditions:{id:id}};
     }
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.supplierList = data.data; 
    });
  }

  $scope.getAggregates = function(id, model){
     
     var dataheader  = {model: 'aggregates', method: 'get', keys: {}, conditions:{model: model +' (AND)', description: $scope.trip_aggregate + ' (AND)', foreign: id }, spa:model};
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data){
        if(model=='clients'){
            $scope.aggregateList = data.data;
            console.log('clients');
            console.log($scope.aggregateList);
            
            if(typeof $scope.aggregateList[0] !== 'undefined'){
                $scope.trip_priceClient = $scope.aggregateList[0].aggregates_Price;
                //alert($scope.trip_priceClient);
            }
        }else{
            $scope.aggregateList = data.data; 
            console.log('supplier');
            console.log($scope.aggregateList);
            if(typeof $scope.aggregateList[0] !== 'undefined'){
                $scope.trip_priceSupplier = $scope.aggregateList[0].aggregates_Price;
                //alert($scope.trip_priceSupplier);
            }
        }
    });
  }

  $scope.createTrip = function(){
         /**
         * sample add post
         * */
        //alert($scope.trip_dispatch+"=="+$scope.trip_deliver);
        $scope.trip_date = $scope.dt; 
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data:{
                 model: 'trip', method: 'insert', inputs: {  
                    receiptno: $scope.trip_receiptno, 
                    aggregate: $scope.trip_aggregate, 
                    client: $scope.trip_client, 
                    volumeclient: $scope.trip_volumeCLient, 
                    priceclient: $scope.trip_priceClient, 
                    supplier: $scope.trip_supplier,
                    volumesupplier: $scope.trip_volumeSupplier,
                    pricesupplier: $scope.trip_priceSupplier,
                    location: $scope.trip_area,
                    truck: $scope.trip_truck,
                    driver: $scope.trip_driver,
                    dispatch: $scope.trip_dispatch,
                    deliver: $scope.trip_deliver,
                    date: $scope.trip_date
                    }
                },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            //console.log(data);
            $scope.retrieveTrips();
            $('#closeCreate').trigger("click");
            $scope.removeModal();
        }); 
    }

  $scope.$watch(
                    $scope.trip_priceClient,
                    function handleFooChange( newValue, oldValue ) {
                        console.log( "Cliwent Price:", newValue );
                    }
                );

  $scope.$watch(
                    $scope.trip_priceSupplier,
                    function handleFooChange( newValue, oldValue ) {
                        console.log( "Supplier Price:", newValue );
                    }
                );



  $scope.checkPrice = function(){
    if((typeof $scope.trip_client !== 'undefined' || $scope.trip_client != "") && (typeof $scope.trip_aggregate !== 'undefined' || $scope.trip_aggregate != "")){
         $scope.getAggregates($scope.trip_client, 'clients', $scope.trip_aggregate);
    }

    if((typeof $scope.trip_supplier !== 'undefined' || $scope.trip_supplier == "") && (typeof $scope.trip_aggregate !== 'undefined' || $scope.trip_aggregate != "")){
         $scope.getAggregates($scope.trip_supplier, 'supplier', $scope.trip_aggregate);
    }
   
  }

   $scope.changeDriver = function(){
     //$cope.trip_driver;

    for (var i = 0, len = $scope.truckList.length; i < len; ++i) {
        var record = $scope.truckList[i];
        if(record.id == $scope.trip_truck){
            $scope.trip_driver = record.truck_Driver;
            
        }
        //alert(JSON.stringify(record));
    }
  }

  $scope.filterDate = function(){
    //alert($scope.datefrom+" ... "+$scope.dateto);
    if($scope.datefrom!="" && $scope.dateto!=""){
        var start = $scope.datefrom;
        var end = $scope.dateto;
        var newDate = start.replace("-","/");
        start = new Date(newDate).getTime();
        newDate = end.replace("-","/");
        end = new Date(newDate).getTime();

        var newIndex = 0;
        $scope.tripListNew=[];
        for (var i = 0, len = $scope.tripListAll.length; i < len; ++i) {
        var record = $scope.tripListAll[i];
        var recordDate;// = Date(record.billclient_Released);

        newDate = record.trip_Date.replace("-","/");
        recordDate = new Date(newDate).getTime();

        //console.log(record.recordDate+ "  === start"+ start+ " == end"+end);
        if(recordDate >= start && recordDate <= end){
            //$scope.trip_driver = record.truck_Driver;
            $scope.tripListNew[newIndex] = record;
            newIndex++;
        }
        //$scope.billingList = [];
        $scope.tripList = $scope.tripListNew;
        console.log($scope.tripListNew);
    }
   
    } else{
        $scope.retrieveBillings();
    }
  }


  $scope.retrieveClient();
  $scope.retrieveSupplier();
  $scope.retrieveTruck();
  $scope.retrieveTrips();
  $scope.retrieveDriver();
});



app.controller('cbillingController', function($scope, $http, $location, $sessionStorage, $routeParams, uibDateParser, $filter) {
    $scope.currentHref = $location.path();


   /**
     * sample get post
     * 
     * columns
     * keys: {0:'user', 1:'email'}
     * keys: {} = *
     * or no keys = *
     * 
     * conditions
     * conditions:{email:'maverickvillar2@gmail.com3 (AND)', user:'matthew2'}
     * conditions:{email:'maverickvillar2@gmail.com3 (OR)', user:'matthew2'}
     * conditions:{} = *
     * no condotions = *
     * 
     * */
     
 
     $scope.target = 0;
     $scope.listIndex = null;

     /** for Date Picker **/
  $scope.today = function() {
    $scope.dt = new Date();
  };
  $scope.today2 = function() {
    $scope.dt2 = new Date();
  };
  $scope.today();
  $scope.today2();

  $scope.clear = function() {
    $scope.dt = null;
  };
  $scope.clear2 = function() {
    $scope.dt2 = null;
  };

  $scope.inlineOptions = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.inlineOptions2 = {
    customClass: getDayClass,
    minDate: new Date(),
    showWeeks: true
  };

  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.dateOptions2 = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  // Disable weekend selection
  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 7);
  }

  $scope.toggleMin = function() {
    $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
    $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
  };
  $scope.toggleMin2 = function() {
    $scope.inlineOptions2.minDate = $scope.inlineOptions2.minDate ? null : new Date();
    $scope.dateOptions2.minDate = $scope.inlineOptions2.minDate;
  };

  $scope.toggleMin();
  $scope.toggleMin2();

  $scope.open1 = function() {
    $scope.popup1.opened = true;
  };

  $scope.open2 = function() {
    $scope.popup2.opened = true;
  };

  $scope.setDate = function(year, month, day) {
    $scope.dt = new Date(year, month, day);
  };

  $scope.setDate2 = function(year, month, day) {
    $scope.dt2 = new Date(year, month, day);
  };

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[0];
  $scope.altInputFormats = ['M!/d!/yyyy'];

  $scope.popup1 = {
    opened: false
  };

  $scope.popup2 = {
    opened: false
  };

  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  var afterTomorrow = new Date();
  afterTomorrow.setDate(tomorrow.getDate() + 1);
  $scope.events = [
    {
      date: tomorrow,
      status: 'full'
    },
    {
      date: afterTomorrow,
      status: 'partially'
    }
  ];

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }

    return '';
  }

  /** End date picker**/


  /** For Time **/


  $scope.mytime = new Date();

  $scope.hstep = 1;
  $scope.mstep = 15;

  $scope.options = {
    hstep: [1, 2, 3],
    mstep: [1, 5, 10, 15, 25, 30]
  };

  $scope.ismeridian = true;
  $scope.toggleMode = function() {
    $scope.ismeridian = ! $scope.ismeridian;
  };

  $scope.update = function() {
    var d = new Date();
    d.setHours( 14 );
    d.setMinutes( 0 );
    $scope.mytime = d;
  };

  $scope.changed = function () {
    $log.log('Time changed to: ' + $scope.mytime);
  };

  $scope.clear = function() {
    $scope.mytime = null;
  };


  /** End Time **/


  $scope.currentDate  = function(){
         var today = new Date();
         var dd = today.getDate();
         var mm = today.getMonth()+1; //January is 0!
         var yyyy = today.getFullYear();
         today = yyyy+"-"+mm+"-"+dd;
         return today;
    }



    $scope.setValue = function(id, index){
        $scope.listIndex = index;
        $scope.target = id;
    }


     $scope.goView = function(){
        if($scope.listIndex!=null){
            window.location.href = "#/receivable/billings/view/"+$scope.target;
        }
    }


   $scope.retrieveTrips = function(id){
     var dataheader  = {model: 'trip', method: 'get', keys: {}, conditions:{receivablebill: id}, spa: 'yes', order: 'trip_date'};
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.tripList = data.data; 
        console.log(data.data);
        //$scope.setValue(0); 
    });
  }


  $scope.retrieveClient = function(id = null){
     var dataheader 
     if(id ==null){
        dataheader = {model: 'clients', method: 'get', keys: {}, conditions:{}};
     }else{
        dataheader = {model: 'clients', method: 'get', keys: {}, conditions:{id:id}};
     }
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data) {
        $scope.clientList = data.data; 
       
    });
  }
 

  $scope.retrieveBillings = function(){
     
     var dataheader  = {model: 'billclient', method: 'get', keys: {}, conditions:{}, order: 'billclient_Released DESC',spa:'yes'};
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data){
        console.log(data);
        $scope.billingList = data.data;
        $scope.billingListAll = data.data;
    });
  }

  $scope.getAggregates = function(id, model){
     
     var dataheader  = {model: 'aggregates', method: 'get', keys: {}, conditions:{model: model +' (AND)', description: $scope.trip_aggregate + ' (AND)', foreign: id }, spa:model};
     
     var retriveRequest = $http({
        method: "post",
        url: $scope.mainURL + "/api/index.php",
        data: dataheader,
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    retriveRequest.success(function(data){
        $scope.aggregateList = data.data;
        // if(model=='clients'){
        //     $scope.aggregateList = data.data;
        //     console.log('clients');
        //     console.log($scope.aggregateList);
            
        //     if(typeof $scope.aggregateList[0] !== 'undefined'){
        //         $scope.trip_priceClient = $scope.aggregateList[0].aggregates_Price;
        //         //alert($scope.trip_priceClient);
        //     }
        // }else{
        //     $scope.aggregateList = data.data; 
        //     console.log('supplier');
        //     console.log($scope.aggregateList);
        //     if(typeof $scope.aggregateList[0] !== 'undefined'){
        //         $scope.trip_priceSupplier = $scope.aggregateList[0].aggregates_Price;
        //         //alert($scope.trip_priceSupplier);
        //     }
        // }
    });
  }

  $scope.createBill = function(){
         /**
         * sample add post
         * */
        var date = $scope.currentDate();
        var addrequest = $http({
            method: "post",
            url: $scope.mainURL + "/api/index.php",
            data:{
                 model: 'billclient', method: 'insert', inputs: {  
                    billno: $scope.billclient_billno, 
                    client: $scope.billclient_client, 
                    from: $scope.dt, 
                    to: $scope.dt2, 
                    released: date
                    }
                },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}//{ 'Content-Type': 'application/x-www-form-urlencoded' }
        });
        addrequest.success(function (data) {
            console.log(data);
            //alert($scope.dt2);
            //$scope.retrieveTrips();
            $scope.retrieveBillings();
            $('#closeCreate').trigger("click");
            $scope.removeModal();
        }); 
    }

  $scope.$watch(
                    $scope.trip_priceClient,
                    function handleFooChange( newValue, oldValue ) {
                        console.log( "Cliwent Price:", newValue );
                    }
                );

  $scope.$watch(
                    $scope.trip_priceSupplier,
                    function handleFooChange( newValue, oldValue ) {
                        console.log( "Supplier Price:", newValue );
                    }
                );



  $scope.checkPrice = function(){
    if((typeof $scope.trip_client !== 'undefined' || $scope.trip_client != "") && (typeof $scope.trip_aggregate !== 'undefined' || $scope.trip_aggregate != "")){
         $scope.getAggregates($scope.trip_client, 'clients', $scope.trip_aggregate);
    }

    if((typeof $scope.trip_supplier !== 'undefined' || $scope.trip_supplier == "") && (typeof $scope.trip_aggregate !== 'undefined' || $scope.trip_aggregate != "")){
         $scope.getAggregates($scope.trip_supplier, 'supplier', $scope.trip_aggregate);
    }
   
  }

   $scope.changeDriver = function(){
     //$cope.trip_driver;

    for (var i = 0, len = $scope.truckList.length; i < len; ++i) {
        var record = $scope.truckList[i];
        if(record.id == $scope.trip_truck){
            $scope.trip_driver = record.truck_Driver;
            
        }
        //alert(JSON.stringify(record));
    }
  }
  $scope.retrieveBillings();
  $scope.retrieveClient();
  $scope.getAggregates();

  $scope.filterDate = function(){
    //alert($scope.datefrom+" ... "+$scope.dateto);
    if($scope.datefrom!="" && $scope.dateto!=""){
        var start = $scope.datefrom;
        var end = $scope.dateto;
        var newDate = start.replace("-","/");
        start = new Date(newDate).getTime();
        newDate = end.replace("-","/");
        end = new Date(newDate).getTime();

        var newIndex = 0;
        $scope.billingListNew=[];
        for (var i = 0, len = $scope.billingListAll.length; i < len; ++i) {
        var record = $scope.billingListAll[i];
        var recordDate;// = Date(record.billclient_Released);

        newDate = record.billclient_Released.replace("-","/");
        recordDate = new Date(newDate).getTime();

        console.log(record.recordDate+ "  === start"+ start+ " == end"+end);
        if(recordDate >= start && recordDate <= end){
            $scope.trip_driver = record.truck_Driver;
            $scope.billingListNew[newIndex] = record;
            newIndex++;
        }
        //$scope.billingList = [];
        $scope.billingList = $scope.billingListNew;
        console.log($scope.billingListNew);
    }
   
    } else{
        $scope.retrieveBillings();
    }


}
if($scope.currentHref.indexOf("/receivable/billings/view/")!=-1){
        $scope.retrieveTrips($routeParams.id);
        $scope.billingID = $routeParams.id;
        //alert(1);
   }
});