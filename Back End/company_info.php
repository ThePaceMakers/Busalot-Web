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
