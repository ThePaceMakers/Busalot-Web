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