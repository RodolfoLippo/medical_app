$(document).ready(function() {
    function fetchMessages(consultationId) {
        $.ajax({
            url: '../../controllers/OnlineConsultationController.php',
            type: 'GET',
            data: { action: 'getMessages', consultation_id: consultationId },
            success: function(response) {
                try {
                    const messages = JSON.parse(response);
                    let messageList = '';
                    messages.forEach(message => {
                        messageList += `<p><strong>${message.sender_name}:</strong> ${message.message}</p>`;
                    });
                    $('#messages').html(messageList);
                    $('#messages').scrollTop($('#messages')[0].scrollHeight); // Scroll to the bottom
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    alert('Invalid response from server.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching messages:', error);
                alert('Error fetching messages. Please try again.');
            }
        });
    }

    function sendMessage() {
        const consultationId = $('#consultation_id').val();
        const message = $('#messageInput').val();
        $.ajax({
            type: 'POST',
            url: '../../controllers/OnlineConsultationController.php',
            data: { action: 'sendMessage', consultation_id: consultationId, message: message },
            success: function(response) {
                console.log('Message response:', response);
                try {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        fetchMessages(consultationId);
                        $('#messageInput').val('');
                    } else {
                        console.error('Error sending message:', jsonResponse.message);
                        alert('Error sending message: ' + jsonResponse.message);
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    alert('Invalid response from server.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
                alert('Error sending message. Please try again.');
            }
        });
    }

    $('#sendMessage').on('click', function(event) {
        event.preventDefault();
        sendMessage();
    });

    $('#messageInput').on('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendMessage();
        }
    });

    const consultationId = $('#consultation_id').val();
    fetchMessages(consultationId);


    setInterval(function() {
        fetchMessages(consultationId);
    }, 1000);
});
