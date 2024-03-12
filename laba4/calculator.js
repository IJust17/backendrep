const output = document.getElementById("output");
const buttons = document.querySelectorAll("button");

buttons.forEach((button) => {
  button.addEventListener("click", function () {
    if (button.textContent.includes("clean")) {
      output.value = "";
    } else if (button.textContent.includes("count")) {
      calculator();
    } else if (button.classList.contains("button_trig_value")) {
      handleTrigonometryValue(button.dataset.function, button.dataset.value);
    } else {
      let isLastCharNumber = /\d/.test(output.value.slice(-1));
      let isCurrentCharNumber = /\d/.test(this.textContent);
      if (isLastCharNumber && isCurrentCharNumber) {
        output.value += this.textContent;
      } else {
        output.value = `${output.value} ${this.textContent}`;
      }
    }
  });
});

function handleTrigonometryValue(functionName, parameter) {
  fetch("calculator.php", {
    method: "POST",
    body: new URLSearchParams({ function: functionName, parameter: parameter }),
  })
    .then((response) => response.text())
    .then(
      (result) => (output.value += ` ${functionName}(${parameter}) = ${result}`)
    );
}

function calculator() {
  if (typeof output.value === "undefined" || output.value === "") {
    alert("Введите выражение");
    return;
  }

  fetch("calculator.php", {
    method: "POST",
    body: new URLSearchParams({ expression: output.value }),
  })
    .then((response) => response.text())
    .then((result) => (output.value = result));
}
