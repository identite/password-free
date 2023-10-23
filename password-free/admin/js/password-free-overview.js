const showActivationPopup = overviewVariables['showActivationPopup'];
let showSyncPopup = overviewVariables['showSyncPopup'];
const showProcessingNotice = overviewVariables['showProcessingNotice'];
let showSyncFailPopup = overviewVariables['showSyncFailPopup'];
const innerKey = overviewVariables['key'];
const syncStatusUrl = overviewVariables['syncStatusUrl'];
const syncStatusParam = overviewVariables['syncStatus'];

const overlay = document.getElementById("password-free-overview-overlay");

let timerId

function onloadOverviewActions() {
    if (showActivationPopup) {
        document.getElementById('password-free-activation-popup-id').style.display = 'block';
        overlay.style.display = 'block';
    }
    if (syncStatusParam == 'Processing') {
        if (showSyncPopup && !showActivationPopup) {
            document.getElementById('password-free-synchronization-popup-id').style.display = 'flex';
            overlay.style.display = 'block';
        } else if (showProcessingNotice) {
            document.getElementById('password-free-overview-synchronization-process-popup').style.display = 'block';
        }
        timerId = setInterval(syncInterval, 2000);
    } else if (syncStatusParam === 'Failed' || syncStatusParam === 'Interrupted') {
        if (showSyncFailPopup && !showActivationPopup) {
            overlay.style.display = 'block';
            document.getElementById("password-free-synchronization-fail-popup-id").style.display = 'flex';
        }
        document.getElementById('password-free-synchronization-fail-block-id').style.display = 'flex';
    }
}

onloadOverviewActions();

function syncInterval() {
    const syncStatus = getSynchronizationStatus();
    if (syncStatus === 'Successful') {
        const syncPopup = document.getElementById("password-free-synchronization-popup-id")
        if (syncPopup != null) {
            syncPopup.style.display = 'none';
        }
        if (!showActivationPopup) {
            overlay.style.display = 'none';
        }
        showSyncPopup = false;
        document.getElementById('password-free-overview-synchronization-process-popup').style.display = 'none';
        const checkbox = document.getElementById('password-free-overview-switch');
        document.getElementById('password-free-overview-switch').checked = true;
        document.getElementById('password-free-overview-default-button-id').style.pointerEvents = 'auto';
        showSyncSuccessPopup();
        clearInterval(timerId)
    } else if (syncStatus === 'Failed' || syncStatus === 'Interrupted') {
        if (showActivationPopup) {
            showSyncPopup = false;
            showSyncFailPopup = true;
        } else {
            const syncPopup = document.getElementById("password-free-synchronization-popup-id");
            if (syncPopup.style.display != '') {
                syncPopup.style.display = 'none';
                overlay.style.display = 'none';
            }
            const syncFailPopup = document.getElementById('password-free-synchronization-fail-popup-id');
            if (syncFailPopup.style.display == '') {
                syncFailPopup.style.display = 'flex';
                overlay.style.display = 'block';
            }
            document.getElementById('password-free-overview-synchronization-process-popup').style.display = 'none';
        }
        clearInterval(timerId);
    }
}

function copyText(text) {
    var copyTextarea = document.createElement("textarea");
    copyTextarea.textContent = text;
    document.body.appendChild(copyTextarea);
    copyTextarea.select();
    document.execCommand("copy");
    document.body.removeChild(copyTextarea);
    document.getElementById("password-free-overview-popup-copy").style.display = "block";
    setTimeout(closeCopyShortPopup, 2000);
}

function openHelpPopUp() {
    document.getElementById("password-free-overview-popup-help").style.display = "block";
}

function closeHelpPopUp() {
    document.getElementById("password-free-overview-popup-help").style.display = "none";
}

function openHelpPopUpRegisterError() {
    document.getElementById("password-free-overview-popup-help-register-error").style.display = "block";
}

function closeHelpPopUpRegisterError() {
    document.getElementById("password-free-overview-popup-help-register-error").style.display = "none";
}

function closeCopyShortPopup() {
    document.getElementById("password-free-overview-popup-copy").style.display = "none";
}

function closeSuccessShortPopup() {
    document.getElementById("password-free-overview-synchronization-success-popup").style.display = "none";
}

function showDropdown() {
    new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                document.getElementById("password-free-overview-dropdown-context-id").style.display = "none";
                document.getElementById("password-free-overview-dropdown-arrow-id").style.transform = "rotate(0deg)";
            } else {
                document.getElementById("password-free-overview-dropdown-context-id").style.display = "block";
                document.getElementById("password-free-overview-dropdown-arrow-id").style.transform = "rotate(180deg)";
            }
            obs.unobserve(entry.target);
        });
    }).observe(document.querySelector('.password-free-overview-dropdown-context'));
}

function toggleClick(url, key) {
    const checkbox = document.getElementById('password-free-overview-switch');
    const checked = checkbox.checked ? 0 : 1;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
    };
    xmlhttp.open("POST", url + checked, true);
    xmlhttp.setRequestHeader('Authorization', key)
    xmlhttp.send();
}

function checkboxClick(url, key) {
    const checkbox = document.getElementById('password-free-overview-checkbox');
    const checked = checkbox.checked ? 0 : 1;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
    };
    xmlhttp.open("POST", url + checked, true);
    xmlhttp.setRequestHeader('Authorization', key)
    xmlhttp.send();
}

function closeSynchronizationPopup(urlClosePopup, key, urlGetSyncStatus) {
    const syncStatus = getSynchronizationStatus();
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
    };
    xmlhttp.open("POST", urlClosePopup, true);
    xmlhttp.setRequestHeader('Authorization', key)
    xmlhttp.send(syncStatus);
    const overlay = document.getElementById("password-free-overview-overlay");
    overlay.style.display = 'none';
    document.getElementById("password-free-synchronization-popup-id").style.display = 'none';
    if (syncStatus === 'Processing') {
        document.getElementById("password-free-overview-synchronization-process-popup").style.display = 'block';
    } else if (syncStatus === 'Successful') {
        showSyncSuccessPopup();
    } else if (syncStatus === 'Failed' || syncStatus === 'Interrupted') {
        document.getElementById("password-free-synchronization-fail-popup-id").style.display = 'flex';
        overlay.style.display = 'block';
    }
}

function showSyncSuccessPopup() {
    document.getElementById("password-free-overview-synchronization-success-popup").style.display = 'block';
    setTimeout(closeSuccessShortPopup, 2000);
}

function closeSyncSuccessPopup() {
    document.getElementById("password-free-overview-synchronization-success-popup").style.display = 'none';
}

function closeSynchronizationFailPopup(url) {
    document.getElementById("password-free-overview-overlay").style.display = 'none';
    document.getElementById("password-free-synchronization-fail-popup-id").style.display = 'none';
    document.getElementById('password-free-synchronization-fail-block-id').style.display = 'flex';
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
    };
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader('Authorization', innerKey)
    xmlhttp.send();
}

function redirectSynchronizationFailPopup() {
    window.open("https://nopass.atlassian.net/servicedesk/customer/portal/2/group/11/create/29", '_blank');
}

function getSynchronizationStatus() {
    let response
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function () {
        if (xmlhttp.readyState == XMLHttpRequest.DONE && xmlhttp.status == 200) {
            response = JSON.parse(xmlhttp.responseText);
        }
    }
    xmlhttp.open("GET", syncStatusUrl, false);
    xmlhttp.setRequestHeader('Authorization', innerKey)
    xmlhttp.send();
    return response;
}

function closeActivationPopup(url) {
    overlay.style.display = 'none';
    document.getElementById("password-free-activation-popup-id").style.display = 'none';
    if (showSyncPopup) {
        document.getElementById("password-free-synchronization-popup-id").style.display = 'flex';
        overlay.style.display = 'block';
    }
    if (showSyncFailPopup) {
        document.getElementById("password-free-synchronization-fail-popup-id").style.display = 'flex';
        overlay.style.display = 'block';
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
    };
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader('Authorization', innerKey)
    xmlhttp.send();
}