
function confirmSignOut() {
    // Display a confirmation dialog
    var confirmed = confirm("Are you sure you want to sign out?");
    
    // If the user clicks "OK," redirect to signout.php
    if (confirmed) {
        window.location.href = "/EHR_system/ajax/signout.php";
    }
}