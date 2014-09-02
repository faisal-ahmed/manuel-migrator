/**
 * Created by victoryland on 8/26/14.
 */

var url = location.href;
var urlArray = url.split("?");

function getCheckedBoxes(chkboxName) {
    var checkboxes = document.getElementsByName(chkboxName);
    var checkboxesChecked = [];
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            checkboxesChecked.push(checkboxes[i]);
        }
    }
    return checkboxesChecked.length > 0 ? checkboxesChecked : null;
}

function getSelectItemValueById(id){
    var e = document.getElementById(id);
    return e.options[e.selectedIndex].value;
}

function generateUrl(field, value, status){
    var returnUrl = urlArray[0] + '?';
    if (urlArray[1] === undefined){
        returnUrl += field + '=' + value;
    } else {
        var tempUrl = urlArray[1].split("&"), flag = 0;
        for (var i = 0; i < tempUrl.length; i++) {
            if (tempUrl[i].length === 0 || tempUrl[i].indexOf('id') !== -1) continue;
            if (typeof status !== "undefined" && tempUrl[i].indexOf('status') !== -1) continue;
            if (tempUrl[i].indexOf(field) === -1) {
                returnUrl += ('&' + tempUrl[i]);
            } else {
                returnUrl += ('&' + field + '=' + value);
                flag = 1;
            }
        }
        returnUrl += (!flag) ? ('&' + field + '=' + value) : "";
        if (typeof status !== "undefined") {
            returnUrl += ('&status=' + status);
        }
    }

    return returnUrl.replace("#",'');
}

function disableModule(id){
    var disabledButton = $('#' + id + '_disable')[0].checked,
        elements = [$('#' + id + '_read'), $('#' + id + '_write'), $('#' + id + '_create'), $('#' + id + '_update'),
            $('#' + id + '_export'), $('#' + id + '_delete')], iLoop;
    if (disabledButton) {
        for (iLoop = 0; iLoop < elements.length; iLoop++) {
            applyDisableSettings(elements[iLoop], true);
        }
    } else {
        for (iLoop = 0; iLoop < elements.length; iLoop++) {
            applyDisableSettings(elements[iLoop], false);
        }
        elements[0].attr("checked", "checked");
    }
}

function applyDisableSettings(e, disabled){
    e.removeAttr("checked");
    (disabled) ? e.attr("disabled", "disabled") : e.removeAttr("disabled");
}

function moduleAccess(id, current, target){
    var elements = [$('#' + id + '_create'), $('#' + id + '_update'), $('#' + id + '_export'), $('#' + id + '_delete')], iLoop;
    var accessStatus = $('#' + id + current)[0].checked, e = $('#' + id + target);
    (accessStatus) ? e.removeAttr("checked") : e.attr("checked", "checked");
    if ((current === '_read' && accessStatus) || (current === '_write' && !accessStatus)) {
        for (iLoop = 0; iLoop < elements.length; iLoop++) {
            applyDisableSettings(elements[iLoop], true);
        }
    } else if ((current === '_read' && !accessStatus) || (current === '_write' && accessStatus)) {
        for (iLoop = 0; iLoop < elements.length; iLoop++) {
            applyDisableSettings(elements[iLoop], false);
        }
    }
}
