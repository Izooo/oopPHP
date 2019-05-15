//we create a function to validate our form
// we will call the function when the form is submitted

function validateForm(){

    var fname = document.forms["userDetails"]["first_name"].value;
    var lname = document.forms["userDetails"]["last_name"].value;
    var city = document.forms["userDetails"]["city_name"].value;
    var uname = document.forms["userDetails"]["Username"].value;
    var password = document.forms["userDetails"]["Password"].value;

    if (fname == null || lname == "" || city == "" || uname == "" || password == ""){
        swal({
            title: "Error! Fields Empty!",
            text: "Please check the missing field",
            icon: "warning",
            button: "Ok"
          });
        //alert("All details required were not supplied");
        return false;
    }


    return true;

}