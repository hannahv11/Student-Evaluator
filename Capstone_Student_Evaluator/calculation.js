/*
    
	ATC Peer Review Project
    Author: Piper Noll, Hannah Vorel, Josh Vang
    Date: 09/24/2024  

    Filename: calculation.js
*/

// starts once the submit button is pushed
const submit = document.getElementById("submit");
submit.onclick = function() {

    // Gets values from forms and stores it in variables
let score1 = document.getElementById("Q1").value;
let score2 = document.getElementById("Q2").value;
let score3 = document.getElementById("Q3").value;
let score4 = document.getElementById("Q4").value;
let score5 = document.getElementById("Q5").value;

// calculates average
let sum = parseFloat(score1) + parseFloat(score2) + parseFloat(score3) + parseFloat(score4) + parseFloat(score5);
let average = sum / 5;

// Delete later probably, sends average to HTML
document.getElementById("score1").innerHTML = score1;
document.getElementById("score2").innerHTML = score2;
document.getElementById("score3").innerHTML = score3;
document.getElementById("score4").innerHTML = score4;
document.getElementById("score5").innerHTML = score5;
document.getElementById("sum").innerHTML = sum;
document.getElementById("average").innerHTML = average;
    
}
