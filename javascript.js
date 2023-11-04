/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

function instLogin(){
    var x = document.forms["loginForm"]["email"].value;
    var y = document.forms["loginForm"]["pass"].value;
    if (x === "" || x===null) {
        alert("Email must be filled out");
        return false;
    }
    if (y === ""|| y===null) {
        alert("Password must be filled out");
        return false;
    }
    if(y.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }
    
    if(/@/.test(loginForm.email.value)){
        window.location.href = "instHomepage.php";
        return true;
    }
    else{
        alert("You have entered an invalid email address!");
    return false;
    }
}

function stuLogin(){
    var x = document.forms["loginForm"]["ksuID"].value;
    var y = document.forms["loginForm"]["pass"].value;
    if (x === "" || x===null) {
        alert("KSU ID must be filled out");
        return false;
    }
    if (y === ""|| y===null) {
        alert("Password must be filled out");
        return false;
    }
    if(y.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }
    window.location.href = "stuHomepage.php";
}

function instSignup(){
    var x = document.forms["signupForm"]["fName"].value;
    var y = document.forms["signupForm"]["lName"].value;
    var z = document.forms["signupForm"]["email"].value;
    var w = document.forms["signupForm"]["pwd"].value;
    if (x === "" || x===null) {
        alert("First Name must be filled out");
        return false;
    }
    if (y === ""|| y===null) {
        alert("Last Name must be filled out");
        return false;
    }
    if (z === ""|| z===null) {
        alert("Email must be filled out");
        return false;
    }
    if (w === ""|| w===null) {
        alert("Password must be filled out");
        return false;
    }
    if(w.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }
    if(/@/.test(signupForm.email.value)){
        window.location.href = "instHomepage.php";
        return true;
    }
    else{
        alert("You have entered an invalid email address!");
    return false;
    }
}

function stuSignup(){
    var x = document.forms["loginForm"]["ksuID"].value;
    var y = document.forms["loginForm"]["pass"].value;
    if (x === "" || x===null) {
        alert("KSU ID must be filled out");
        return false;
    }
    if (y === ""|| y===null) {
        alert("Password must be filled out");
        return false;
    }
    if(y.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }
    if(x.length > 9|| x.length < 9) {
        alert("ID must be 9 digits");
        return false;
    }
    window.location.href = "stuHomepage.php";
}