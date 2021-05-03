function checkFiscalYear(event) {
  // Get the fiscal year field
  var fy = $("#fiscalYear").val();

  // Creat the regex and test it
  let re = /\d{4} \/ \d{4}/;

  if (re.test(fy) == false) {
    alert('Fiscal year must be in the format "#### / ####"\nFor example "2021 / 2022".');
    event.preventDefault();
  }
}

$(document).ready(function(){
  const form = document.getElementById('frmEditFiscalYear');
  form.addEventListener('submit', checkFiscalYear);
});
