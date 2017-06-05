/*!
 * HTML5 FileReaderHelper v.1.0.0
 * Author: Tóth András
 * Licence: MIT
 * url: http://atandrastoth.co.uk
 */
var FileReaderHelper = function() {
    var init = function(types, readAs, callBack, multy) {
        multy = typeof multy == 'undefined' ? false : multy;
        var input = document.createElement('input');
        input.style.cssText = 'display: none;';
        input.type = "file";
        if (multy) input.multiple = true;
        document.querySelector('body').appendChild(input);
        input.addEventListener('change', selectFiles, false);
        input.click();

        function selectFiles(evt) {
            var files = (evt.target || evt.sourceElement).files;
            removeElement(input);
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.name.toLowerCase().match('(.)(.*(' + types.toLowerCase() + '))')) {
                    continue;
                }
                var reader = new FileReader();
                reader.onload = (function(theFile) {
                    return function(e) {
                        //config required data from e, theFile
                        callBack({
                            data: e.target.result,
                            name: theFile.name,
                            size: theFile.size
                        });
                    };
                })(f);
                switch (readAs) {
                    case 'dataURL':
                        reader.readAsDataURL(f);
                        break;
                    case 'binary':
                        reader.readAsBinaryString(f);
                        break;
                    case 'array':
                        reader.readAsArrayBuffer(f);
                        break;
                    default:
                        reader.readAsText(f);
                }
            }
        }
    };

    function removeElement(element) {
        element && element.parentNode && element.parentNode.removeChild(element);
    }
    return {
        Init: function(types, readAs, callBack, multy) {
            init(types, readAs, callBack, multy);
        }
    }
};