<?php
session_start();
require_once('backend/isAuthenticated.php');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0">
  <title>company_info</title>
  <link href="http://fonts.googleapis.com/css?family=Raleway:100,500,400,600,400" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/standardize.css">
  <link rel="stylesheet" href="css/company_info-grid.css">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="plugins/sweet-alert.css">
  <link rel="stylesheet" href="plugins/swal-forms.css">
</head>
<body class="body page-company_info clearfix">
<div class="wrapper wrapper-1">
  <!--  <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"-->
  <!--          src="https://www.google.com/maps?t=m&amp;q=South Africa&amp;output=embed"></iframe>-->
</div>
<p onClick="window.location='index.html';" class="text text-1"><span>Busalot</span></p>
<button onClick="window.location='infoz.html';" class="_button _button-1">About</button>
<button onClick="window.location='company.html';" class="_button _button-2">Company Portal</button>
<button onClick="window.location='contact.html';" class="_button _button-3">Contact us</button>
<p class="text text-2">Company Profile</p>
<div class="element element-1"></div>
<p class="text text-3">Company Details</p>
<!--<button class="_button _button-4">Edit Details</button>-->
<button class="_button _button-5" onClick="saveCompanyChanges()">Apply and Save</button>
<!--<button class="_button _button-6">Delete Profile</button>-->
<p class="text text-4">Company Name:</p>
<input class="_input _input-1" id="company_name" type="text">
<p class="text text-6">Company Email:</p>
<input class="_input _input-2" id="company_email" type="text">
<p class="text text-10">Company Phone:</p>
<input class="_input _input-3" style="color:black" id="company_phone" type="text">
<p class="text text-11">Company Website:</p>
<input class="_input _input-6" id="company_website" type="text">
<p class="text text-14">Street Address:</p>
<input class="_input _input-7" id="company_address" type="text">
<p class="text text-17">City:</p>
<input class="_input _input-10" id="company_city" type="text">
<button class="_button _button-10" onClick="logout()">Logout</button>
<p class="text text-24">Company Routes</p>
<button class="_button _button-12" onclick="createNewRoute()">Create new route</button>
<button class="_button _button-14" onclick="createBus()">Create new bus</button>


<div class="wrapper wrapper-5 overlay">
  <!--    <button onclick="createNewRoute()">Open Modal</button>-->
  <table id="table">
    <thead id="tableHead" class="table-head">
    <tr>
      <th>From</th>
      <th>To</th>
      <th>Bus Name</th>
      <th>Rating</th>
      <th>Capacity</th>
      <th>Trailer Option</th>
      <th>Disability Access</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody id="tableBody" class="table-body">
    </tbody>
  </table>
</div>
<div class="wrapper wrapper-6" style="margin-bottom:250px">
<!--  <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"-->
<!--          src="https://www.google.com/maps?t=m&amp;q=South Africa&amp;output=embed"></iframe>-->

  <iframe id="iframe"
          width="100%"
          height="100%"
          frameborder="0"
          scrolling="no"
          marginheight="0"
          marginwidth="0"
          src=""

  >
  </iframe>
</div>


<script src="js/jquery-min.js"></script>
<script src="js/global-variables.js"></script>
<script src="./plugins/sweet-alert.js"></script>
<script src="./plugins/swal-forms.js"></script>
<script>
    $('document').ready(function () {
        getCompanyDetails();
        getAllCompanyRoutes();
        getCompanyBusses();
    });

    //This will be the busses that the logged in company owns
    var busses = [];

    function getCompanyDetails() {
        $.ajax({
            type: 'POST',
            url: url + '/getData.php',
            data: {
                key: key,
                getCompanyDetails: true,
            },
            xhrFields: {
                withCredentials: true
            },
            success: function (result) {
                result = JSON.parse(result);
                $('#company_name').val(result.data.name);
                $('#company_phone').val(result.data.telephone);
                $('#company_email').val(result.data.email);
                $('#company_website').val(result.data.website);
                $('#company_address').val(result.data.address);
                $('#company_city').val(result.data.city);
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    //PHP will check who is logged in
    function getCompanyBusses() {
        $.ajax({
            type: 'POST',
            url: url + '/getData.php',
            data: {
                key: key,
                getCompanyBusses: true,
            },
            xhrFields: {
                withCredentials: true
            },
            success: function (result) {
                result = JSON.parse(result);
                busses = result.data;
            },
            error: function (error) {
                console.log(error);
            },
        });
    }


    function saveCompanyChanges() {
        $.ajax({
            type: 'POST',
            url: url + '/saveData.php',
            data: {
                key: key,
                saveCompanyDetails: true,
                data: {
                    name: $('#company_name').val(),
                    telephone: $('#company_phone').val(),
                    email: $('#company_email').val(),
                    website: $('#company_website').val(),
                    address: $('#company_address').val(),
                    city: $('#company_city').val(),
                }
            },
            xhrFields: {
                withCredentials: true
            },
            success: function (result) {
              console.log(result);
                result = JSON.parse(result);
                if (result.status == 'success')
                    alert('Changes saved successfully');

              getCompanyBusses();
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function getAllCompanyRoutes() {
        $.ajax({
            type: 'POST',
            url: url + '/getData.php',
            data: {
                key: key,
                getAllCompanyRoutes: true,
            },
            xhrFields: {
                withCredentials: true
            },
            success: function (result) {
                result = JSON.parse(result);

                jQuery.each(result.data, function (i, data) {

                    //Convert boolean to Yes / No
                    var trailer = (data.trailer_option == 1) ? 'Yes' : 'No';
                    var disability = (data.disability == 1) ? 'Yes' : 'No';

                    $("#tableBody").append("<tr>" +
                        "<td>" + data.start_location + "</td>" +
                        "<td>" + data.end_location + "</td>" +
                        "<td>" + data.name + "</td>" +
                        "<td>" + data.rating + "</td>" +
                        "<td>" + data.size + "</td>" +
                        "<td>" + trailer + "</td>" +
                        "<td>" + disability + "</td>" +
                        "<td><button onclick='deleteRoute("+data.route_ID+")'>Delete</button></td>" +
                        "<td><button onclick=\"displayRoute('"+data.start_location+"','"+data.end_location+"')\">Preview</button></td>" +
                        "</tr>");
                });

            },
            error: function (error) {
            },
        });
    }


    function logout() {
        $.ajax({
            type: 'POST',
            url: url + '/logout.php',
            data: {
                key: key
            },
            xhrFields: {
                withCredentials: true
            },
            success: function (result) {
                window.location = 'index.html';
            },
            error: function (error) {
                window.location = 'index.html';
            },
        });
    }
function createNewRoute() {
        //convert busses array to be able to be read by swal
        //example:
        /*{value: '1', text: 'Bus 1'},
        {value: '2', text: 'Bus 2'},
        {value: '3', text: 'Bus 3'},*/

        var busOptions = busses.map(bus=>({value:bus.bus_ID,text:bus.name}));

        swal.withForm({
            title: 'Create a new route',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Submit',
            closeOnConfirm: true,
            formFields: [
                {id: 'start_location', placeholder: 'Start location', required: true},
                {id: 'end_location', placeholder: 'End location', required: true},
                {id: 'price', placeholder: 'Price', required: true},
                {
                    id: 'bus_ID',
                    type: 'select',
                    options: busOptions,
                }

            ]
        }, function (isConfirm) {

            //If user pressed cancel
            if(!isConfirm) return;

            $.ajax({
                type: 'POST',
                url: url + '/saveData.php',
                data: {
                    key: key,
                    saveNewRoute: true,
                    data: this.swalForm,
                },
                xhrFields: {
                    withCredentials: true
                },
                success: function (result) {
                    data = JSON.parse(result);

                    data = data.data;

                    var trailer = (data.trailer_option == 1) ? 'Yes' : 'No';
                    var disability = (data.disability == 1) ? 'Yes' : 'No';

                    $("#tableBody").append("<tr>" +
                        "<td>" + data.start_location + "</td>" +
                        "<td>" + data.end_location + "</td>" +
                        "<td>" + data.name + "</td>" +
                        "<td>" + data.rating + "</td>" +
                        "<td>" + data.size + "</td>" +
                        "<td>" + trailer + "</td>" +
                        "<td>" + disability + "</td>" +
                        "<td><button onclick='deleteRoute(data.route_ID)'>Delete</button></td>" +
                        "<td><button onclick=\"displayRoute('"+data.start_location+"','"+data.end_location+"')\">Preview</button></td>"+
                        "</tr>");
                },
                error: function (error) {

                },
            });
        })
    }

    function createBus(){
      swal.withForm({
        title: 'Create a new route',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Submit',
        closeOnConfirm: true,
        formFields: [
          { id: 'name', placeholder: 'Name', required: true },
          { id: 'size', placeholder: 'Capacity', type:'number', required: true },
          { name: 'disability', value: 'Disability', type: 'checkbox' },
          { name: 'trailer_option', value: 'Trailer option', type: 'checkbox'},
          {
            id: 'rating',
            type: 'select',
            options: [
              {value:'1',text:'Rating: 1'},
              {value:'2',text:'Rating: 2'},
              {value:'3',text:'Rating: 3'},
              {value:'4',text:'Rating: 4'},
              {value:'5',text:'Rating: 5'},
            ],
          }
        ]
      },
      function (isConfirm) {
        if(!isConfirm)
          return;

        if(this.swalForm.disability)
        {
          this.swalForm.disability = 1;
        }
        else
        {
          this.swalForm.disability = 0;
        }

        if(this.swalForm.trailer_option)
        {
          this.swalForm.trailer_option = 1;
        }
        else
        {
          this.swalForm.trailer_option = 0;
        }

        $.ajax({
          type: 'POST',
          url: url + '/saveData.php',
          data: {
            key: key,
            createBus:true,
            data: this.swalForm,
          },
          xhrFields: {
            withCredentials: true
          },
          success: function (result) {
            result = JSON.parse(result);
            if (result.status == 'success')
              alert('Bus created successfully');
          },
          error: function (error) {
          },
        });

      })
    }

    function deleteRoute(routeID) {
        if(confirm('Are you sure you want to delete this route?')){
            $.ajax({
                type: 'POST',
                url: url + '/deleteData.php',
                data: {
                    key: key,
                    deleteRoute:true,
                    data: {
                        route_ID: routeID
                    }
                },
                xhrFields: {
                    withCredentials: true
                },
                success: function (result) {
                    //Refresh the page to remove the route from the table
                    location.reload();
                },
                error: function (error) {
                },
            });
        }
    }

    function displayRoute(start_location,end_location){
        var iframe = $('#iframe');
        var url = "https://www.google.com/maps/embed/v1/directions?key=AIzaSyC3HZNneU2f0SwDVUswB1tp0HTyFdFXIq4" +
            "&origin="+start_location +
            "&destination="+end_location +
            "&zoom=9";

        iframe.attr('src',url);
    }


</script>

<style>
  table, td, th {
    border: 1px solid #ddd;
    text-align: left;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  th, td {
    padding: 15px;
  }

  .overlay {
    background-color: rgba(54, 54, 54, 0.5);
    overflow: auto;
  }

  .table-head {
    font-size: 25px;
  }

  .table-body {
    font-size: 18px;
  }

</style>
</body>
</html>
