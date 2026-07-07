document.addEventListener("DOMContentLoaded", function () {
  const quantity = document.getElementById("quantity");

  const btnMinus = document.getElementById("btnMinus");

  const btnPlus = document.getElementById("btnPlus");
  const stock = parseInt(quantity.dataset.stock);

  btnMinus.addEventListener("click", function () {
    let value = parseInt(quantity.value);

    if (value > 1) {
      quantity.value = value - 1;
    }
  });

  btnPlus.addEventListener("click", function () {
    let value = parseInt(quantity.value);

    if (value < stock) {
      quantity.value = value + 1;
    }
  });
});
