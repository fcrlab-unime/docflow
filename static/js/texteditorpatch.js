console.log("Docflow texteditorpatch.js loaded");

var updatedMenububbles = [];
var data;

function getSelectionText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    return text;
}

function tag(selection, tagString) {
    if (selection.rangeCount) {
        var selectionText = getSelectionText();
        range = selection.getRangeAt(0);
        range.deleteContents();
        range.insertNode(document.createTextNode("[" + selectionText + "]" + tagString));
    }
}

function hideContextMenu(contextmenu) {
    contextmenu.style.display = "none"
}

function showContextMenu(contextmenu, menububble) {

    console.log('menububble', menububble);
    console.log('contextmenu', contextmenu);

    //SHOW CONTEXT MENU
    contextmenu.style.display = "block"
    contextmenu.style.left = menububble.style.left;
    contextmenu.style.top = menububble.style.top;
    //HIDE MENUBUBBLE
    menububble.className = "menububble";
}

function createContextMenu(editor) {
    console.log('creating new docflow editor context menu');

    const contextmenu = document.createElement("div");
    contextmenu.id = "docflow-contextmenu";
    contextmenu.className = "docflow-context-menu-editor";
    /*     contextmenu.setAttribute("data-v-190f543d", "");
        contextmenu.setAttribute("data-v-5496c5380", ""); */
    contextmenu.style.display = "none";

    const contextmenuItemList = document.createElement("ul");
    contextmenuItemList.className = "menu-editor-docflow";

    data.forEach(function (tagObject) {
        const contextmenuItem = document.createElement("li");
        const contextmenuItemLink = document.createElement("a");
        contextmenuItemLink.href = "#";
        contextmenuItemLink.innerHTML = tagObject.label;
        contextmenuItemLink.className = "docflow-context-menu-editor-entry";
        contextmenuItem.appendChild(contextmenuItemLink);
        contextmenuItem.addEventListener("click", function (event) {
            const selection = window.getSelection();
            tag(selection, tagObject.tag_string);
            hideContextMenu(contextmenu);
        });
        contextmenuItemList.appendChild(contextmenuItem);
    });

    contextmenu.appendChild(contextmenuItemList);
    editor.appendChild(contextmenu);
    console.log('new docflow editor context menu created', contextmenu);
    return contextmenu;
}

function createTagButton(menububble, index) {
    //console.log("creating tag button...");
    let tagButton = document.getElementById("docflow-tag-button-" + index.toString());
    if (!tagButton) {
        console.log("tag button not detected, creating new...");
        tagButton = document.createElement("button");
        tagButton.id = "docflow-tag-button-" + index.toString();
        tagButton.setAttribute("data-v-190f543d", "");
        tagButton.className = "menububble__button docflow-tag-button";
        buttonIcon = document.createElement("span");
        buttonIcon.className = "icon-tag";
        buttonIcon.setAttribute("data-v-190f543d", "");
        buttonText = document.createElement("span");
        buttonText.className = "menububble__buttontext";
        buttonText.innerHTML = t("docflow", "Tag");
        buttonText.setAttribute("data-v-190f543d", "");
        tagButton.appendChild(buttonIcon);
        tagButton.appendChild(buttonText);
        menububble.appendChild(tagButton);
        console.log("tag button created");
    }

    const editor = menububble.parentNode;
    let contextmenu = createContextMenu(editor);

    //UPDATE TAG BUTTON EVENT LISTENER
    console.log("updating tag button event listener...");
    tagButton = document.getElementById("docflow-tag-button-" + index.toString());
    tagButton.addEventListener("click", function (event) {
        console.log("tag button clicked");
        //SHOW CONTEXT MENU
        showContextMenu(contextmenu, menububble);

    });
    //console.log("tag button event listener updated");
}

function waitForMenuBubble() {
    //console.log("searching for menu bubble...");
    const menububbles = Array.from(document.getElementsByClassName("menububble"));
    //console.log("menububbles", menububbles);
    menububbles.forEach(function (menububble, index) {
        if (!updatedMenububbles.includes(menububble)) {
            console.log("new menu bubble detected", menububble);
            createTagButton(menububble, index);
            updatedMenububbles.push(menububble);
        }
    });
    setTimeout(function () {
        waitForMenuBubble();
    }, 1000);
}

function findAncestorByClassName(el, ancestorClass) {
        if (el.className.includes(ancestorClass)) {
            console.log('found ancestor');
            return true;
            //return el;
        } else {
            console.log('el is not the ancestor');
            if (el.parentElement) {
                console.log('el has parent');
                return findAncestorByClassName(el.parentElement, ancestorClass);
            } else {
                console.log('el has no parent');
                return false;
            }
        }
}

function getData(){
    //make a get request to the server and return the result as json
    var request = new XMLHttpRequest();
    request.open('GET', window.location.protocol + '//' + window.location.host + OC.generateUrl('/apps/docflow/taglist'), false);
    request.send(null);
    if (request.status === 200) {
        return JSON.parse(request.responseText);
    }
    return false;
}

document.onreadystatechange = function () {
    console.log('document.readyState', document.readyState);
    if (document.readyState == "complete") {
        data = getData();
        console.log('data', data);
        if(data){
            waitForMenuBubble();
            document.addEventListener("click", function (event) {
                console.log("document clicked");
                console.log("event.target", event.target);
                if (!event.target.className.includes("docflow-context-menu-editor-entry")) {
                    console.log("not context menu entry");
                    if (!findAncestorByClassName(event.target, "docflow-tag-button")) {
                        console.log("not tag button");
                        const contextmenuList = document.getElementsByClassName("docflow-context-menu-editor");
                        contextmenuList.forEach(function (contextmenu) {
                            hideContextMenu(contextmenu);
                        });
                    }
                }
            });
        } else {
            console.log('no data');
        }
    } 
}