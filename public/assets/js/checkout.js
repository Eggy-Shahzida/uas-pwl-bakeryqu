document.addEventListener("DOMContentLoaded", function () {
  const provinceSelect = document.getElementById("province");
  const citySelect = document.getElementById("city");
  const districtSelect = document.getElementById("district");
  const courierSelect = document.getElementById("courier");
  const shippingCostEl = document.getElementById("shippingCost");
  const grandTotalEl = document.getElementById("grandTotal");
  const shippingCostInput = document.getElementById("shippingCostInput");
  const shippingServiceInput = document.getElementById("shippingServiceInput");
  const provinceNameInput = document.getElementById("provinceNameInput");
  const cityNameInput = document.getElementById("cityNameInput");
  const districtNameInput = document.getElementById("districtNameInput");
  const submitBtn = document.getElementById("submitOrderBtn");

  if (!provinceSelect) return; // bukan halaman checkout

  const subtotal = window.CHECKOUT_SUBTOTAL || 0;
  const baseUrl = window.BASE_URL || "";

  let selectedCost = 0;

  //------------------------------------------------
  // helper
  //------------------------------------------------
  function formatRupiah(number) {
    return "Rp " + Number(number).toLocaleString("id-ID");
  }

  function resetSelect(select, placeholder) {
    select.innerHTML = `<option value="">${placeholder}</option>`;
    select.disabled = true;
  }

  function updateTotal() {
    const total = subtotal + selectedCost;
    grandTotalEl.textContent = formatRupiah(total);
  }

  function resetShippingCost() {
    shippingCostEl.textContent = "-";
    selectedCost = 0;
    shippingCostInput.value = 0;
    shippingServiceInput.value = "";
    updateTotal();
  }

  //------------------------------------------------
  // step 1 -> 2: provinsi dipilih, load kota/kabupaten
  //------------------------------------------------
  provinceSelect.addEventListener("change", function () {
    const provinceId = this.value;

    provinceNameInput.value = this.options[this.selectedIndex]?.text || "";
    cityNameInput.value = "";
    districtNameInput.value = "";

    resetSelect(citySelect, "-- Pilih Kota/Kabupaten --");
    resetSelect(districtSelect, "-- Pilih Kecamatan --");
    resetShippingCost();

    if (!provinceId) return;

    citySelect.innerHTML = '<option value="">Memuat...</option>';

    fetch(
      `${baseUrl}/checkout/get-cities?province_id=${encodeURIComponent(provinceId)}`,
    )
      .then((res) => res.json())
      .then((result) => {
        if (!result.success || !result.data.length) {
          citySelect.innerHTML =
            '<option value="">Data tidak tersedia</option>';
          return;
        }

        citySelect.innerHTML =
          '<option value="">-- Pilih Kota/Kabupaten --</option>';

        result.data.forEach(function (city) {
          const opt = document.createElement("option");
          opt.value = city.id;
          opt.textContent = city.name;
          citySelect.appendChild(opt);
        });

        citySelect.disabled = false;
      })
      .catch(() => {
        citySelect.innerHTML = '<option value="">Terjadi kesalahan</option>';
      });
  });

  //------------------------------------------------
  // step 2 -> 3: kota dipilih, load kecamatan
  //------------------------------------------------
  citySelect.addEventListener("change", function () {
    const cityId = this.value;

    cityNameInput.value = this.options[this.selectedIndex]?.text || "";
    districtNameInput.value = "";

    resetSelect(districtSelect, "-- Pilih Kecamatan --");
    resetShippingCost();

    if (!cityId) return;

    districtSelect.innerHTML = '<option value="">Memuat...</option>';

    fetch(
      `${baseUrl}/checkout/get-districts?city_id=${encodeURIComponent(cityId)}`,
    )
      .then((res) => res.json())
      .then((result) => {
        if (!result.success || !result.data.length) {
          districtSelect.innerHTML =
            '<option value="">Data tidak tersedia</option>';
          return;
        }

        districtSelect.innerHTML =
          '<option value="">-- Pilih Kecamatan --</option>';

        result.data.forEach(function (district) {
          const opt = document.createElement("option");
          opt.value = district.id;
          opt.textContent = district.name;
          districtSelect.appendChild(opt);
        });

        districtSelect.disabled = false;
      })
      .catch(() => {
        districtSelect.innerHTML =
          '<option value="">Terjadi kesalahan</option>';
      });
  });

  //------------------------------------------------
  // step 3 selesai / ganti kurir -> hitung ongkir
  //------------------------------------------------
  function calculateShipping() {
    const districtId = districtSelect.value;
    const courier = courierSelect.value;

    if (districtSelect.selectedIndex >= 0) {
      districtNameInput.value =
        districtSelect.options[districtSelect.selectedIndex]?.text || "";
    }

    if (!districtId) {
      resetShippingCost();
      return;
    }

    shippingCostEl.textContent = "Menghitung...";

    submitBtn.disabled = true;

    const formData = new URLSearchParams();
    formData.append("district_id", districtId);
    formData.append("courier", courier);

    fetch(`${baseUrl}/checkout/get-cost`, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: formData,
    })
      .then((res) => res.json())
      .then((result) => {
        submitBtn.disabled = false;

        if (!result.success || !result.data || !result.data.length) {
          shippingCostEl.textContent = "Layanan tidak tersedia";
          selectedCost = 0;
          shippingCostInput.value = 0;
          shippingServiceInput.value = "";
          updateTotal();
          return;
        }

        // ambil opsi termurah dari kurir yang dipilih
        const cheapest = result.data.reduce(function (min, item) {
          return item.cost < min.cost ? item : min;
        }, result.data[0]);

        selectedCost = cheapest.cost;
        shippingCostInput.value = cheapest.cost;
        shippingServiceInput.value = cheapest.service || "";

        shippingCostEl.textContent =
          formatRupiah(cheapest.cost) +
          (cheapest.service ? ` (${cheapest.service})` : "");

        updateTotal();
      })
      .catch(() => {
        submitBtn.disabled = false;
        shippingCostEl.textContent = "Gagal menghitung ongkir";
        selectedCost = 0;
        shippingCostInput.value = 0;
        updateTotal();
      });
  }

  districtSelect.addEventListener("change", calculateShipping);
  courierSelect.addEventListener("change", calculateShipping);
});
