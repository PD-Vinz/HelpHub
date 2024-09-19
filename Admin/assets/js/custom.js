


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




// Elements
var upload = document.getElementById("upload");
var preview = document.getElementById("preview");
var avatar = document.getElementById("avatar");
var avatarName = document.getElementById("name");
var avatarNameChangeBox = document.getElementById("change-name-box");

// Avatars object to manage image list and current active image
var avatars = {
  srcList: [],
  activeKey: 0,
  
  add: function(name, src) {
    this.srcList.push({ name: name, src: src });
    this.activeKey = this.srcList.length - 1;
    return this.activeKey;
  },
  
  changeName: function(key, name) {
    if (!Number.isInteger(key)) return false;
    this.srcList[key].name = name;
    if (avatarName.dataset.key == key) {
      avatarName.textContent = name;
    }
    return name;
  },
  
  showNext: function() {
    var next = this.activeKey + 1;
    if (next >= this.srcList.length) next = 0;
    this.showByKey(next);
  },
  
  showLast: function() {
    var next = this.activeKey - 1;
    if (next < 0) next = this.srcList.length - 1;
    this.showByKey(next);
  },
  
  showByKey: function(key) {
    var item = this.srcList[key];
    if (!item) return;

    while (preview.firstChild) {
      preview.removeChild(preview.firstChild);
    }

    var img = document.createElement("img");
    img.src = item.src;
    img.className = "avatar_img--loading";
    img.onload = function() {
      img.classList.add("avatar_img");
    };
    
    avatarName.textContent = item.name;
    avatarName.setAttribute("data-key", key);
    preview.appendChild(img);
    this.activeKey = key;
  }
};

// Show initial avatar
function showAvatar(key) {
  if (!key) {
    key = avatars.activeKey;
  }
  avatars.showByKey(key);
}

// Handle file upload
upload.addEventListener("change", handleFiles, false);
function handleFiles() {
  var files = this.files;
  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    var imageType = /^image\//;

    if (!imageType.test(file.type)) {
      avatar.classList.add("avatar--upload-error");
      setTimeout(function() {
        avatar.classList.remove("avatar--upload-error");
      }, 1200);
      continue;
    }

    avatar.classList.remove("avatar--upload-error");

    var reader = new FileReader();
    reader.onload = (function(theFile) {
      return function(e) {
        var img = document.createElement("img");
        img.src = e.target.result;
        img.className = "avatar_img";

        while (preview.firstChild) {
          preview.removeChild(preview.firstChild);
        }

        preview.appendChild(img);

        var key = avatars.add(theFile.name, e.target.result);
        avatarName.textContent = theFile.name;
        avatarName.setAttribute("data-key", key);
      };
    })(file);
    reader.readAsDataURL(file);
  }
}

// Change avatar name on Enter key or blur
window.changeAvatarName = function(event, key, name) {
  if (event.keyCode != 13 && event != "blur") return;
  key = parseInt(key);
  if (!name) return;
  avatars.changeName(key, name);
  document.activeElement.blur();
  window.getSelection().removeAllRanges();
};

// Navigate avatars
window.changeAvatar = function(dir) {
  if (dir === "next") {
    avatars.showNext();
  } else {
    avatars.showLast();
  }
};

// Handle aria upload
window.handleAriaUpload = function(e, obj) {
  if (e.keyCode == 13) {
    obj.click();
  }
};

// Initial display
showAvatar();
