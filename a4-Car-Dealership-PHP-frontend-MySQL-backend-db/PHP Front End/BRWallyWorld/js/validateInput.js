/*
  FILE          : validateInput.js
  PROJECT       : PROG2000 - Web Development: Assignment #4 - Hi-Low game in ASP
  PROGRAMMER    : Brendan Rushing
  FIRST VERSION : 2018-11-01
  DESCRIPTION   :   Hi-Lo game written with server-side written in ASP
					Inputs are validated in javascript
					Forms are written in HTML
                    The user is asked for a username and then a max number guess
                    The user then precedes to guess the random number and the range is shown.
                    The user is shown a message and the background is changed after the game is won.
                    The user can then play the game again by pressing the Play again button.


                    This file is the input validation written in javascript:
                    1. input numbers are validated using regular expression to allow any amount of numbers
                    2. input letters are validated using regular expression to allow any amount of letters upper and lower and spaces

*/
    
    
    
    //
	// Function: validateNumbers(input) 
	// Purpose: check to see if input is only numbers
	// Parameters: input
	// Return: true or false
	//
	function validateNumbers(input) { 
		var numberCheck = /^[0-9]*$/;  //regex for numbers
  		var retCode = input.match(numberCheck); //check regex match with input

  		if(retCode == null){
    	//not valid name
    	inputError.innerHTML = "You can only enter numbers in this field";

		return false;
		}
		else if (input > 9999){

		inputError.innerHTML = "The number is too big. Max number of 9999";
		return false;
		}


		return true;
	}		




	//
	// Function: validateLetters(input) 
	// Purpose: check to see if input is only letters (capital or lowercase)
	// Parameters: input
	// Return: true or false
	//
	function validateLetters(input) { 
		var letterCheck = /^[a-zA-Z ]+$/;
  		var retCode = input.match(letterCheck); //check regex match with input

  		if(retCode == null){
    	//not valid name
    	inputError.innerHTML = "You can only enter letters and spaces for name";

		return false;
		}

		return true;
	}	
