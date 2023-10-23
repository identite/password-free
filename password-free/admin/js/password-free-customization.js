let isDefaultArray = false;
let isChanged = false;
let alignmentIdParam = '';
let logoIdParam = '';
let logoSrcParam = '';
let defaultBackgroundColorParam = '';
let defaultCornerRadiusParam = '';
let defaultBorderThicknessParam = '';
let defaultBorderColorParam = '';
let defaultTextFontParam = '';
let defaultTextSizeParam = '';
let defaultTextBoldParam = false;
let defaultTextItalicParam = false;
let defaultTextUnderlineParam = false;
let defaultTextColorParam = '';
let hoverBackgroundColorParam = '';
let hoverCornerRadiusParam = '';
let hoverBorderThicknessParam = '';
let hoverBorderColorParam = '';
let hoverTextFontParam = '';
let hoverTextSizeParam = '';
let hoverTextBoldParam = false;
let hoverTextItalicParam = false;
let hoverTextUnderlineParam = false;
let hoverTextColorParam = '';
let registrURL = '';
let authURL = '';

let fonts = '';
let outUrl = '';
let isInterceptRedirect = true;
const passwordFree = 'passwordFreeCustomization';

if (localStorage[passwordFree] !== undefined) {
    localStorage.removeItem(passwordFree);
    window.location.reload();
}

navigation.addEventListener("navigate", (event) => {
    if (isChanged && isInterceptRedirect) {
        outUrl = new URL(event.destination.url).href;
        event.preventDefault();
        document.getElementById('password-free-customization-overlay').style.display = 'flex';
        document.getElementById('password-free-customization-change-popup-id').style.display = 'flex';
    }
    ;
});

function alignmentItems(array, id, isFilling) {
    array.forEach(item => {
        if (item.id === id) {
            alignmentIdParam = item.id;
            chooseAlignmentElement(document.getElementById(item.item_id), document.getElementById(item.wrapper_id));
        } else {
            stopChoosingAlignmentElement(document.getElementById(item.item_id), document.getElementById(item.wrapper_id));
        }
        if (!isFilling) {
            pfOnChangeElement();
        }
    })
}

function logoItems(array, id, isFilling) {
    array.forEach(item => {
        if (item.id === id) {
            logoIdParam = item.id;
            logoSrcParam = item.img;
            document.querySelectorAll('.password-free-button img').forEach(element => {
                element.src = logoSrcParam;
            })
            document.querySelectorAll('.password-free-button-hover img').forEach(element => {
                element.src = logoSrcParam;
            })
            chooseLogoElement(document.getElementById(item.item_id), document.getElementById(item.wrapper_id));
        } else {
            stopChoosingLogoElement(document.getElementById(item.item_id), document.getElementById(item.wrapper_id));
        }
        if (!isFilling) {
            pfOnChangeElement();
        }
    })
}

function defaultBackgroundColor(element) {
    defaultBackgroundColorParam = element.value;
    const input = document.getElementById('password-free-default-background-color-input-id');
    input.value = defaultBackgroundColorParam;
    changeColorFieldSize(input);
    document.querySelectorAll('.password-free-button').forEach(el => {
        el.style.background = defaultBackgroundColorParam;
    })
    colorInputErrorSolved(input)
    pfOnChangeElement();
}

function defaultBackgroundColorInput(element) {
    if (element.value == '') {
        element.value = '#ffffff';
    }
    if (CSS.supports('color', element.value) == '') {
        colorInputError(element)
    } else {
        colorInputErrorSolved(element)
        defaultBackgroundColorParam = element.value;
        document.getElementById('password-free-default-background-color-id').value = defaultBackgroundColorParam;
        document.querySelectorAll('.password-free-button').forEach(el => {
            el.style.background = defaultBackgroundColorParam;
        })
    }
    pfOnChangeElement();
}

function defaultCornerRadius(element) {
    if (event.data !== undefined && isNaN(event.data)) {
        element.value = defaultCornerRadiusParam;
    } else if (element.value > 1000) {
        element.value = 1000;
    }
    defaultCornerRadiusParam = element.value;
    document.querySelectorAll('.password-free-button').forEach(el => {
        el.style.borderRadius = defaultCornerRadiusParam + 'px';
    })
    pfOnChangeElement();
}

function defaultCornerRadiusChange(element) {
    if (element.value == '') {
        element.value = 0;
        defaultCornerRadiusParam = element.value;
        document.querySelectorAll('.password-free-button').forEach(el => {
            el.style.borderRadius = defaultCornerRadiusParam + 'px';
        })
    }
    pfOnChangeElement();
}

function defaultBorderThickness(element) {
    if (event.data !== undefined && isNaN(event.data)) {
        element.value = defaultBorderThicknessParam;
    } else if (element.value > 1000) {
        element.value = 1000;
    }
    defaultBorderThicknessParam = element.value;
    document.querySelectorAll('.password-free-button').forEach(el => {
        el.style.borderWidth = defaultBorderThicknessParam + 'px';
    })
    pfOnChangeElement();
}

function defaultBorderThicknessChange(element) {
    if (element.value == '') {
        element.value = 0;
        defaultBorderThicknessParam = element.value;
        document.querySelectorAll('.password-free-button').forEach(el => {
            el.style.borderWidth = defaultBorderThicknessParam + 'px';
        })
    }
    pfOnChangeElement();
}

function defaultBorderColorInput(element) {
    if (element.value == '') {
        element.value = '#ffffff';
    }
    if (CSS.supports('color', element.value) == '') {
        colorInputError(element)
    } else {
        colorInputErrorSolved(element)
        defaultBorderColorParam = element.value;
        document.getElementById('password-free-default-border-color-id').value = defaultBorderColorParam;
        document.querySelectorAll('.password-free-button').forEach(el => {
            el.style.borderColor = defaultBorderColorParam;
        })
    }
    pfOnChangeElement();
}

function defaultBorderColor(element) {
    defaultBorderColorParam = element.value;
    const input = document.getElementById('password-free-default-border-color-input-id');
    input.value = defaultBorderColorParam;
    changeColorFieldSize(input);
    document.querySelectorAll('.password-free-button').forEach(el => {
        el.style.borderColor = defaultBorderColorParam;
    })
    colorInputErrorSolved(input);
    pfOnChangeElement();
}

function defaultTextFont(element) {
    defaultTextFontParam = element.value;
    document.querySelectorAll('.password-free-button').forEach(div => {
        div.style.fontFamily = fonts[defaultTextFontParam];
    })
    pfOnChangeElement();
}

let sss = '';

function defaultTextSize(element) {
    if (event.data !== undefined && isNaN(event.data)) {
        element.value = defaultTextSizeParam;
    } else if (element.value > 1000) {
        element.value = 1000;
    } else if (element.value[0] == 0) {
        element.value = 1;
    }
    defaultTextSizeParam = element.value;
    document.querySelectorAll('.password-free-button').forEach(div => {
        div.style.fontSize = defaultTextSizeParam + 'px';
        div.style.lineHeight = defaultTextSizeParam + 'px';
    })
    pfOnChangeElement();
}

function defaultTextSizeChange(element) {
    if (element.value == '' || element.value < 1) {
        element.value = 1;
        defaultTextSizeParam = element.value;
        document.querySelectorAll('.password-free-button').forEach(div => {
            div.style.fontSize = defaultTextSizeParam + 'px';
            div.style.lineHeight = defaultTextSizeParam + 'px';
        })
    }
    pfOnChangeElement();
}

function defaultTextColorInput(element) {
    if (element.value == '') {
        element.value = '#ffffff';
    }
    if (CSS.supports('color', element.value) == '') {
        colorInputError(element)
    } else {
        colorInputErrorSolved(element)
        defaultTextColorParam = element.value;
        document.getElementById('password-free-default-text-color-id').value = defaultTextColorParam;
        document.querySelectorAll('.password-free-button').forEach(div => {
            div.style.color = defaultTextColorParam;
        })
    }
    pfOnChangeElement();
}

function defaultTextColor(element) {
    defaultTextColorParam = element.value;
    const input = document.getElementById('password-free-default-text-color-input-id');
    input.value = defaultTextColorParam;
    changeColorFieldSize(input);
    document.querySelectorAll('.password-free-button').forEach(div => {
        div.style.color = defaultTextColorParam;
    })
    colorInputErrorSolved(input);
    pfOnChangeElement();
}

function changeDefaultTextStyle(id, btnId) {
    switch (id) {
        case '0':
            const fontWeight = defaultTextBoldParam ? '300' : '600';
            document.querySelectorAll('.password-free-button').forEach(div => {
                div.style.fontWeight = fontWeight;
            });
            changeTextStyleBtn(defaultTextBoldParam, btnId)
            defaultTextBoldParam = !defaultTextBoldParam;
            break;
        case '1':
            const italic = defaultTextItalicParam ? 'normal' : 'italic';
            document.querySelectorAll('.password-free-button').forEach(div => {
                div.style.fontStyle = italic;
            });
            changeTextStyleBtn(defaultTextItalicParam, btnId);
            defaultTextItalicParam = !defaultTextItalicParam;
            break;
        case '2':
            const underline = defaultTextUnderlineParam ? 'none' : 'underline';
            document.querySelectorAll('.password-free-button').forEach(div => {
                div.style.textDecoration = underline;
            });
            changeTextStyleBtn(defaultTextUnderlineParam, btnId)
            defaultTextUnderlineParam = !defaultTextUnderlineParam;
            break;
    }
    pfOnChangeElement();
}

function hoverBackgroundColor(element) {
    hoverBackgroundColorParam = element.value;
    const input = document.getElementById('password-free-hover-background-color-input-id');
    input.value = hoverBackgroundColorParam;
    changeColorFieldSize(input);
    document.querySelectorAll('.password-free-button-hover').forEach(el => {
        el.style.background = hoverBackgroundColorParam;
    })
    colorInputErrorSolved(input);
    pfOnChangeElement();
}

function hoverBackgroundColorInput(element) {
    if (element.value == '') {
        element.value = '#ffffff';
    }
    if (CSS.supports('color', element.value) == '') {
        colorInputError(element)
    } else {
        colorInputErrorSolved(element)
        hoverBackgroundColorParam = element.value;
        document.getElementById('password-free-hover-background-color-id').value = hoverBackgroundColorParam;
        document.querySelectorAll('.password-free-button-hover').forEach(el => {
            el.style.background = hoverBackgroundColorParam;
        })
    }
    pfOnChangeElement();
}

function hoverCornerRadius(element) {
    if (event.data !== undefined && isNaN(event.data)) {
        element.value = hoverCornerRadiusParam;
    } else if (element.value > 1000) {
        element.value = 1000;
    }
    hoverCornerRadiusParam = element.value;
    document.querySelectorAll('.password-free-button-hover').forEach(el => {
        el.style.borderRadius = hoverCornerRadiusParam + 'px';
    })
    pfOnChangeElement();
}

function hoverCornerRadiusChange(element) {
    if (element.value == '') {
        element.value = 0;
        hoverCornerRadiusParam = element.value;
        document.querySelectorAll('.password-free-button-hover').forEach(el => {
            el.style.borderRadius = hoverCornerRadiusParam + 'px';
        })
    }
    pfOnChangeElement();
}

function hoverBorderThickness(element) {
    if (event.data !== undefined && isNaN(event.data)) {
        element.value = hoverBorderThicknessParam;
    } else if (element.value > 1000) {
        element.value = 1000;
    }
    hoverBorderThicknessParam = element.value;
    document.querySelectorAll('.password-free-button-hover').forEach(el => {
        el.style.borderWidth = hoverBorderThicknessParam + 'px';
    })
    pfOnChangeElement();
}

function hoverBorderThicknessChange(element) {
    if (element.value == '') {
        element.value = 0;
        hoverBorderThicknessParam = element.value;
        document.querySelectorAll('.password-free-button-hover').forEach(el => {
            el.style.borderWidth = hoverBorderThicknessParam + 'px';
        })
    }
    pfOnChangeElement();
}

function hoverBorderColorInput(element) {
    if (element.value == '') {
        element.value = '#ffffff';
    }
    if (CSS.supports('color', element.value) == '') {
        colorInputError(element)
    } else {
        colorInputErrorSolved(element)
        hoverBorderColorParam = element.value;
        document.getElementById('password-free-hover-border-color-id').value = hoverBorderColorParam;
        document.querySelectorAll('.password-free-button-hover').forEach(el => {
            el.style.borderColor = hoverBorderColorParam;
        })
    }
    pfOnChangeElement();
}

function hoverBorderColor(element) {
    hoverBorderColorParam = element.value;
    const input = document.getElementById('password-free-hover-border-color-input-id');
    input.value = hoverBorderColorParam;
    changeColorFieldSize(input);
    document.querySelectorAll('.password-free-button-hover').forEach(el => {
        el.style.borderColor = hoverBorderColorParam;
    })
    colorInputErrorSolved(input);
    pfOnChangeElement();
}

function hoverTextFont(element) {
    hoverTextFontParam = element.value;
    document.querySelectorAll('.password-free-button-hover').forEach(div => {
        div.style.fontFamily = fonts[hoverTextFontParam];
    })
    pfOnChangeElement();
}

function hoverTextSize(element) {
    if (event.data !== undefined && isNaN(event.data)) {
        element.value = hoverTextSizeParam;
    } else if (element.value > 1000) {
        element.value = 1000;
    } else if (element.value[0] == 0) {
        element.value = 1;
    }
    hoverTextSizeParam = element.value;
    document.querySelectorAll('.password-free-button-hover').forEach(div => {
        div.style.fontSize = hoverTextSizeParam + 'px';
        div.style.lineHeight = hoverTextSizeParam + 'px';
    })

    pfOnChangeElement();
}

function hoverTextSizeChange(element) {
    if (element.value == '' || element.value < 1) {
        element.value = 1;
        hoverTextSizeParam = element.value;
        document.querySelectorAll('.password-free-button-hover').forEach(div => {
            div.style.fontSize = hoverTextSizeParam + 'px';
            div.style.lineHeight =  hoverTextSizeParam + 'px';
        })
    }
    pfOnChangeElement();
}

function hoverTextColorInput(element) {
    if (element.value == '') {
        element.value = '#ffffff';
    }
    if (CSS.supports('color', element.value) == '') {
        colorInputError(element)
    } else {
        colorInputErrorSolved(element)
        hoverTextColorParam = element.value;
        document.getElementById('password-free-hover-text-color-id').value = hoverTextColorParam;
        document.querySelectorAll('.password-free-button-hover').forEach(div => {
            div.style.color = hoverTextColorParam;
        })
    }
    pfOnChangeElement();
}

function hoverTextColor(element) {
    hoverTextColorParam = element.value;
    const input = document.getElementById('password-free-hover-text-color-input-id');
    input.value = hoverTextColorParam;
    changeColorFieldSize(input);
    document.querySelectorAll('.password-free-button-hover').forEach(div => {
        div.style.color = hoverTextColorParam;
    })
    colorInputErrorSolved(input);
    pfOnChangeElement();
}

function changeHoverTextStyle(id, btnId) {
    switch (id) {
        case '0':
            const fontWeight = hoverTextBoldParam ? '300' : '600';
            document.querySelectorAll('.password-free-button-hover').forEach(div => {
                div.style.fontWeight = fontWeight;
            });
            changeTextStyleBtn(hoverTextBoldParam, btnId)
            hoverTextBoldParam = !hoverTextBoldParam;
            break;
        case '1':
            const italic = hoverTextItalicParam ? 'normal' : 'italic';
            document.querySelectorAll('.password-free-button-hover').forEach(div => {
                div.style.fontStyle = italic;
            });
            changeTextStyleBtn(hoverTextItalicParam, btnId);
            hoverTextItalicParam = !hoverTextItalicParam;
            break;
        case '2':
            const underline = hoverTextUnderlineParam ? 'none' : 'underline';
            document.querySelectorAll('.password-free-button-hover').forEach(div => {
                div.style.textDecoration = underline;
            });
            changeTextStyleBtn(hoverTextUnderlineParam, btnId)
            hoverTextUnderlineParam = !hoverTextUnderlineParam;
            break;
    }
    pfOnChangeElement();
}

function changeTextStyleBtn(isActive, btnId) {
    isActive ? stopTextStyle(btnId) : chooseTextStyle(btnId);
}

function stopChoosingAlignmentElement(item, wrapper) {
    wrapper.style.color = "rgba(140, 147, 173, 1)";
    item.style.border = "1px solid rgba(140, 147, 173, 1)";
    item.style.backgroundColor = "rgba(253, 253, 253, 1)";
}

function chooseAlignmentElement(item, wrapper) {
    wrapper.style.color = "rgba(60, 100, 244, 1)";
    item.style.border = "2px solid rgba(11, 56, 217, 1)";
    item.style.backgroundColor = "rgba(240, 243, 255, 1)";
}

function stopChoosingLogoElement(item, wrapper) {
    wrapper.style.color = "rgba(140, 147, 173, 1)";
    item.style.border = "1px solid rgba(140, 147, 173, 1)";
}

function chooseLogoElement(item, wrapper) {
    wrapper.style.color = "rgba(60, 100, 244, 1)";
    item.style.border = "2px solid rgba(11, 56, 217, 1)";
}

function chooseTextStyle(btnId) {
    const button = document.getElementById(btnId)
    button.style.fontWeight = 600;
    button.style.border = "2px solid rgba(11, 56, 217, 1)";
    button.style.background = "rgba(240, 243, 255, 1)";

}

function stopTextStyle(btnId) {
    const button = document.getElementById(btnId)
    button.style.fontWeight = 400;
    button.style.border = "1px solid rgba(140, 147, 173, 1)";
    button.style.background = "rgba(255, 255, 255, 1)";
}

function fillData(baseArray, alignmenArray, logoArray, textStyleArray, fontArray) {
    isDefaultArray = baseArray.is_default_array;
    fonts = fontArray;
    const defaultArray = baseArray.default;
    const hoverArray = baseArray.hover;
    alignmentItems(alignmenArray, baseArray.alignment_id, true);
    logoItems(logoArray, baseArray.logo_id, true);
    fillDefaultTextStyles(defaultArray['text_style'], textStyleArray);
    fillHovertTextStyles(hoverArray['text_style'], textStyleArray);

    if (isDefaultArray) {
        document.getElementById('password-free-customization-btn-default-id').disabled = true;
    }

    defaultBackgroundColorParam = defaultArray.background_color;
    document.getElementById('password-free-default-background-color-id').value = defaultBackgroundColorParam;
    const defaultBackgroundColor = document.getElementById('password-free-default-background-color-input-id');
    defaultBackgroundColor.value = defaultBackgroundColorParam;
    changeColorFieldSize(defaultBackgroundColor);

    defaultCornerRadiusParam = defaultArray.corner_radius;
    document.getElementById('password-free-default-corner-input-id').value = defaultCornerRadiusParam;

    defaultBorderThicknessParam = defaultArray.border_thickness;
    document.getElementById('password-free-default-border-input-id').value = defaultBorderThicknessParam;

    defaultBorderColorParam = defaultArray.border_color;
    document.getElementById('password-free-default-border-color-id').value = defaultBorderColorParam;
    const defaultBorderColor = document.getElementById('password-free-default-border-color-input-id');
    defaultBorderColor.value = defaultBorderColorParam;
    changeColorFieldSize(defaultBorderColor);

    defaultTextFontParam = defaultArray.text_font;
    document.getElementById('password-free-default-font').value = defaultTextFontParam;

    defaultTextSizeParam = defaultArray.text_size;
    document.getElementById('password-free-default-text-size-input-id').value = defaultTextSizeParam;

    defaultTextColorParam = defaultArray.text_color;
    document.getElementById('password-free-default-text-color-id').value = defaultTextColorParam;
    const defaultTextColor = document.getElementById('password-free-default-text-color-input-id');
    defaultTextColor.value = defaultTextColorParam;
    changeColorFieldSize(defaultTextColor);

    hoverBackgroundColorParam = hoverArray.background_color;
    document.getElementById('password-free-hover-background-color-id').value = hoverBackgroundColorParam;
    const hoverBackgroundColor = document.getElementById('password-free-hover-background-color-input-id');
    hoverBackgroundColor.value = hoverBackgroundColorParam;
    changeColorFieldSize(hoverBackgroundColor);

    hoverCornerRadiusParam = hoverArray.corner_radius;
    document.getElementById('password-free-hover-corner-input-id').value = hoverCornerRadiusParam;

    hoverBorderThicknessParam = hoverArray.border_thickness;
    document.getElementById('password-free-hover-border-input-id').value = hoverBorderThicknessParam;

    hoverBorderColorParam = hoverArray.border_color;
    document.getElementById('password-free-hover-border-color-id').value = hoverBorderColorParam;
    const hoverBorderColor = document.getElementById('password-free-hover-border-color-input-id');
    hoverBorderColor.value = hoverBorderColorParam;
    changeColorFieldSize(hoverBorderColor);

    hoverTextFontParam = hoverArray.text_font;
    document.getElementById('password-free-hover-font').value = hoverTextFontParam;

    hoverTextSizeParam = hoverArray.text_size;
    document.getElementById('password-free-hover-text-size-input-id').value = hoverTextSizeParam;

    hoverTextColorParam = hoverArray.text_color;
    document.getElementById('password-free-hover-text-color-id').value = hoverTextColorParam;
    const hoverTextColor = document.getElementById('password-free-hover-text-color-input-id');
    hoverTextColor.value = hoverTextColorParam;
    changeColorFieldSize(hoverTextColor);

    registrURL = baseArray.register_url;
    document.getElementById('password-free-url-change-register').value = registrURL;
    authURL = baseArray.auth_url;
    document.getElementById('password-free-url-change-auth').value = authURL;

    unBlockApplyBtn();

    buttonsStyling(fontArray);
}

function fillHovertTextStyles(textStyle, buttons) {
    hoverTextBoldParam = textStyle.bold;
    hoverTextItalicParam = textStyle.italic;
    hoverTextUnderlineParam = textStyle.underline;
    if (hoverTextBoldParam) {
        chooseTextStyle(buttons[0].btn_hover_id)
    } else {
        stopTextStyle(buttons[0].btn_hover_id)
    }
    if (hoverTextItalicParam) {
        chooseTextStyle(buttons[1].btn_hover_id)
    } else {
        stopTextStyle(buttons[1].btn_hover_id)
    }
    if (hoverTextUnderlineParam) {
        chooseTextStyle(buttons[2].btn_hover_id)
    } else {
        stopTextStyle(buttons[2].btn_hover_id)
    }
}

function fillDefaultTextStyles(textStyle, buttons) {
    defaultTextBoldParam = textStyle.bold;
    defaultTextItalicParam = textStyle.italic;
    defaultTextUnderlineParam = textStyle.underline;
    if (defaultTextBoldParam) {
        chooseTextStyle(buttons[0].btn_default_id)
    } else {
        stopTextStyle(buttons[0].btn_default_id)
    }
    if (defaultTextItalicParam) {
        chooseTextStyle(buttons[1].btn_default_id)
    } else {
        stopTextStyle(buttons[1].btn_default_id)
    }
    if (defaultTextUnderlineParam) {
        chooseTextStyle(buttons[2].btn_default_id)
    } else {
        stopTextStyle(buttons[2].btn_default_id)
    }
}


function buttonsStyling(fonts) {
    document.querySelectorAll('.password-free-button').forEach(element => {
        element.style.background = defaultBackgroundColorParam;
        element.style.borderRadius = defaultCornerRadiusParam + 'px';
        element.style.border = defaultBorderThicknessParam + 'px' + ' solid ' + defaultBorderColorParam;
        element.style.fontWeight = defaultTextBoldParam ? '600' : '300';
        element.style.fontStyle = defaultTextItalicParam ? 'italic' : 'normal';
        element.style.textDecoration = defaultTextUnderlineParam ? 'underline' : 'none';
        element.style.fontFamily = fonts[defaultTextFontParam];
        element.style.fontSize = defaultTextSizeParam + 'px';
        element.style.lineHeight = defaultTextSizeParam + 'px';
        element.style.color = defaultTextColorParam;

    })
    document.querySelectorAll('.password-free-button img').forEach(img => {
        img.src = logoSrcParam;
    })

    document.querySelectorAll('.password-free-button-hover').forEach(element => {
        element.style.background = hoverBackgroundColorParam;
        element.style.borderRadius = hoverCornerRadiusParam + 'px';
        element.style.border = hoverBorderThicknessParam + 'px' + ' solid ' + hoverBorderColorParam;
        element.style.fontWeight = defaultTextBoldParam ? '600' : '300';
        element.style.fontStyle = defaultTextItalicParam ? 'italic' : 'normal';
        element.style.textDecoration = defaultTextUnderlineParam ? 'underline' : 'none';
        element.style.fontFamily = fonts[hoverTextFontParam];
        element.style.fontSize = hoverTextSizeParam + 'px';
        element.style.lineHeight = hoverTextSizeParam + 'px';
        element.style.color = hoverTextColorParam;
    })
    document.querySelectorAll('.password-free-button-hover img').forEach(img => {
        img.src = logoSrcParam;
    })
}

function pfApplyChanges(urlApply, urlApplyDefault, key) {
    if (isDefaultArray) {
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                document.getElementById('password-free-customization-popup-accept-apply').style.display = 'flex';
                setTimeout(pfCloseAcceptApplyPopUp, 2000);
            }
        }
        xmlhttp.open("POST", urlApplyDefault, true);
        xmlhttp.setRequestHeader('Authorization', key);
        xmlhttp.send();
    } else {
        const customArray = {
            register_url: registrURL,
            auth_url: authURL,
            is_default_array: isDefaultArray,
            alignment_id: alignmentIdParam,
            logo_id: logoIdParam,
            default: {
                background_color: defaultBackgroundColorParam,
                corner_radius: defaultCornerRadiusParam,
                border_thickness: defaultBorderThicknessParam,
                border_color: defaultBorderColorParam,
                text_font: defaultTextFontParam,
                text_size: defaultTextSizeParam,
                text_style: {
                    bold: defaultTextBoldParam,
                    italic: defaultTextItalicParam,
                    underline: defaultTextUnderlineParam
                },
                text_color: defaultTextColorParam
            },
            hover: {
                background_color: hoverBackgroundColorParam,
                corner_radius: hoverCornerRadiusParam,
                border_thickness: hoverBorderThicknessParam,
                border_color: hoverBorderColorParam,
                text_font: hoverTextFontParam,
                text_size: hoverTextSizeParam,
                text_style: {
                    bold: hoverTextBoldParam,
                    italic: hoverTextItalicParam,
                    underline: hoverTextUnderlineParam
                },
                text_color: hoverTextColorParam
            },
        };
        const element = document.querySelector('.password-free-button');
        const culcHeight = parseInt(defaultTextSizeParam) + (defaultBorderThicknessParam * 2) + 12;
        const height = element.offsetHeight > culcHeight ? element.offsetHeight : culcHeight;
        const data = {
            customArray: customArray,
            width: element.offsetWidth,
            height: height
        }
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                document.getElementById('password-free-customization-popup-accept-apply').style.display = 'flex';
                setTimeout(pfCloseAcceptApplyPopUp, 2000);
            }
        }
        xmlhttp.open("POST", urlApply, true);
        xmlhttp.setRequestHeader('Authorization', key);
        xmlhttp.send(JSON.stringify(data));
    }
    isChanged = false;
    localStorage.removeItem(passwordFree)
}

function pfDefaultSettings(url, key) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            const response = JSON.parse(xmlhttp.responseText);
            fillData(response.baseArray, response.alignmentArray, response.logoArray, response.buttonsArray, fonts)
            document.getElementById('password-free-customization-popup-accept-default').style.display = 'flex';
            setTimeout(pfCloseAcceptDefaultPopUp, 2000);
            isChanged = true;
            document.getElementById('password-free-customization-btn-default-id').disabled = true;
            document.querySelectorAll('.password-free-customization-settings input[type="text"]').forEach(element => {
                element.style.border = '1px solid rgba(253, 253, 253, 1)';
            });
        }
    }
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader('Authorization', key);
    xmlhttp.send();
}

function pfCloseAcceptApplyPopUp() {
    document.getElementById('password-free-customization-popup-accept-apply').style.display = 'none';
}

function pfCloseAcceptDefaultPopUp() {
    document.getElementById('password-free-customization-popup-accept-default').style.display = 'none';
}

function pfOnChangeElement() {
    isDefaultArray = false;
    isChanged = true;
    document.getElementById('password-free-customization-btn-default-id').disabled = false;
    localStorage[passwordFree] = 'changed';
}

function changePopUpStay() {
    document.getElementById('password-free-customization-overlay').style.display = 'none';
    document.getElementById('password-free-customization-change-popup-id').style.display = 'none';
}

function changePopUpLeave() {
    isInterceptRedirect = false;
    window.location.href = outUrl;
}

function changeRegisterUrl(element) {
    if (isValidUrl(element.value)) {
        registrURL = element.value;
        unBlockApplyBtn()
        element.style.borderColor = 'rgba(217, 220, 233, 1)'
        document.getElementById('password-free-customization-register-error-id').style.display = 'none';
    } else {
        blockApplyBtn()
        element.style.borderColor = 'rgba(204, 31, 31, 1)';
        document.getElementById('password-free-customization-register-error-id').style.display = 'block';
    }
    pfOnChangeElement();
}

function changeAuthUrl(element) {
    if (isValidUrl(element.value)) {
        authURL = element.value;
        unBlockApplyBtn()
        element.style.borderColor = 'rgba(217, 220, 233, 1)'
        document.getElementById('password-free-customization-auth-error-id').style.display = 'none';
    } else {
        blockApplyBtn()
        element.style.borderColor = 'rgba(204, 31, 31, 1)';
        document.getElementById('password-free-customization-auth-error-id').style.display = 'block';
    }
    pfOnChangeElement();
}

function isValidUrl(textUrl) {
    let url;
    if (textUrl == '') {
        return true;
    }
    try {
        url = new URL(textUrl);
    } catch (e) {
        return false;
    }
    return url.protocol === "http:" || url.protocol === "https:";
}

function blockApplyBtn() {
    document.getElementById('password-free-customization-btn-apply-id').disabled = true;
}

function unBlockApplyBtn() {
    document.getElementById('password-free-customization-btn-apply-id').disabled = false;
}

function colorInputError(element) {
    blockApplyBtn()
    element.style.border = '1px solid rgba(204, 31, 31, 1)';
    element.style.paddingLeft = '8px';
}

function colorInputErrorSolved(element) {
    unBlockApplyBtn();
    element.style.border = '1px solid rgba(253, 253, 253, 1)';
    element.style.paddingLeft = 0;
}

function getTextSize(string) {
    let span = document.createElement('span');
    span.innerHTML = string;
    document.body.append(span);
    const textSize = span.offsetWidth;
    span.remove();
    return textSize;
}

function changeColorFieldSize(element) {
    const minSize = 40;
    const calculatedSize = getTextSize(element.value) + 6;
    const size = calculatedSize < minSize ? minSize : calculatedSize;
    element.style.width = size + 'px';
}