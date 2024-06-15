// video_call.js

$(document).ready(function() {
    let localStream;
    let remoteStream;
    let peerConnection;
    const configuration = {
        'iceServers': [
            { 'urls': 'stun:stun.l.google.com:19302' }
        ]
    };

    $('#startCall').on('click', async function() {
        $('#video-call').removeClass('hidden');
        try {
            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            document.getElementById('localVideo').srcObject = localStream;
        } catch (videoError) {
            console.warn('Video not available, trying audio only.', videoError);
            try {
                localStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                document.getElementById('localVideo').style.display = 'none';
                $('#remoteVideo').addClass('w-full').removeClass('mt-2');
            } catch (audioError) {
                console.error('Audio not available.', audioError);
                alert('Error accessing media devices: ' + audioError.message);
                return;
            }
        }
        
        peerConnection = new RTCPeerConnection(configuration);
        peerConnection.addStream(localStream);

        peerConnection.onaddstream = function(event) {
            remoteStream = event.stream;
            document.getElementById('remoteVideo').srcObject = remoteStream;
        };

        peerConnection.onicecandidate = function(event) {
            if (event.candidate) {
                sendSignal('candidate', event.candidate);
            }
        };

        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);
        sendSignal('offer', offer);
    });

    $('#endCall').on('click', function() {
        if (peerConnection) {
            peerConnection.close();
        }
        $('#video-call').addClass('hidden');
    });

    async function sendSignal(type, data) {
        $.ajax({
            type: 'POST',
            url: '../../controllers/SignalController.php',
            data: { type: type, data: JSON.stringify(data) },
            success: function(response) {
                console.log('Signal response:', response);
            },
            error: function(xhr, status, error) {
                console.error('Signal error:', error);
            }
        });
    }
});
