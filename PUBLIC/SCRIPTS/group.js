let IS_OWNER = false;

function getGroupDetail(id, callback = null) {
  console.group("GET GROUP DETAIL - START");
  const data = { id: id };

  const method = "GET";
  const url = API_ADDRESS + "GROUP/";

  console.group("GET GROUP DETAIL - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Data   :", data);
  console.groupEnd();

  $.ajax({
    url: url, 
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
        
        const currentUser = SESSION.username;
        const currentRole = SESSION.jenis;
        IS_OWNER = (currentUser === g.pembuat) || currentRole === "ADMIN";

        if (IS_OWNER) {
            $(".dosen_only").show();
            $("#kode-group-wrapper").removeClass("hidden");
            $("#kode-group").text(g.kode);
            $("#member-action").addClass("hidden");
        } else {
            $(".dosen_only").hide();
            $("#kode-group-wrapper").addClass("hidden");
            $("#member-action").removeClass("hidden");
          }

        if (callback) callback(g);

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
      console.groupEnd(); 
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

        if (IS_OWNER) {
          $(".dosen_only").show();
        } else {
          $(".dosen_only").hide();
        }

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
      console.groupEnd();
    },
  });

  console.groupEnd();
}

function getGroupEvent(id, offset = 0, keyword = "") {
  console.group("GET GROUP EVENT - START");

  const method = "GET";
  const url = API_ADDRESS + "EVENT/" + id + "/";
  const data = {
    limit: 5,
    offset: 0,
    keyword: keyword,
  };

  console.group("GET GROUP EVENT - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.groupEnd();

  const list = $("#list-event-container");

  console.group("GET GROUP EVENT - AJAX");

  $.ajax({
    url: url,
    type: method,
    data: data,
    dataType: "json",

    success: function (res) {
      console.log("SUCCESS:", res);

      if (res.status === "success") {
        list.empty();

        if (IS_OWNER) {
          $(".dosen_only").show();
        } else {
          $(".dosen_only").hide();
        }


        // DOSEN
        res.data.forEach((d) => {
          const row = `
            <div class="card event">
              <img src='http://localhost/UniversityHub/APP/DATABASE/EVENT/${d.id}.${d.poster_extention}' class="foto-event">
              <p>${d.judul}</p>
              <p>${d.jenis}</p>
              <p>Tanggal: ${d.tanggal}</p>
              <div class="dosen_only">
                <button class="btn-remove-event" data-id="${d.id}" data-group="${id}">
                  Hapus event
                </button>
                <button type="button" class="btn-edit-event" data-id="${d.id}" >
                    Edit Event
                </button>
              </div>
            </div>
          `;
          list.append(row);
        });
      } else {
        $("#status-message").text(res.message || "Tidak ada EVENT.");
      }
    },

    error: function (xhr, status, error) {
      console.error("ERROR:", { xhr, status, error });
      let msg = xhr.responseJSON?.message || "Terjadi kesalahan server";
      $("#status-message").text(msg);
    },

    complete: function () {
      console.groupEnd(); 
    },
  });

  console.groupEnd(); 
}

function removeMember(idgroup, username) {
  console.group("REMOVE MEMBER - START");

  const method = "DELETE";
  const url = API_ADDRESS + "MEMBER/" + idgroup + "/";
  const data = JSON.stringify({ username: username });

  console.group("REMOVE MEMBER - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Payload:", data);
  console.groupEnd();

  console.group("REMOVE MEMBER - AJAX");

  $.ajax({
    url: url,
    type: method,
    data: data,
    dataType: "json",

    success: function (res) {
      console.log("SUCCESS:", res);

      if (res.status === "success") {
        $("#status-message").text("Member berhasil dihapus.");

        // Reload member list
        getGroupMember(idgroup);
      } else {
        $("#status-message").text(res.message || "Gagal menghapus member.");
      }
    },

    error: function (xhr, status, error) {
      console.error("ERROR:", { xhr, status, error });
      let msg = xhr.responseJSON?.message || "Terjadi kesalahan server";
      $("#status-message").text(msg);
    },

    complete: function () {
      console.groupEnd();
    },
  });

  console.groupEnd();
}

function removeEvent(idgroup, idevent) {
  console.group("REMOVE EVENT - START");

  const method = "DELETE";
  const url = API_ADDRESS + "EVENT/" + idgroup + "/";
  const data = JSON.stringify({ idevent: idevent });

  console.group("REMOVE EVENT - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Payload:", data);
  console.groupEnd();

  console.group("REMOVE EVENT - AJAX");

  $.ajax({
    url: url,
    type: method,
    data: data,
    dataType: "json",

    success: function (res) {
      console.log("SUCCESS:", res);

      if (res.status === "success") {
        $("#status-message").text("Event berhasil dihapus.");

        // Refresh event list after delete
        loadEventPage();
      } else {
        $("#status-message").text(res.message || "Gagal menghapus event.");
      }
    },

    error: function (xhr, status, error) {
      console.error("ERROR:", { xhr, status, error });
      let msg = xhr.responseJSON?.message || "Terjadi kesalahan server";
      $("#status-message").text(msg);
    },

    complete: function () {
      console.groupEnd(); 
    },
  });

  console.groupEnd(); 
}

function removeGroup(idgroup) {
  console.group("REMOVE GROUP - START");

  const method = "DELETE";
  const url = API_ADDRESS + "GROUP/";
  const data = JSON.stringify({ id: idgroup });

  console.group("REMOVE GROUP - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Payload:", data);
  console.groupEnd();

  console.group("REMOVE GROUP - AJAX");

  $.ajax({
    url: url,
    type: method,
    data: data,
    contentType: "application/json",
    dataType: "json",

    success: function (res) {
      console.log("SUCCESS:", res);

      if (res.status === "success") {
        alert("Group berhasil dihapus.");
        window.location.href = "index.php";
      } else {
        $("#status-message").text(res.message || "Gagal menghapus group.");
      }
    },

    error: function (xhr, status, error) {
      console.error("ERROR:", { xhr, status, error });
      let msg = xhr.responseJSON?.message || "Terjadi kesalahan server";
      $("#status-message").text(msg);
    },

    complete: function () {
      console.groupEnd(); 
    },
  });

  console.groupEnd();
}

function leaveGroup(idgroup) {
  console.group("LEAVE GROUP - START");

  const method = "DELETE";
  const url = API_ADDRESS + "MEMBER/" + idgroup + "/?action=leave";
  const data = JSON.stringify({});

  console.group("LEAVE GROUP - Sending Request");
  console.log("Method :", method);
  console.log("URL    :", url);
  console.log("Payload:", data);
  console.groupEnd();

  console.group("LEAVE GROUP - AJAX");

  $.ajax({
    url: url,
    type: method,
    data: data,
    dataType: "json",

    success: function (res) {
      console.log("SUCCESS:", res);

      if (res.status === "success") {

        alert("Anda telah keluar dari group.");
        window.location.href = "index.php";
      } else {
        $("#status-message").text(res.message || "Gagal keluar dari group.");
      }
    },

    error: function (xhr) {
      console.error("ERROR:", xhr);
      let msg = xhr.responseJSON?.message || "Terjadi kesalahan server";
      $("#status-message").text(msg);
    },

    complete: function () {
      console.groupEnd();
    }
  });

  console.groupEnd();
}

$('#search-mhs').on('keyup', function() {
    let keyword = $(this).val();
    if(keyword.length < 3) return; 

    $.ajax({
        url: '../API/MEMBER/index.php', 
        method: 'GET',
        data: { search: keyword }, 
        success: function(response) {
            let rows = '';
            response.data.forEach(mhs => {
                rows += `
                    <tr>
                        <td>${mhs.nrp}</td>
                        <td>${mhs.nama}</td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-add-member" 
                                data-nrp="${mhs.nrp}">
                                Tambah
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#result-mhs').html(rows);
        }
    });
});

// Event klik tombol Tambah
$(document).on('click', '.btn-add-member', function() {
    let nrp = $(this).data('nrp');
    let idgrup = $('#idgrup').val(); 

    $.post('../API/JOIN/index.php', { nrp: nrp, idgrup: idgrup }, function(res) {
        alert('Berhasil menambahkan anggota!');
        location.reload();
    }).fail(function() {
        alert('Gagal menambahkan anggota.');
    });
});



