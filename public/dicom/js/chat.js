// const prediction = response.data.predictions[0];
//                          const x = prediction.x * (img.naturalWidth / 412)
const myElement = document.getElementById('my-element');
myElement.style.zoom = '70% ';




document.getElementById('houssem').style.display = 'block';

var ok = -1;

function show() {
    ok =ok* -1;
    //Get the element with class "cornerstone-canvas"
    var canvas = document.querySelector('#dicomImage');

    if (ok > 0) {
        document.getElementById('houssem').style.display = 'block';
        const bgdcm = document.getElementById('bgdcm');
        bgdcm.classList.remove('col');

        bgdcm.classList.add('col-9');
        //Get the element with class "cornerstone-canvas"
        // var canvas = document.querySelector('#dicomImage');
        // Set the width of the element to 100%
        //canvas.style.width = '70%';
        //canvas.style.height = '100%';


    } else {
        document.getElementById('houssem').style.display = 'none';
        // var bgdcm = document.getElementById('bgdcm');
        //bgdcm.classList.remove('col-9');

        //bgdcm.classList.add('col');

    }

}


//send content to chat table



var loaded = false;
function loadAndViewImage(imageId) {
    var element = document.getElementById('dicomImage');
    try {
        console.log(imageId)
        cornerstone.loadImage(imageId).then(function (image) {
            console.log(image);
            var viewport = cornerstone.getDefaultViewportForImage(element, image);

            cornerstone.displayImage(element, image, viewport);


        }, function (err) {
            throw err;
        });
    }
    catch (err) {
        throw err;
    }
}

// Initialize Cornerstone tools

// Set up and activate the magnify tool





cornerstoneWADOImageLoader.external.cornerstone = cornerstone;
cornerstoneWADOImageLoader.external.dicomParser = dicomParser;
cornerstoneTools.external.cornerstoneMath = cornerstoneMath;
cornerstoneTools.external.cornerstone = cornerstone;
cornerstoneTools.init({ showSVGCursors: true });

// Enable Cornerstone on the DICOM image element
var element = document.getElementById('dicomImage');
cornerstone.enable(element);

function convertDicomToPNG() {
    // Get the loaded image
    var enabledElement = cornerstone.getEnabledElement(element);
    var image = enabledElement.image;

    // Create a virtual canvas to perform conversion
    var virtualCanvas = document.createElement('canvas');
    virtualCanvas.width = image.width;
    virtualCanvas.height = image.height;
    var virtualContext = virtualCanvas.getContext('2d');

    // Draw the DICOM image onto the virtual canvas
    cornerstone.renderToCanvas(virtualCanvas, image);

    // Convert the virtual canvas to PNG
    var imageData = virtualCanvas.toDataURL('image/png');
// Create a download link for the PNG image
/*var downloadLink = document.createElement('a');
downloadLink.href = imageData;
downloadLink.download = imagetwig+'.png';
downloadLink.textContent = 'Download PNG';
document.body.appendChild(downloadLink);
downloadLink.click();*/
    // Output the PNG data URI
    console.log(imageData);
    saveImageData(imageData);

    // Optionally, you can use the PNG data URI as needed, such as sending it to the server or performing further processing
}

function downloadpngimage()
{
 // Get the loaded image
 var enabledElement = cornerstone.getEnabledElement(element);
 var image = enabledElement.image;

 // Create a virtual canvas to perform conversion
 var virtualCanvas = document.createElement('canvas');
 virtualCanvas.width = image.width;
 virtualCanvas.height = image.height;
 var virtualContext = virtualCanvas.getContext('2d');

 // Draw the DICOM image onto the virtual canvas
 cornerstone.renderToCanvas(virtualCanvas, image);

 // Convert the virtual canvas to PNG
 var imageData = virtualCanvas.toDataURL('image/png');
// Create a download link for the PNG image
var downloadLink = document.createElement('a');
downloadLink.href = imageData;
downloadLink.download = imagetwig+'.png';
downloadLink.textContent = 'Download PNG';
document.body.appendChild(downloadLink);
downloadLink.click();
}


function saveImageData(imageData) {
    // Create a new XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/save-image.php'); // Replace with the path to your PHP script
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Image saved successfully.');
        } else {
            console.log('Error saving image:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Error saving image.');
    };

    // Get the image name from the input field
    var imagetwig = document.getElementById('image-twig').value;

    // Send the image data and image name to the server for saving
    xhr.send('imageData=' + encodeURIComponent(imageData) + '&imageName=' + encodeURIComponent(imagetwig + '.png'));
}

///
// Load and display the DICOM image
imagetwig=document.getElementById('image-twig').value;
loadAndViewImage("wadouri:http://127.0.0.1:8000/uploads/images/"+imagetwig+".dcm");



function zoom() {


    // Add our tool, and set it's mode
    const ZoomTool = cornerstoneTools.ZoomTool;

    cornerstoneTools.addTool(cornerstoneTools.ZoomTool, {});

    cornerstoneTools.setToolActive('Zoom', { mouseButtonMask: 1 })
    ////
}


function contrast() {







    const WwwcTool = cornerstoneTools.WwwcTool;

    cornerstoneTools.addTool(WwwcTool)
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 1 })



}
function rotate() {





    const RotateTool = cornerstoneTools.RotateTool;

    cornerstoneTools.addTool(RotateTool)
    cornerstoneTools.setToolActive('Rotate', { mouseButtonMask: 1 })
}

function length() {




    const LengthTool = cornerstoneTools.LengthTool;

    cornerstoneTools.addTool(LengthTool)
    cornerstoneTools.setToolActive('Length', { mouseButtonMask: 1 })

}

function scoop() {



    const MagnifyTool = cornerstoneTools.MagnifyTool;

    cornerstoneTools.addTool(MagnifyTool)
    cornerstoneTools.setToolActive('Magnify', { mouseButtonMask: 1 })
}
function pan() {




    const PanTool = cornerstoneTools.PanTool;

    cornerstoneTools.addTool(PanTool)
    cornerstoneTools.setToolActive('Pan', { mouseButtonMask: 1 })

}


function annotation() {

    const ArrowAnnotateTool = cornerstoneTools.ArrowAnnotateTool;

    cornerstoneTools.addTool(ArrowAnnotateTool)
    cornerstoneTools.setToolActive('ArrowAnnotate', { mouseButtonMask: 1 })


}

function circle() {

    var id = $('#sendcontent').data('id-image');

    var imageIds = [id]; // An array of image IDs for the stack
    var currentImageIdIndex = 0; // The index of the current image in the stack

    // Define the stack
    var stack = {
        currentImageIdIndex: currentImageIdIndex,
        imageIds: imageIds
    };

    // Set the stack
    cornerstoneTools.addStackStateManager(element, ["stack"]);
    cornerstoneTools.addToolState(element, "stack", stack);

    var CircleScissorsTool = cornerstoneTools.CircleScissorsTool;

    cornerstoneTools.addTool(CircleScissorsTool)
    cornerstoneTools.setToolActive('CircleScissors', { mouseButtonMask: 1 })

}

function stuck() {
    var id = $('#sendcontent').data('id-image');

    const scheme = 'wadouri';
    const baseUrl = 'https://localhost:7065/image/';
    const series = [
        "1.dcm","23.dcm"
    ];

    const imageIds = series.map(seriesImage => `${scheme}:${baseUrl}${seriesImage}`);

    // Add our tool, and set it's mode
    const StackScrollTool = cornerstoneTools.StackScrollTool;

    //define the stack
    const stack = {
        currentImageIdIndex: 0,
        imageIds: imageIds
    };

    // load images and set the stack
    cornerstone.loadImage(imageIds[0]).then((image) => {
        cornerstone.displayImage(element, image);
        cornerstoneTools.addStackStateManager(element, ['stack']);
        cornerstoneTools.addToolState(element, 'stack', stack);
    });

    cornerstoneTools.addTool(StackScrollTool);
    cornerstoneTools.setToolActive('StackScroll', { mouseButtonMask: 1 });

}
function FreehandRoiTool() {

    var FreehandRoiTool = cornerstoneTools.FreehandRoiTool;

    cornerstoneTools.addTool(FreehandRoiTool)
    cornerstoneTools.setToolActive('FreehandRoi', { mouseButtonMask: 1 })

}
function rectangle() {
    // Add our tool, and set it's mode
    const RectangleRoiTool = cornerstoneTools.RectangleRoiTool;

    cornerstoneTools.addTool(RectangleRoiTool)
    cornerstoneTools.setToolActive('RectangleRoi', { mouseButtonMask: 1 })
}

function EllipticalRoi() {

    const EllipticalRoiTool = cornerstoneTools.EllipticalRoiTool;

    cornerstoneTools.addTool(EllipticalRoiTool)
    cornerstoneTools.setToolActive('EllipticalRoi', { mouseButtonMask: 1 })
}

function angle()
{
    const CobbAngleTool = cornerstoneTools.CobbAngleTool;

    cornerstoneTools.addTool(CobbAngleTool)
    cornerstoneTools.setToolActive('CobbAngle', { mouseButtonMask: 1 })
}
//send content to chat table


function DragProbeTool() {
    var DragProbeTool = cornerstoneTools.DragProbeTool;

    cornerstoneTools.addTool(DragProbeTool)
    cornerstoneTools.setToolActive('DragProbe', { mouseButtonMask: 1 })

}
function region() {
    const WwwcRegionTool = cornerstoneTools.WwwcRegionTool;

    cornerstoneTools.addTool(WwwcRegionTool)
    cornerstoneTools.setToolActive('WwwcRegion', { mouseButtonMask: 1 })
}

function text() {


    // Add our tool, and set it's mode
    const TextMarkerTool = cornerstoneTools.TextMarkerTool

    // set up the markers configuration

    const configuration = {
        markers: ['F5', 'F4', 'F3', 'F2', 'F1'],
        current: 'F5',
        ascending: true,
        loop: true,
    }

    cornerstoneTools.addTool(TextMarkerTool, { configuration })
    cornerstoneTools.setToolActive('TextMarker', { mouseButtonMask: 1 })

}




ScaleOverlayTool = cornerstoneTools.ScaleOverlayTool;

cornerstoneTools.addTool(ScaleOverlayTool)
cornerstoneTools.setToolActive('ScaleOverlay', { mouseButtonMask: 1 })



$(document).ready(function() {
    console.log('Page fully loaded');
    setTimeout(function() {
        console.log('3 seconds have passed');
        convertDicomToPNG();
    }, 3000); // 3000 milliseconds = 3 seconds
});


function brain() {
    // Get the DICOM image name from the input field
    var imageTwig = document.getElementById('image-twig').value;

    // Construct the path to the PNG image by replacing ".dcm" with ".png"
    var imagePath = "http://127.0.0.1:8000/uploads/images/" + imageTwig + ".png";

    // Create a new Image element
    const img = new Image();

    // Set the source of the image to the PNG image path
    img.src = imagePath;

    // Wait for the image to load
    img.onload = () => {
        // Create a canvas element
        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;

        // Draw the image on the canvas
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);

        // Convert the canvas to a base64 string
        const imageData = canvas.toDataURL('image/png').replace(/^data:.+;base64,/, '');

        // Make the API call with the converted image
        axios({
            method: "POST",
            url: "https://detect.roboflow.com/brain-tumors-detection/1",
            params: {
                api_key: "GtgMJ3TF9bBauIJotXXs"
            },
            data: imageData,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        })
        .then(response => {
            widthimage=response.data.image.width;
            heightimage=response.data.image.height;
            const x = response.data.predictions[0].x-20;
            const y = response.data.predictions[0].y-20;
            const width = response.data.predictions[0].width;
            const height = response.data.predictions[0].height;

            console.log(x, y);
            console.log(response);

            // Create a popup window
            const popupWindow = window.open("", "Popup", "width=600,height=600");

            // Adjust the width and height of the image according to the response
            const imageWidth = width; // Width obtained from the response
            const imageHeight = height; // Height obtained from the response

            // Create an HTML structure inside the popup window to display the image and detected area
            const popupContent = `
                <div style="position: relative;">
                    <img src="${imagePath}" style="width: ${widthimage}px; height: ${heightimage}px;">
                    <div style="position: absolute; top: ${y}px; left: ${x}px; width: ${width}px; height: ${height}px; border: 1px solid green;"></div>
                </div>
            `;

            // Write the HTML content to the popup window
            popupWindow.document.write(popupContent);
        })
        .catch(error => {
            console.log(error.message);
        });
    };
}
