// PARAM 1: Name of app
// PARAM 2: dependencies
// var myAPP: has all the code for this module
// ng-app: needs to target a specific controller (in our case, myApp)
//    tells the html where to find the code for elements tagged with ng-app
var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);

var url = 'http://aileenagravante.com/congress-information-search/spa/scripts/congress.php';
// var url = 'http://localhost/congress-spa-final.php';

// Anything inside the scope variable is available inside the controller
function MyController($scope, $http) {
  var error = "No error";

  // HTTP get request for all Legislators
  $http({
    method: 'GET',
    url: (url + '?database=legislators&keyword=all')
    }).then(function successCallback(response) {
      $scope.legislators = response.data.results;
    }, function errorCallback(response) {
      error = ($scope.error + " Error with Legislators API call");
  });

  // HTTP get request for all committees
  $http({
    method: 'GET',
    url: (url + '?database=committees&keyword=all')
    }).then(function successCallback(response) {
      $scope.committees = response.data.results;
    }, function errorCallback(response) {
      error = (error + " Error with Committees API call");
  });

  // HTTP get request for all active bills
  $http({
    method: 'GET',
    url: (url + "?database=bills&keyword=active")
    }).then(function successCallback(response) {
      $scope.activeBills = response.data.results;
    }, function errorCallback(response) {
      error = (error + " Error with Bills API call");
  });

  // HTTP get request for all new bills
  $http({
    method: 'GET',
    url: (url + "?database=bills&keyword=new")
    }).then(function successCallback(response) {
      $scope.newBills = response.data.results;
    }, function errorCallback(response) {
      error = (error + " Error with Bills API call");
  });

  // This is used for pagination
  $scope.currentPageLegislatorsState = 1;
  $scope.pageSizeLegislatorsState = 10;

  $scope.currentPageLegislatorsHouse = 1;
  $scope.pageSizeLegislatorsHouse = 10;

  $scope.currentPageLegislatorsSenate = 1;
  $scope.pageSizeLegislatorsSenate = 10;

  $scope.currentPageBillsActive = 1;
  $scope.pageSizeBillsActive = 10;

  $scope.currentPageBillsNew = 1;
  $scope.pageSizeBillsNew = 10;

  $scope.currentPageCommitteesHouse = 1;
  $scope.pageSizeCommitteesHouse = 10;

  $scope.currentPageCommitteesSenate = 1;
  $scope.pageSizeCommitteesSenate = 10;

  $scope.currentPageCommitteesJoint = 1;
  $scope.pageSizeCommitteesJoint = 10;

  // When view details for a legislator is clicked, the bioguide_id is captured
  //    this filters for the appropriate legislator
  // We also make additional $http requests to get the details of the committees
  //    that legislator belongs to and bills that legislator sponsors
  // Finally, we do the date math, with the help of Moment.js, for the progress
  //    bar on the legislator's detail page
  $scope.viewLegislatorDetails = function(legislatorID) {
    $scope.selectedLegislator = $scope.legislators.filter(function(legislator){
      return (legislator.bioguide_id == legislatorID);
    });

    // HTTP get request for committees the selected legislator belongs to
    $http({
      method: 'GET',
      url: (url + "?database=committees&keyword=" + legislatorID)
      }).then(function successCallback(response) {
        $scope.selectedLegislatorCommittees = response.data.results;
      }, function errorCallback(response) {
        error = (error + " Error with Selected Legislator API call");
    });

    // HTTP get request for bills the selected legislator sponsors
    $http({
      method: 'GET',
      url: (url + "?database=bills&keyword=" + legislatorID)
      }).then(function successCallback(response) {
        $scope.selectedLegislatorBills = response.data.results[0].bills;
      }, function errorCallback(response) {
        error = (error + " Error with Selected Legislator API call");
    });

    // Calculate how much time the selected legislator has already spent
    //    in term using Moment.js
    var now = moment();
    var start = moment($scope.selectedLegislator[0].term_start, 'YYYY-MM-DD');
    var end = moment($scope.selectedLegislator[0].term_end, 'YYYY-MM-DD');

    // (now - term_start)
    var nowStartDiff = now.diff(start, 'days');

    // (term_end - term_start)
    var endStartDiff = end.diff(start, 'days');

    // The % of time the legislator already served on his/her current term =
    //    (now - term_start)/(term_end - term_start)*100
    $scope.termProgress = Math.ceil(((nowStartDiff/endStartDiff)*100));
  };

  // status: true = active; false = new
  // When view details for a bill is clicked, the status and bill ID is
  //    is captured and this filters for the appropriate bill
  $scope.viewBillDetails = function(status, billID) {
    if(status) {
      $scope.selectedBill = $scope.activeBills.filter(function(bill){
        return (bill.bill_id == billID);
      });
    }
    else {
      $scope.selectedBill = $scope.newBills.filter(function(bill){
        return (bill.bill_id == billID);
      });
    }
  };

  // This drives the display (hollow or solid) of the star based on whether or
  //    not the item is favorited. In the CSS, we have
  //    styled 'fa fa-star' (for a favorited item) accordingly.
  $scope.star = function(id) {
    if(localStorage.getItem(id) != null) {
      return 'fa fa-star';
    }
    else {
      return 'fa fa-star-o';
    }
  }

  // Filter for showing favorited items on favorites page
  // Check if the item's ID is in the local storage
  // Note: the filter is run on each item during ng-repeat
  $scope.favoriteFilter = function(item) {
    if(localStorage.getItem(item.bioguide_id) != null) {
      return true;
    }
    else if(localStorage.getItem(item.bill_id) != null) {
      return true;
    }
    else if(localStorage.getItem(item.committee_id) != null) {
      return true;
    }
    else {
      return false;
    }
  }

  // Favorite or un-favorite an item when the star
  //    icon is clicked
  // Since IDs across legislators, committees, and bills
  //    are unique, we use this as the key and keep the
  //    database as value for informational purposes only
  $scope.favorite = function(id, database) {
    if(localStorage.getItem(id) != null) {
      localStorage.removeItem(id);
    }
    else {
      localStorage.setItem(id, database);
    }
  }

  // Remove the favorited item from local storage
  //    when trash icon is clicked in favorites page
  $scope.trash = function(id) {
    localStorage.removeItem(id);
  }

  $scope.pageChangeHandler = function(num) {
      console.log('page changed to ' + num);
  };

  console.log(error);
}

function OtherController($scope) {
  $scope.pageChangeHandler = function(num) {
    console.log('going to page ' + num);
  };
}

// PARAM 1: name of this controller
// PARAM 2: function called for this controller
myApp.controller('MyController', MyController);
myApp.controller('OtherController', OtherController);

$(document).ready(function(){
  navToggle();
  menuReset();
});

// Adjust the left margin of the main content div
//    every time the window is adjusted
$(window).resize(function(){
  contentMarginAdjust();
});

function contentMarginAdjust() {
  // If the menu is hidden (display: none)
  if($("#main-nav").css("display") === "none") {
    $("#main-content").css("margin-left", "0px");
  }
  // If the menu is not hidden (display: block)
  else {
    if($(window).width() <= 767) {
      $("#main-content").css("margin-left", "50px");
    }
    else {
      $("#main-content").css("margin-left", "150px");
    }
  }
}

function navToggle() {
  $("#main-nav-btn").click(function() {
    // Capture these values before the main nav is toggled
    var mainNavDisplayed = ($("#main-nav").css("display") === "none");
    var mainNavWidth = $("#main-nav").css("width");

    // This un/displays the main nav
    $("#main-nav").toggle();

    // If the menu was previously closed (display: none)
    if(mainNavDisplayed) {
      // If we're on mobile
      if($(window).width() <= 767) {
        $("#main-content").css("margin-left", "50px");
      }
      else {
        $("#main-content").css("margin-left", "150px");
      }
    }

    // If the menu was previously open (display: block)
    else {
      // Regardless mobile or desktop, if we're closing the menu
      //    the left margin of the content is at 0px
      $("#main-content").css("margin-left", "0px");
    }
  });
}

// Takes care of when a menu item is clicked while the page is on a details page,
//    and takes the user back to the last active tab on the main page of the
//    corresponding menu item.
// Ex: If we're currently viewing the details of a legislator in the Senate,
//    when we click the Legislator menu item, we will be brought back to the
//    Senate tab of the Legislator's page.
function menuReset() {
  $("#legislators-nav-btn").click(function(){
    if(!($("#legislators .item.initial-carousel-item").hasClass("active"))) {
      $("#legislators .item.initial-carousel-item").addClass("active");
      $("#legislators .item.secondary-carousel-item").removeClass("active");
    }
  });

  $("#bills-nav-btn").click(function(){
    if(!($("#bills .item.initial-carousel-item").hasClass("active"))) {
      $("#bills .item.initial-carousel-item").addClass("active");
      $("#bills .item.secondary-carousel-item").removeClass("active");
    }
  });

  $("#favorites-nav-btn").click(function(){
    if($("#favorites .item.secondary-carousel-item").hasClass("active")) {
      $("#favorites .item.initial-carousel-item").addClass("active");
      $("#favorites .item.secondary-carousel-item").removeClass("active");
    }
    else if($("#favorites .item.third-carousel-item").hasClass("active")) {
      $("#favorites .item.initial-carousel-item").addClass("active");
      $("#favorites .item.third-carousel-item").removeClass("active");
    }
  });
}
