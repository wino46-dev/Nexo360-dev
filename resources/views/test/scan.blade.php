<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detección de Documento</title>
    <script async src="https://docs.opencv.org/4.x/opencv.js"></script>
    <style>
        #video,
        #canvas {
            border: 1px solid #ccc;
            width: 100%;
            max-width: 600px;
        }

        .frame {
            position: absolute;
            border: 2px solid green;
        }

        #photo {
            margin-top: 10px;
            width: 100%;
            max-width: 600px;
        }
    </style>
</head>

<body>
    <h1>Detección de Documento</h1>
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas"></canvas>
    <img id="photo" alt="Foto del documento">
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photo = document.getElementById('photo');
        let frame, gray, edges;

        // Inicializar la cámara
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                video.srcObject = stream;
            })
            .catch((err) => console.error('Error al acceder a la cámara:', err));

        // Procesamiento con OpenCV.js
        video.addEventListener('loadeddata', () => {
            const width = video.videoWidth;
            const height = video.videoHeight;

            // Inicializar los objetos Mat con las dimensiones correctas
            frame = new cv.Mat(height, width, cv.CV_8UC4);
            gray = new cv.Mat(height, width, cv.CV_8UC1);
            edges = new cv.Mat(height, width, cv.CV_8UC1);

            // Comienza el procesamiento una vez que se carguen los datos del video
            requestAnimationFrame(processFrame);
        });

        const processFrame = () => {
            // Capturar un frame
            const cap = new cv.VideoCapture(video);
            cap.read(frame);

            // Convertir a escala de grises
            cv.cvtColor(frame, gray, cv.COLOR_RGBA2GRAY);

            // Aplicar desenfoque y detectar bordes
            cv.GaussianBlur(gray, gray, new cv.Size(5, 5), 0);
            cv.Canny(gray, edges, 50, 150);

            // Mostrar el frame procesado
            cv.imshow('canvas', frame);

            // Continuar procesando
            requestAnimationFrame(processFrame);
        };
    </script>
</body>

</html>
