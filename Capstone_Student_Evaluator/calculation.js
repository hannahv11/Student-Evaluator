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
    let score1 = parseFloat(document.getElementById("Q1").value);
    let score2 = parseFloat(document.getElementById("Q2").value);
    let score3 = parseFloat(document.getElementById("Q3").value);
    let score4 = parseFloat(document.getElementById("Q4").value);
    let score5 = parseFloat(document.getElementById("Q5").value);

    // calculates average
    let average = (score1 + score2 + score3 + score4 + score5);

    // Delete later probably, sends average to HTML
    document.getElementById("average").innerHTML = average.value;
    
}

