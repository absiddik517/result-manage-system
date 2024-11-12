export function round(num, decimalPlaces = 0) {
  const multiplier = 10 ** decimalPlaces;
  return Math.round(num * multiplier) / multiplier;
}

export function toWord(number) {
  let result = "";
  let ones = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen"];
  let tens = ["", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];

  if (number < 20) {
    result = ones[number];
  } else if (number < 100) {
    result = tens[Math.floor(number / 10)] + " " + ones[number % 10];
  } else if (number < 1000) {
    result = ones[Math.floor(number / 100)] + " hundred " + toWord(number % 100);
  } else if (number < 1000000) {
    result = toWord(Math.floor(number / 1000)) + " thousand " + toWord(number % 1000);
  } else if (number < 1000000000) {
    result = toWord(Math.floor(number / 1000000)) + " million " + toWord(number % 1000000);
  } else {
    result = toWord(Math.floor(number / 1000000000)) + " billion " + toWord(number % 1000000000);
  }

  return result;
}

export function sum(data, property, match = false){
  let total = 0;
  
  data.forEach(function(item) {
    if(!match){
      total += item[property];
    }else{
      if(item[match[0]] == match[1]){
        total += item[property];
      }
    }
  })
  
  return total;
}

export function intToBdtFormat(num) {
  // Convert the integer to a Number object
  const numObj = new Number(num);

  // Convert the Number object to BDT format
  const bdtFormatted = numObj.toLocaleString('en-BD', { style: 'currency', currency: 'BDT' });

  // Return the BDT-formatted string
  return bdtFormatted;
}

export function random_str(length = 10) {
  var result           = '';
  var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}





