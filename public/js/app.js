document.addEventListener("DOMContentLoaded", function () {
  // ---- Refresh captcha tanpa reload halaman ----
  var refreshBtn = document.getElementById("captcha-refresh-btn");
  var captchaImage = document.getElementById("captcha-image");

  if (refreshBtn && captchaImage) {
    refreshBtn.addEventListener("click", function () {
      refreshBtn.classList.add("spin");
      var baseUrl = refreshBtn.dataset.baseUrl || "/captcha";
      captchaImage.src = baseUrl + "?v=" + Date.now();
      setTimeout(function () {
        refreshBtn.classList.remove("spin");
      }, 400);
    });
  }

  // ---- Cari data karyawan otomatis berdasarkan NIK ----
  var nikInput = document.getElementById("nik");
  var namaInput = document.getElementById("nama");
  var namaOutput = document.getElementById("preview-nama");
  var jabatanOutput = document.getElementById("preview-jabatan");
  var gajiPokokInput = document.getElementById("gaji_pokok");
  var previewBox = document.getElementById("karyawan-preview");
  var previewNotFound = document.getElementById("karyawan-not-found");
  var searchUrl = nikInput ? nikInput.dataset.searchUrl : null;
  var debounceTimer = null;

  if (nikInput && searchUrl) {
    nikInput.addEventListener("input", function () {
      clearTimeout(debounceTimer);
      var nik = nikInput.value.trim();

      if (previewBox) previewBox.style.display = "none";
      if (previewNotFound) previewNotFound.style.display = "none";

      if (nik.length < 3) return;

      debounceTimer = setTimeout(function () {
        fetch(searchUrl + "?nik=" + encodeURIComponent(nik), {
          headers: { "X-Requested-With": "XMLHttpRequest" },
        })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (data.ditemukan) {
              if (namaInput && !namaInput.value) {
                namaInput.value = data.nama;
              }
              if (namaOutput) namaOutput.textContent = data.nama;
              if (jabatanOutput) jabatanOutput.textContent = data.jabatan;
              if (gajiPokokInput && !gajiPokokInput.value) {
                gajiPokokInput.value = Math.round(data.gaji_pokok);
              }
              if (previewBox) previewBox.style.display = "block";
            } else if (previewNotFound) {
              previewNotFound.style.display = "block";
            }
          })
          .catch(function () {
            /* diamkan; validasi server tetap berjalan saat submit */
          });
      }, 350);
    });
  }

  // ---- Tombol cetak slip ----
  var printBtn = document.getElementById("print-btn");
  if (printBtn) {
    printBtn.addEventListener("click", function () {
      window.print();
    });
  }
});
