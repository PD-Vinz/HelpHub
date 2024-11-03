


/*=============================================================
    Authour URI: www.binarycart.com
    License: Commons Attribution 3.0

    http://creativecommons.org/licenses/by/3.0/

    100% To use For Personal And Commercial Use.
    IN EXCHANGE JUST GIVE US CREDITS AND TELL YOUR FRIENDS ABOUT US
   
    ========================================================  */


(function ($) {
    "use strict";
    var mainApp = {

        main_fun: function () {
            /*====================================
            METIS MENU 
            ======================================*/
            $('#main-menu').metisMenu();

            /*====================================
              LOAD APPROPRIATE MENU BAR
           ======================================*/
            $(window).bind("load resize", function () {
                if ($(this).width() < 768) {
                    $('div.sidebar-collapse').addClass('collapse')
                } else {
                    $('div.sidebar-collapse').removeClass('collapse')
                }
            });

            /*====================================
            MORRIS BAR CHART
         ======================================*/
            Morris.Bar({
                element: 'morris-bar-chart',
                data: [{
                    y: '2006',
                    a: 100,
                    b: 90
                }, {
                    y: '2007',
                    a: 75,
                    b: 65
                }, {
                    y: '2008',
                    a: 50,
                    b: 40
                }, {
                    y: '2009',
                    a: 75,
                    b: 65
                }, {
                    y: '2010',
                    a: 50,
                    b: 40
                }, {
                    y: '2011',
                    a: 75,
                    b: 65
                }, {
                    y: '2012',
                    a: 100,
                    b: 90
                }],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Series A', 'Series B'],
                hideHover: 'auto',
                resize: true
            });

            /*====================================
          MORRIS DONUT CHART
       ======================================*/
            Morris.Donut({
                element: 'morris-donut-chart',
                data: [{
                    label: "Download Sales",
                    value: 12
                }, {
                    label: "In-Store Sales",
                    value: 30
                }, {
                    label: "Mail-Order Sales",
                    value: 20
                }],
                resize: true
            });

            /*====================================
         MORRIS AREA CHART
      ======================================*/

            Morris.Area({
                element: 'morris-area-chart',
                data: [{
                    period: '2010 Q1',
                    iphone: 2666,
                    ipad: null,
                    itouch: 2647
                }, {
                    period: '2010 Q2',
                    iphone: 2778,
                    ipad: 2294,
                    itouch: 2441
                }, {
                    period: '2010 Q3',
                    iphone: 4912,
                    ipad: 1969,
                    itouch: 2501
                }, {
                    period: '2010 Q4',
                    iphone: 3767,
                    ipad: 3597,
                    itouch: 5689
                }, {
                    period: '2011 Q1',
                    iphone: 6810,
                    ipad: 1914,
                    itouch: 2293
                }, {
                    period: '2011 Q2',
                    iphone: 5670,
                    ipad: 4293,
                    itouch: 1881
                }, {
                    period: '2011 Q3',
                    iphone: 4820,
                    ipad: 3795,
                    itouch: 1588
                }, {
                    period: '2011 Q4',
                    iphone: 15073,
                    ipad: 5967,
                    itouch: 5175
                }, {
                    period: '2012 Q1',
                    iphone: 10687,
                    ipad: 4460,
                    itouch: 2028
                }, {
                    period: '2012 Q2',
                    iphone: 8432,
                    ipad: 5713,
                    itouch: 1791
                }],
                xkey: 'period',
                ykeys: ['iphone', 'ipad', 'itouch'],
                labels: ['iPhone', 'iPad', 'iPod Touch'],
                pointSize: 2,
                hideHover: 'auto',
                resize: true
            });

            /*====================================
    MORRIS LINE CHART
 ======================================*/
            Morris.Line({
                element: 'morris-line-chart',
                data: [{
                    y: '2006',
                    a: 100,
                    b: 90
                }, {
                    y: '2007',
                    a: 75,
                    b: 65
                }, {
                    y: '2008',
                    a: 50,
                    b: 40
                }, {
                    y: '2009',
                    a: 75,
                    b: 65
                }, {
                    y: '2010',
                    a: 50,
                    b: 40
                }, {
                    y: '2011',
                    a: 75,
                    b: 65
                }, {
                    y: '2012',
                    a: 100,
                    b: 90
                }],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Series A', 'Series B'],
                hideHover: 'auto',
                resize: true
            });
           
     
        },

        initialization: function () {
            mainApp.main_fun();

        }

    }
    // Initializing ///

    $(document).ready(function () {
        mainApp.main_fun();
    });

}(jQuery));


//form//
window.onload = function () {
    document.getElementById("download")
        .addEventListener("click", () => {
            const Form = this.document.getElementById("invoice");
            console.log(invoice);
            console.log(window);
            var opt = {
                margin: 1,
                filename: 'ID Printing Classlist Form.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
            };
            html2pdf().from(invoice).set(opt).save();
        })
}


/*
//----------Change Profile Updated-------------//
function displayImg2(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $('#cimg2').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
  }
}

$(document).ready(function() {
  $('#issue-form').on('submit', function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
          url: 'ticket-submit.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
              alert('Form submitted successfully');
          },
          error: function() {
              alert('Form submission failed');
          }
      });
  });
});
*/



//change profile//

// Get elements
const upload = document.getElementById("upload");
const preview = document.getElementById("preview");
const avatar = document.getElementById("avatar");
const maxSize = 6 * 1024 * 1024; // 6MB size limit
const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg']; // Allowed file types

// Update image preview
const updateImagePreview = (file) => {
    // Clear previous image preview
    while (preview.firstChild) {
        preview.removeChild(preview.firstChild);
    }

    // Create a FileReader to read the file
    const reader = new FileReader();
    reader.onload = (e) => {
        const img = document.createElement("img");
        img.src = e.target.result;
        img.className = "avatar_img"; // Apply class to the image
        preview.appendChild(img);
    };

    // Read the image file as a data URL
    reader.readAsDataURL(file);
};

// Validate file size and type
const validateFile = (file) => {
    const sizeError = document.getElementById('sizeError');
    const typeError = document.getElementById('typeError');

    if (file.size > maxSize) {
        sizeError.textContent = 'File size exceeds 6MB limit.';
        return false;
    } else {
        sizeError.textContent = '';
    }

    if (!allowedTypes.includes(file.type)) {
        typeError.textContent = 'Only PNG, JPG, and JPEG files are allowed.';
        return false;
    } else {
        typeError.textContent = '';
    }

    return true;
};

// Handle file selection
upload.addEventListener("change", (e) => {
    const file = upload.files[0];
    if (file && validateFile(file)) {
        updateImagePreview(file);
    } else {
        // Reset file input if validation fails
        upload.value = '';
    }
});

// Reset event
avatar.addEventListener("reset", () => {
    // Clear preview and reset error messages
    while (preview.firstChild) {
        preview.removeChild(preview.firstChild);
    }
    document.getElementById('sizeError').textContent = '';
    document.getElementById('typeError').textContent = '';
});

// Submit event
avatar.addEventListener("submit", (e) => {
    e.preventDefault();
    const myFile = upload.files;
    console.log(myFile); // You can handle file submission here
});

