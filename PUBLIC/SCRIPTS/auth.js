url = API_ADDRESS + "AUTH/";

function login(data = {}) {
  console.group("LOGIN - START");
  const timeout = 500;

  method = "POST";
  url = API_ADDRESS + "AUTH/";

  console.group(" Sending Login Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Data   :", data);
  console.groupEnd();

  var status = $("#status-message");

  $.ajax({
    url: url,
    type: method,
    data: data,
    dataType: "json",
    success: function (res) {
      console.log("Server Response:", res);

      if (res.status === "success") {
        status.text("Berhasil login.");
        status.attr("class", "success");
        window.location.href = "index.php";
      } else if (res.status === "error") {
        status.text("Gagal login, " + res.message);
        status.attr("class", "error");
      }
    },
    error: function (xhr) {
      console.error("AJAX Error:", xhr.responseText);

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }

      status.text(msg); // FIXED
    },
  });
  console.groupEnd();
}

function checkLoggedIn() {
  console.group("CHECK LOG IN - START");
  const timeout = 500;
  method = "GET";

  data = {
    jenis: "account",
  };
  url = API_ADDRESS + "AUTH/";

  console.group(" Sending Check logged in Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Data   :", data);
  console.groupEnd();

  var status = $("#status-message");

  $.ajax({
    url: url,
    type: "GET",
    data: data,
    dataType: "json",
    success: function (res) {
      console.log("Server Response:", res);
      if (res.status === "success") {
        if (res.data.jenis != "MAHASISWA") {
          allowDosenActions();
        }
      }
      if (res.status === "error") {
        status.text("Belum login, " + res.message);
        window.location.href = "login.php";
      }
    },
    error: function (xhr) {
      console.error("AJAX Error:", xhr.responseText);

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }

      status.text(msg);
    },
  });
  console.groupEnd();
}
function getGroupJoinedByUser(keyword = "") {
  console.group("GET GROUPED JOIN BY USER - START");
  const timeout = 500;

  const data = {
    limit: 5,
    offset: 0,
    keyword: keyword,
    jenis: "group",
  };

  const method = "GET";
  const url = API_ADDRESS + "AUTH/";

  console.group("GET GROUPED JOIN BY USER - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Data   :", data);
  console.groupEnd();

  var status = $("#status-message");

  $.ajax({
    url: url,
    type: method,
    data: data,
    dataType: "json",
    success: function (res) {
      console.log("Server Response:", res);

      if (res.status === "success") {
        console.log(res.data);

        $("#daftar-group").empty();

        res.data.forEach((g) => {
          const card = `
            <div class="card group">
              <h2>${g.nama}</h2>
              <p>${g.deskripsi}</p>
              <p>Dibuat pada: ${g.tanggal_dibuat}</p>
              <a href="detail-group.php?idgroup=${g.id}">Detail Group</a>
            </div>
          `;
          $("#daftar-group").append(card);
        });
      } else if (res.status === "error") {
        status.text(res.message);
        status.attr("class", "error");
      }
    },
    error: function (xhr) {
      console.error("AJAX Error:", xhr.responseText);

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }

      status.text(msg);
    },
  });
  console.groupEnd();
}
function allowDosenActions() {
  $(".dosen_only").removeClass("dosen_only");
}
