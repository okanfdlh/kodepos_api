<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="index.js"></script>
    <link rel="stylesheet" href="style1.css">
    <title>Kode Pos Bangka Belitung</title>
  </head>
  <?php
  $apiUrl = "https://raw.githubusercontent.com/okanfdlh/kodepos_api/refs/heads/main/dataposbabel.json";
  $response = file_get_contents($apiUrl);
  $data = json_decode($response, true);
  ?>
  <body>
    <div class="container">
    <h1>KODE POS BANGKA BELITUNG</h1>
<p class="subtitle">Silakan pilih kabupaten, kecamatan, dan desa untuk melihat kode pos.</p>

    <div class="flex">
    <select id="city">
        <option value="">Pilih Kabupaten</option>
        <?php
        $cityList = [];
        foreach ($data as $item) {
          if (!in_array($item['city'], $cityList)) {
            $cityList[] = $item['city'];
            echo "<option value='{$item['city']}'>{$item['city']}</option>";
          }
        }
        ?>
      </select>
      <select id="sub_district">
        <option value="">Pilih Kecamatan</option>
      </select>
      <select id="urban">
        <option value="">Pilih Desa</option>
      </select>
      <button id="searchButton">Cari</button>
    </div>
    </div>
    <div id="result"></div>
    <script>
      const data = <?= json_encode($data) ?>;
const citySelect = document.getElementById("city");
const subDistrictSelect = document.getElementById("sub_district");
const urbanSelect = document.getElementById("urban");
const searchButton = document.getElementById("searchButton");
const resultDiv = document.getElementById("result");

citySelect.addEventListener("change", function () {
  const selectedCity = citySelect.value;
  subDistrictSelect.innerHTML = "<option value=''>Pilih Kecamatan</option>";
  urbanSelect.innerHTML = "<option value=''>Pilih Desa</option>";
  if (selectedCity) {
    const subDistrictSet = new Set();
    data.forEach(item => {
      if (item.city === selectedCity) {
        subDistrictSet.add(item.sub_district);
      }
    });
    subDistrictSet.forEach(subDistrict => {
      const option = document.createElement("option");
      option.value = subDistrict;
      option.textContent = subDistrict;
      subDistrictSelect.appendChild(option);
    });
  }
});

subDistrictSelect.addEventListener("change", function () {
  const selectedCity = citySelect.value;
  const selectedSubDistrict = subDistrictSelect.value;
  urbanSelect.innerHTML = "<option value=''>Pilih Desa</option>";
  if (selectedCity && selectedSubDistrict) {
    data.forEach(item => {
      if (item.city === selectedCity && item.sub_district === selectedSubDistrict) {
        const option = document.createElement("option");
        option.value = item.urban;
        option.textContent = item.urban;
        urbanSelect.appendChild(option);
      }
    });
  }
});

searchButton.addEventListener("click", function () {
  const selectedCity = citySelect.value;
  const selectedSubDistrict = subDistrictSelect.value;
  const selectedUrban = urbanSelect.value;

  resultDiv.style.display = "block";
  resultDiv.innerHTML = "";


  if (selectedCity && selectedSubDistrict && selectedUrban) {
    const result = data.find(
      item => item.city === selectedCity && item.sub_district === selectedSubDistrict && item.urban === selectedUrban
    );
    if (result) {
      console.log(result); // Debugging
      resultDiv.innerHTML = `
        <div class="card">
          <h3>Hasil Pencarian</h3>
          <hr>
          <div class="table-layout">
          <div>
            <p class="label"><span>Kabupaten</span>: ${result.city}</p>
          </div>
          <div>
            <p class="label"><span>Kecamatan</span>: ${result.sub_district}</p>
          </div>
          <div>
          <p class="label"><span>Desa</span>: ${result.urban}</p>
          </div>
          <div>
           <p class="label"><span>Kode Pos</span>: ${result.postal_code}</p>
          </div>
 
          </div>
        </div>
      `;
    } else {
      resultDiv.innerHTML = "Kode pos tidak ditemukan";
    }
  } else {
    resultDiv.innerHTML = "Silakan pilih semua opsi";
  }
});

    </script>
  </body>
</html>
