//image preloader
(function($)
{
    var cache = [];
    // Arguments are image paths relative to the current page.
    $.preLoadImages = function()
    {
        var args_len = arguments.length;
        for (var i = args_len; i--;)
        {
            var cacheImage = document.createElement('img');
            cacheImage.src = arguments[i];
            cache.push(cacheImage);
        }
    }
})(jQuery)





$(function()
{

    //for the Change Module drop down
    $('.coraldropdown').each(function()
    {
        $(this).parent().eq(0).hover(function()
        {
            $('.coraldropdown:eq(0)', this).slideDown(100);
        }, function()
        {
            $('.coraldropdown:eq(0)', this).slideUp(100);
        });
    });

    //for swapping menu images
    $('.rollover').hover(function() {
        var currentImg = $(this).attr('src');
        $(this).attr('src', $(this).attr('hover'));
        $(this).attr('hover', currentImg);
        
        if ($(this).attr('id') == 'menu-last'){
            var endImg = $("#menu-end").attr('src');
            $('#menu-end').attr('src', $("#menu-end").attr('hover'));
            $('#menu-end').attr('hover', endImg);
        }
        }, function() {
        var currentImg = $(this).attr('src');
        $(this).attr('src', $(this).attr('hover'));
        $(this).attr('hover', currentImg);
        
        if ($(this).attr('id') == 'menu-last'){
            var endImg = $("#menu-end").attr('src');
            $('#menu-end').attr('src', $("#menu-end").attr('hover'));
            $('#menu-end').attr('hover', endImg);
        }
        
     });
     
});





var displayInds = [];

// 1 visible, 0 hidden
function toggleDivState(divID, intDisplay)
{
    if (document.layers)
    {
        document.layers[divID].display = intDisplay ? "block" : "none";
    }
    else if (document.getElementById)
    {
        var obj = document.getElementById(divID);
        obj.style.display = intDisplay ? "block" : "none";
    }
    else if (document.all)
    {
        document.all[divID].style.display = intDisplay ? "block" : "none";
    }
}



// 1 visible, 0 hidden
function toggleDivs(platformID)
{

    imageName = "document.image_" + platformID;
    imageName.src = "images/arrowdown.gif";

    divID = 'div_' + platformID;


    if (typeof displayInds[divID] == "undefined") displayInds[divID] = 1;

    toggleDivState(divID, displayInds[divID]);

    if (displayInds[divID] == 0) displayInds[divID] = 1;
    else displayInds[divID] = 0


}


function replaceSpecial(myString)
{

    var re = new RegExp(/.?&/);

    myString = myString.replace(/\//gi, "\\/");

    return myString.replace(/\./gi, "\\.");

}




function getCheckboxValue(field)
{
    if ($('#' + field + ':checked').attr('checked'))
    {
        return 1;
    }
    else
    {
        return 0;
    }
}

function validateRequired(field, alerttxt)
{
    fieldValue = $("#" + field).val();

    if (fieldValue == null || fieldValue == "")
    {
        $("#span_error_" + field).html(alerttxt);
        $("#" + field).focus();
        return false;
    }
    else
    {
        $("#span_error_" + field).html('');
        return true;
    }
}


function validateNumber(field, alerttxt)
{
    fieldValue = $("#" + field).val();

    if (isNaN(fieldValue))
    {
        $("#span_error_" + field).html(alerttxt);
        $("#" + field).focus();
        return false;
    }
    else
    {
        $("#span_error_" + field).html('');
        return true;
    }
}



function validateDate(field, alerttxt)
{
    $("#span_error_" + field).html('');
    sDate = $("#" + field).val();

    if (sDate)
    {

        var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/
        if (re.test(sDate))
        {
            var dArr = sDate.split("/");
            var d = new Date(sDate);

            if (!(d.getMonth() + 1 == dArr[0] && d.getDate() == dArr[1] && d.getFullYear() == dArr[2]))
            {
                $("#span_error_" + field).html(alerttxt);
                $("#" + field).focus();
                return false;
            }
            else
            {
                return true;
            }

        }
        else
        {
            $("#span_error_" + field).html(alerttxt);
            $("#" + field).focus();
            return false;
        }
    }

    return true;
}





function postwith(to, p)
{
    var myForm = document.createElement("form");
    myForm.method = "post";
    myForm.action = to;
    for (var k in p)
    {
        var myInput = document.createElement("input");
        myInput.setAttribute("name", k);
        myInput.setAttribute("value", p[k]);
        myForm.appendChild(myInput);
    }
    document.body.appendChild(myForm);
    myForm.submit();
    document.body.removeChild(myForm);
}



//This prototype is provided by the Mozilla foundation and
//is distributed under the MIT license.
//http://www.ibiblio.org/pub/Linux/LICENSES/mit.license

if (!Array.prototype.indexOf)
{
    Array.prototype.indexOf = function(elt /*, from*/ )
    {
        var len = this.length;

        var from = Number(arguments[1]) || 0;
        from = (from < 0) ? Math.ceil(from) : Math.floor(from);
        if (from < 0)
            from += len;

        for (; from < len; from++)
        {
            if (from in this &&
                this[from] === elt)
                return from;
        }
        return -1;
    };
}



function isEmpty(value)
{
    var error = "";

    if (value.length == 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}


function isISSN(value)
{

    value = value.replace('-', '');

    if (value.length == 8)
    {
        var objRegExp = /^[a-zA-Z0-9]*$/;

        return (objRegExp.test(value));
    }
    else
    {
        return false;
    }

}



function isYear(value)
{

    if (value.length == 4)
    {
        var objRegExp = /^19|20[0-9][0-9]$/;

        return (objRegExp.test(value));
    }
    else
    {
        return false;
    }

}


function isNumber(value)
{

    var objRegExp = /^[0-9]*$/;
    return (objRegExp.test(value));

}