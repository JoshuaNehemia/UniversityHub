
function getGroupDetail(id) {
  console.group("GET GROUP DETAIL - START");
  const data = { id: id };

  const method = "GET";
  const url = API_ADDRESS + "GROUP/";

  // --- Logging Request ---
  console.group("GET GROUP DETAIL - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Data   :", data);
  console.groupEnd();

  $.ajax({
    url: url, // FIXED: this was missing in your code
    type: method,
    data: data,
    dataType: "json",

    beforeSend: function () {
      console.group("GET GROUP DETAIL - AJAX Start");
    },

    success: function (res) {
      console.log("SUCCESS:", res);

      if (res.status === "success") {
        const g = res.data;

        $("#nama-group").text(g.nama);
        $("#deskripsi-group").text(g.deskripsi);
        $("#pembuat-group").text(g.pembuat);
        $("#tanggal-pembentukan-group").text("Tanggal: " + g.tanggal_dibuat);
      } else {
        $("#nama-group").text("Group tidak ditemukan");
      }
    },

    error: function (xhr, status, error) {
      console.error("ERROR:", { xhr, status, error });

      let msg = "Terjadi kesalahan server";
      if (xhr.responseJSON?.message) {
        msg = xhr.responseJSON.message;
      }

      $("#nama-group").text(msg);
    },

    complete: function () {
      console.groupEnd(); // END AJAX log group
    },
  });
  console.groupEnd();
  console.groupEnd();
}
function getGroupMember(id) {
  console.group("GET GROUP MEMBER - START");

  const method = "GET";
  const url = API_ADDRESS + "MEMBER/" + id + "/";

  console.group("GET GROUP MEMBER - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.groupEnd();

  const list = $("#list-member-container");

  console.group("GET GROUP MEMBER - AJAX");

  $.ajax({
    url: url,
    type: method,
    dataType: "json",

    success: function (res) {
      console.log("SUCCESS:", res);

      if (res.status === "success") {
        list.empty();

        // DOSEN
        res.data.DOSEN.forEach((d) => {
          const row = `
            <div class="card member">
              <img src='http://localhost/UniversityHub/APP/DATABASE/PROFILE/DOSEN/${d.npk}.${d.foto_extension}' class="foto-member">
              <p>${d.nama_dosen}</p>
              <p>NPK: ${d.npk}</p>
              <div class="dosen_only">
                <button class="btn-remove-member" data-username="${d.username}" data-group="${id}">
                  Hapus member
                </button>
              </div>
            </div>
          `;
          list.append(row);
        });

        // MAHASISWA
        res.data.MAHASISWA.forEach((m) => {
          const row = `
            <div class="card member">
              <img src='http://localhost/UniversityHub/APP/DATABASE/PROFILE/MAHASISWA/${m.nrp}.${m.foto_extention}' class="foto-member">
              <p>${m.nama_mahasiswa}</p>
              <p>NRP: ${m.nrp}</p>
              <div class="dosen_only">
                <button class="btn-remove-member" data-username="${m.username}" data-group="${id}">
                  Hapus member
                </button>
              </div>
            </div>
          `;
          list.append(row);
        });
      } else {
        $("#status-message").text(res.message || "Tidak ada member.");
      }
    },

    error: function (xhr, status, error) {
      console.error("ERROR:", { xhr, status, error });
      let msg = xhr.responseJSON?.message || "Terjadi kesalahan server";
      $("#status-message").text(msg);
    },

    complete: function () {
      console.groupEnd(); // AJAX group
    },
  });

  console.groupEnd(); // START group
}
