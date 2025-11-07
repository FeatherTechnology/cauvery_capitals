// =============================================================
//  mfs_core.js  — Centralized Mantra Fingerprint Detection Logic
//  Works with MFS100 / MFS101 / MFS110 / MFS200 and future versions
// =============================================================

// 🔹 Automatically detect available Mantra service URI
async function detectMFSURI() {
    const possibleUris = [
        "https://localhost:8003/",
        "http://localhost:8004/",
        "https://127.0.0.1:8003/",
        "http://127.0.0.1:8004/"
    ];

    const servicePaths = ["mfs100", "mfs101", "mfs110", "mfs200"];

    for (const base of possibleUris) {
        for (const path of servicePaths) {
            const testUrl = `${base}${path}/info`;
            try {
                const response = await fetch(testUrl, { method: 'GET' });
                if (response.ok) {
                    // console.log(`✅ Connected to: ${base}${path}/`);
                    return `${base}${path}/`;
                }
            } catch (err) {
                // silently continue checking
            }
        }
    }

    // No device found
    // Swal.fire({
    //     icon: 'error',
    //     title: 'Device Not Found',
    //     text: 'No Mantra fingerprint device detected.\nPlease connect your device and restart the application.',
    //     confirmButtonColor: '#0c70ab'
    // });

    // throw new Error('No Mantra device service found.');
}

// 🔹 Global variable to store the detected Mantra service URI
var uri = null;

// 🔹 Initialize detection once DOM is ready
$(document).ready(async function () {
    try {
        uri = await detectMFSURI();
        console.log("Using Mantra Service:", uri);
    } catch (e) {
        console.error("Device not detected:", e.message);
    }
});

// =============================================================
//  AJAX communication helpers — unified for all device versions
// =============================================================

function PostMFS100Client(method, jsonData) {
    var res;
    $.support.cors = true;
    var httpStaus = false;

    if (!uri) {
        return { httpStaus: httpStaus, err: 'Fingerprint service not initialized or device not connected.' };
    }

    $.ajax({
        type: "POST",
        async: false,
        crossDomain: true,
        url: uri + method,
        contentType: "application/json; charset=utf-8",
        data: jsonData,
        dataType: "json",
        processData: false,
        success: function (data) {
            httpStaus = true;
            res = { httpStaus: httpStaus, data: data };
        },
        error: function (jqXHR, ajaxOptions, thrownError) {
            res = { httpStaus: httpStaus, err: getHttpError(jqXHR, thrownError) };
        },
    });
    return res;
}

function GetMFS100Client(method) {
    var res;
    $.support.cors = true;
    var httpStaus = false;

    if (!uri) {
        return { httpStaus: httpStaus, err: 'Fingerprint service not initialized or device not connected.' };
    }

    $.ajax({
        type: "GET",
        async: false,
        crossDomain: true,
        url: uri + method,
        contentType: "application/json; charset=utf-8",
        processData: false,
        success: function (data) {
            httpStaus = true;
            res = { httpStaus: httpStaus, data: data };
        },
        error: function (jqXHR, ajaxOptions, thrownError) {
            res = { httpStaus: httpStaus, err: getHttpError(jqXHR, thrownError) };
        },
    });
    return res;
}

// 🔹 Handle possible HTTP and AJAX errors cleanly
function getHttpError(jqXHR, thrownError) {
    if (jqXHR.status === 0) return 'Service Unavailable. Please check if the fingerprint service is running.';
    if (jqXHR.status == 404) return 'Requested API not found on the device.';
    if (jqXHR.status == 500) return 'Internal error in fingerprint service.';
    if (thrownError === 'parsererror') return 'JSON parsing failed.';
    if (thrownError === 'timeout') return 'Device response timed out.';
    if (thrownError === 'abort') return 'Request aborted.';
    return 'Unhandled error occurred.';
}
async function CaptureMultiFinger(quality, timeout, nooffinger) {
    const endpoints = ["capturewithdeduplicate", "capturewithdedup", "capture"];
    const MFSRequest = {
        Quality: quality,
        TimeOut: timeout,
        NoOfFinger: nooffinger
    };
    const jsondata = JSON.stringify(MFSRequest);

    for (const endpoint of endpoints) {
        const res = PostMFS100Client(endpoint, jsondata);
        if (res && res.httpStaus && res.data && res.data.ErrorCode === "0") {
            console.log(`✅ Capture succeeded using: ${endpoint}`);
            return res;
        }
    }

    Swal.fire({
        icon: "error",
        title: "Capture Failed",
        text: "Could not capture fingerprint on any supported endpoint.",
        confirmButtonColor: "#0c70ab"
    });
    return null;
}
function CaptureFinger(quality, timeout) {
    var MFS100Request = {
        "Quality": quality,
        "TimeOut": timeout
    };
    var jsondata = JSON.stringify(MFS100Request);
    return PostMFS100Client("capture", jsondata);
}
function VerifyFinger(ProbFMR, GalleryFMR) {
    var MFS100Request = {
        "ProbTemplate": ProbFMR,
        "GalleryTemplate": GalleryFMR,
        "BioType": "ANSI"
    };
    var jsondata = JSON.stringify(MFS100Request);
    return PostMFS100Client("verify", jsondata);
}

// =============================================================
//  Universal Mantra Fingerprint Functions (for all MFS versions)
// =============================================================

// 🔹 Match fingerprint against gallery template
function MatchFinger(quality, timeout, GalleryFMR) {
    var MFS100Request = {
        "Quality": quality,
        "TimeOut": timeout,
        "GalleryTemplate": GalleryFMR,
        "BioType": "FMR" // or "ANSI" depending on your use
    };
    var jsondata = JSON.stringify(MFS100Request);

    // Try all possible match endpoints for compatibility
    const possibleEndpoints = ["match", "verify", "compare"];
    let response = null;

    for (let endpoint of possibleEndpoints) {
        response = PostMFS100Client(endpoint, jsondata);
        if (response?.httpStaus && response.data?.ErrorCode === "0") {
            console.log(`✅ Match successful using endpoint: ${endpoint}`);
            return response;
        }
    }

    console.error("❌ Match failed on all endpoints");
    return response;
}

// 🔹 Get PID data (used for Aadhaar eKYC integration)
function GetPidData(BiometricArray) {
    // The request format for PID/RBD is consistent across versions
    var req = { "BiometricArray": BiometricArray };
    var jsondata = JSON.stringify(req);

    // Try all possible PID endpoints (older/newer Mantra RD Services)
    const possibleEndpoints = ["getpiddata", "piddata", "getpid"];
    let response = null;

    for (let endpoint of possibleEndpoints) {
        response = PostMFS100Client(endpoint, jsondata);
        if (response?.httpStaus && response.data?.ErrorCode === "0") {
            console.log(`✅ PID Data fetched via: ${endpoint}`);
            return response;
        }
    }

    console.error("❌ PID Data request failed on all endpoints");
    return response;
}

// 🔹 Get RBD data (for registered biometric data)
function GetRbdData(BiometricArray) {
    var req = { "BiometricArray": BiometricArray };
    var jsondata = JSON.stringify(req);

    // Try all possible RBD endpoints for version compatibility
    const possibleEndpoints = ["getrbddata", "rbddata", "getrbd"];
    let response = null;

    for (let endpoint of possibleEndpoints) {
        response = PostMFS100Client(endpoint, jsondata);
        if (response?.httpStaus && response.data?.ErrorCode === "0") {
            console.log(`✅ RBD Data fetched via: ${endpoint}`);
            return response;
        }
    }

    console.error("❌ RBD Data request failed on all endpoints");
    return response;
}
