const url = API_ADDRESS + "AUTH/";

function login(data = {}) {

  const method = "POST";
  const requestUrl = API_ADDRESS + "AUTH/";

  console.group("Login Request");
  console.log("URL    :", requestUrl);
  console.log("Method :", method);
  console.log("Params :", data);
  console.groupEnd();

  var status = $("#status-message");
  var btn = $(".login-btn");

  btn.prop("disabled", true).text("Memproses...");
  status.html("");

  $.ajax({
    url: requestUrl,
    type: method,
    data: data,
    dataType: "json",
    success: function (res) {
      console.group("Login Response - Success");
      console.log("Data :", res);
      console.groupEnd();

      btn.prop("disabled", false).text("Masuk");

      if (res.status === "success") {
        window.SESSION = res.data;

        status.html(`
          <div class="alert alert-success">
            Login berhasil!
          </div>
        `);

        setTimeout(function() {
          if (res.data.jenis === "ADMIN") {
            window.location.href = "ADMIN/index.php";
          } else {
            window.location.href = "index.php";
          }
        }, 1000);
      } else {
          status.html(`
            <div class="alert alert-danger">
              ${res.message || "Username atau Password salah."}
            </div>
          `);
      }
    },
    error: function (xhr) {
      console.group("Login Response - Error");
      console.log("Status :", xhr.status);
      console.log("Text   :", xhr.responseText);
      console.log("JSON   :", xhr.responseJSON);
      console.groupEnd();

      btn.prop("disabled", false).text("Masuk");

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      status.html(`
        <div class="alert alert-warning">
          ${msg}
        </div>
      `);
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
        // if (res.data.jenis != "MAHASISWA") {
        //   allowDosenActions();
        // }
        // SIMPAN DATA USER KE SESSION GLOBAL
        window.SESSION = res.data;

        if ($("#display-name").length) {
          $("#display-name").text(res.data.nama || res.data.username);
        }

        if (res.data.jenis && res.data.jenis.toUpperCase() === "DOSEN") {
          $(".dosen_only").show(); 
          $(".dosen_only").css("display", "block"); 
        }
      } else {
        // Jika belum login, tendang ke halaman login
        window.location.href = "login.php";
      }
    },
    error: function (xhr) {
      console.error("Check Login Error", xhr);
        // Jika error server/session habis, tendang ke login
        window.location.href = "login.php";
      },
  });
}

function getGroupJoinedByUser(keyword = "") {
  const method = "GET";
  const requestUrl = API_ADDRESS + "AUTH/";
  const data = {
    limit: 10,
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
  var container = $("#daftar-group");

  container.html('<p class="text-muted">Memuat data grup...</p>');

  $.ajax({
    url: requestUrl,
    type: method,
    data: data,
    dataType: "json",
    success: function (res) {
      console.group("Get User Groups Response - Success");
      console.log("Data :", res);
      console.groupEnd();

      if (res.status === "success" && res.data.length > 0) {
        container.empty();

        res.data.forEach((g) => {
          let bgStyle = "";
          if (g.foto && g.foto !== "") {
            bgStyle = `background-image: url('${g.foto}');`;
          } else {
            bgStyle = `background: linear-gradient(135deg, var(--first-color), #3d0000);`;
          }
          const cardHtml = `
            <div class="group-card">
              <div class="group-card-header" style="${bgStyle}"></div>          
                <div class="group-card-body">
                    <h3 class="group-card-title">${g.nama}</h3>
                    <p class="group-card-desc">${g.deskripsi || 'Tidak ada deskripsi.'}</p>      
                    <div class="group-card-action">
                      <a href="detail-group.php?idgroup=${g.id}" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem;">
                        Lihat Detail
                      </a>
                    </div>
                </div>
              </div>
            `;
          container.append(cardHtml);
        });
      } else {
        container.html('<p class="text-muted">Anda belum bergabung dengan grup manapun.</p>');
      }
    },
    error: function(xhr) {
      console.error("Get Groups Error", xhr);
      container.html('<div class="alert alert-warning">Gagal memuat data grup.</div>');
    },
  });
}

// function allowDosenActions() {
//   $(".dosen_only").removeClass("dosen_only");
// }


function logout() {
  if(!confirm("Apakah Anda yakin ingin keluar?")) return;

  $.ajax({
    url: API_ADDRESS + "AUTH/",
    type: "DELETE",
    dataType: "json",
    success: function(res){
      window.location.href = "login.php";
    },
    error: function(){
      alert("Gagal logout.");
    }
  });
}
