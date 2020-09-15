<script>
            const canvas = document.getElementById('qr-canvas');
            const context = canvas.getContext('2d');
            let autoCaptureStatus = true;
            let decodeFailures = 0;

            const constraints = {
                video: {
                    width: 400,
                    height: 400
                }
            };
            
            showDefaultCanvas();
            autoCapture();
            scanAgain();

            // Attach the video stream to the video element and autoplay.
            navigator.mediaDevices.getUserMedia(constraints)
                    .then((stream) => {
                        player.srcObject = stream;
                    });
            
            function captureSnapshotButton() {
                // Draw the video frame to the canvas.
                attemptDecodeButton.disabled = false;
                context.drawImage(player, 0, 0, canvas.width, canvas.height);
            };

            function stopCameraButton() {
                // Stop video capture.
                player.srcObject.getVideoTracks().forEach(track => track.stop());
                disableButtons();
                autoCapture = false;
                output.innerHTML = '<h2 style="color:#F00">Recargue la página para escanear.</h2>';
                showDefaultCanvas();
            };

            function attemptDecodeButton() {
                // Decode QR Code
                try {
                    decodedValue = qrcode.decode();
                    // console.log(decodedValue);
                    updateOutputValue(decodedValue);
                    // Stops scanning
                    autoCaptureStatus = false;
                    setTimeout(scanAgain, 1200);
                } catch (err) {
                    if (err !== "No se ha podido encontrar la codificación (found 0)") {
                        //throw err;
                    }
                }
            };

            function startAutoCaptureButton() {
                // Start taking snapshots to canvas
                autoCaptureStatus = true;
                decodeFailures = 0;
                autoCapture();
            };

            function stopAutoCaptureButton() {
                // Stop taking snapshots to canvas
                autoCaptureStatus = false;
            };

            function scanAgain(){
                $('#output').html("<span id='empty'><h3>Acerque el código QR al lector...</h3></span>");
                $('#output').css('background-color', 'white');
                autoCaptureStatus = true;
                autoCapture();
            };

            function autoCapture() {
                if (autoCaptureStatus) {
                    captureSnapshotButton();
                    attemptDecodeButton();
                    setTimeout(autoCapture, 100);
                }
            }

            function updateOutputValue(val) {
                /* output.innerHTML = "<h2>Decoded value: " + val + "</h2>"; */
                if(val == '')
                {

                }
                else
                {
                  separar = val.split(';');
                  abrev = separar[0];
                  enp = separar[1];
                  if(typeof enp == 'undefinded')
                  {

                  }
                  else
                  {
                    $('#output').load('index.php?ACTION=fichar-asist&criptedval='+encodeURI(val)),
                    $('.table').load(location.href + ' .table > *')
                  }
                }
            }

            function disableButtons() {
                buttons = document.getElementsByTagName("button");
                Array.from(buttons).map(button => button.disabled = true);
            }
            
            function showDefaultCanvas() {
                context.clearRect(0, 0, canvas.width, canvas.height);        
                context.font = "30px Arial";
                context.fillText("Lector QR", 55, 130);
            }

            window.setInterval(() => {
                location.reload();
            }, 300000);
        </script>