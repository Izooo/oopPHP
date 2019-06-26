$(document).ready(function(){
    $('#api-key-btn').click(function(event){
        var confirm_key = confirm("You are about to generate a new API key");
        if(!confirm_key){
            return;           
        }
        
        $.ajax({
            url: "apikey.php",
            type: "POST",
            success: function(data){
                if(data['success'] == 1){
                    //everything went fine
                    //set your key in the text area
                    $('#api_key').val(data['message']);
                    //$('#api_key').html(data['message']);
                }else{
                    alert("Something went wrong. Please try again");
                }
            }
        });
    });
});