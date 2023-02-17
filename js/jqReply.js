//creates succes div with js
function createSuccesDiv(succes) {
    let item = document.createElement("div");
    item.classList.add("item");

    let errorsDiv = document.createElement("div");
    errorsDiv.classList.add("succes");

    let span = document.createElement("p");
    span.appendChild(document.createTextNode(succes));
    errorsDiv.appendChild(span);

    return item.appendChild(errorsDiv);
}

//creates error div with js
function createErrorDiv(error) {
    let item = document.createElement("div");
    item.classList.add("item");

    let errorsDiv = document.createElement("div");
    errorsDiv.classList.add("errors");

    let span = document.createElement("p");
    span.appendChild(document.createTextNode(error));
    errorsDiv.appendChild(span);

    return item.appendChild(errorsDiv);
}
