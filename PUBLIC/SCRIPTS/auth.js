const url = API_ADDRESS + "AUTH/";

function login(data = {}) {
  // 1. Setup Request Data
  const method = "POST";
  const requestUrl = API_ADDRESS + "AUTH/";

  // 2. Log the Request immediately
  console.group("Login Request");
  console.log("URL    :", requestUrl);
  console.log("Method :", method);
  console.log("Params :", data);
  console.groupEnd();

  var status = $("#status-message");

  $.ajax({
    url: requestUrl,
    type: method,
    data: data,
    dataType: "json",
    success: function (res) {
      // 3. Log the Success Response
      console.group("Login Response - Success");
      console.log("Data :", res);
      console.groupEnd();

      if (res.status === "success") {
        status.text("Berhasil login.");
        status.attr("class", "success");
        console.log(res.data)
        if (res.data.jenis === "ADMIN") {
          console.log("Masuk halaman admin");
          window.location.href = "ADMIN/index.php";
        } else {
          console.log("Masuk halaman user");
          window.location.href = "index.php";
        }
      } else if (res.status === "error") {
        status.text("Gagal login, " + res.message);
        status.attr("class", "error");
      }
    },
    error: function (xhr) {
      // 4. Log the Error Response
      console.group("Login Response - Error");
      console.log("Status :", xhr.status);
      console.log("Text   :", xhr.responseText);
      console.log("JSON   :", xhr.responseJSON);
      console.groupEnd();

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      status.text(msg);
    },
  });
}

function checkLoggedIn() {
  const method = "GET";
  const requestUrl = API_ADDRESS + "AUTH/";
  const data = {
    jenis: "account",
  };

  console.group("Check Logged In Request");
  console.log("URL    :", requestUrl);
  console.log("Method :", method);
  console.log("Params :", data);
  console.groupEnd();

  var status = $("#status-message");

  $.ajax({
    url: requestUrl,
    type: method,
    data: data,
    dataType: "json",
    success: function (res) {
      console.group("Check Logged In Response - Success");
      console.log("Data :", res);
      console.groupEnd();

      if (res.status === "success") {
        console.log(res.data);
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
      console.group("Check Logged In Response - Error");
      console.log("Status :", xhr.status);
      console.log("Text   :", xhr.responseText);
      console.log("JSON   :", xhr.responseJSON);
      console.groupEnd();

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      status.text(msg);
    },
  });
}

function getGroupJoinedByUser(keyword = "") {
  const method = "GET";
  const requestUrl = API_ADDRESS + "AUTH/";
  const data = {
    limit: 5,
    offset: 0,
    keyword: keyword,
    jenis: "group",
  };

  console.group("Get User Groups Request");
  console.log("URL    :", requestUrl);
  console.log("Method :", method);
  console.log("Params :", data);
  console.groupEnd();

  var status = $("#status-message");

  $.ajax({
    url: requestUrl,
    type: method,
    data: data,
    dataType: "json",
    success: function (res) {
      console.group("Get User Groups Response - Success");
      console.log("Data :", res);
      console.groupEnd();

      if (res.status === "success") {
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
      console.group("Get User Groups Response - Error");
      console.log("Status :", xhr.status);
      console.log("Text   :", xhr.responseText);
      console.log("JSON   :", xhr.responseJSON);
      console.groupEnd();

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      status.text(msg);
    },
  });
}

function allowDosenActions() {
  $(".dosen_only").removeClass("dosen_only");
}
