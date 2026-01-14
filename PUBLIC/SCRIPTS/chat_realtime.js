$(document).ready(function() {
    const threadId = $('#thread-container').data('id');
    const currentUser = $('#user-data').data('username'); 

    setInterval(function() {
        fetchMessages();
    }, 2000);

    fetchMessages();
    $('#form-chat').on('submit', function(e) {
        e.preventDefault();
        let message = $('#chat-input').val();
        
        if(message.trim() === "") return;

        $.ajax({
            url: '../../API/CHAT/index.php', 
            method: 'POST',
            data: {
                idthread: threadId,
                isi: message
            },
            success: function(response) {
                $('#chat-input').val(''); 
                fetchMessages(); 
            },
            error: function(err) {
                console.error("Gagal mengirim pesan", err);
            }
        });
    });

    function fetchMessages() {
        $.ajax({
            url: '../../API/CHAT/index.php',
            method: 'GET',
            data: { idthread: threadId },
            dataType: 'json',
            success: function(response) {
                renderChat(response.data);
            }
        });
    }

    function renderChat(chats) {
        let html = '';
        chats.forEach(chat => {
            let isMe = chat.username_pembuat === currentUser;
            let alignment = isMe ? 'justify-content-end' : 'justify-content-start';
            let bubbleColor = isMe ? 'bg-primary text-white' : 'bg-light text-dark border';

            html += `
                <div class="d-flex ${alignment} mb-3">
                    <div class="card p-2 ${bubbleColor}" style="max-width: 70%; border-radius: 15px;">
                        <small class="fw-bold d-block" style="font-size: 0.8rem;">
                            ${chat.nama_pembuat} </small>
                        <span>${chat.isi}</span>
                        <small class="d-block text-end mt-1" style="font-size: 0.7rem; opacity: 0.8;">
                            ${chat.tanggal_pembuatan}
                        </small>
                    </div>
                </div>
            `;
        });
        $('#chat-box').html(html);
    }
});